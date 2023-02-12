<?php

namespace App\bit\webspace\security;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:generate-keys', description: 'Creates a new key pair.')]
class SecurityService extends Command {

  protected function execute(InputInterface $input, OutputInterface $output): int {
    try {

      $secretKeyPath = $_ENV['JWT_SECRET_KEY'];
      $secretKeyPath = str_replace('%kernel.project_dir%', $_SERVER['DOCUMENT_ROOT'], $secretKeyPath);
      $output->writeln("Secret key path: {$secretKeyPath}");
      $publicKeyPath = $_ENV['JWT_PUBLIC_KEY'];
      $publicKeyPath = str_replace('%kernel.project_dir%', $_SERVER['DOCUMENT_ROOT'], $publicKeyPath);
      $output->writeln("public key path: {$publicKeyPath}");
      $keySecret = $_ENV['JWT_KEY_SECRET'];
      if (!$keySecret) {
        $output->writeln("You must set a JWT_KEY_SECRET. This must be a BASE64 encoded string.");
//        $output->writeln("You must set a JWT_KEY_SECRET with a minimum length of "
//          . SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
        return Command::FAILURE;
      }
      $keySecretDecoded = base64_decode($keySecret);
      if (!$keySecretDecoded || strlen($keySecretDecoded) !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
        $output->writeln(
          "You must set a JWT_KEY_SECRET. This must be a BASE64 encoded string with a decoded length of "
          . SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
        return Command::FAILURE;
      }

      if (!file_exists($secretKeyPath)) {
        $output->writeln('Start key generation');
        $keypair = sodium_crypto_sign_keypair();
        KeyHelper::saveKeypair($keypair, $keySecretDecoded, 'Ed25519', $secretKeyPath);
        KeyHelper::saveArmored('Ed25519 PUBLIC KEY', base64_encode(sodium_crypto_sign_publickey($keypair)), $publicKeyPath);
      } else {
        $output->writeln('Keys files available');
        $keypair = KeyHelper::loadKeyPair($keySecretDecoded, $secretKeyPath);
        if (!$keypair) {
          $output->writeln('unable to load key pair');
          return Command::FAILURE;
        }
      }

      $privateKey = base64_encode(sodium_crypto_sign_secretkey($keypair));
      $publicKey = base64_encode(sodium_crypto_sign_publickey($keypair));

      $output->writeln('private key:' . $privateKey);
      $output->writeln('public key:' . $publicKey);

      $payload = [
        'iss' => 'example.org',
        'aud' => 'example.com',
        'iat' => 1356999524,
        'nbf' => 1357000000
      ];

      $jwt = JWT::encode($payload, $privateKey, 'EdDSA');
      echo "Encode:\n" . print_r($jwt, true) . "\n";

      $decoded = JWT::decode($jwt, new Key($publicKey, 'EdDSA'));
      echo "Decode:\n" . print_r((array)$decoded, true) . "\n";

    } catch (Exception $ex) {
      $output->writeln($ex->getMessage());
      return Command::FAILURE;
    }
    return Command::SUCCESS;
  }
}
