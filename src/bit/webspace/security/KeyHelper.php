<?php

namespace App\bit\webspace\security;

use Exception;
use SodiumException;

class KeyHelper {

  /**
   * Generate a new key pair for EdDSA (Ed25519)
   *
   * @throws SodiumException
   */
  public static function generateEd25519Keypair(): string {
    return sodium_crypto_sign_keypair();
  }

  /**
   * Generates a secret key with the correct length that can be used to encrypt a key pair.
   *
   * @return string
   */
  public static function generateSecretKey(): string {
    return sodium_crypto_secretbox_keygen();
  }

  /**
   * Load an armored text file and returns the content without BEGIN and END Block.
   *
   * @param string $filePath
   * @return string|null A BASE64 encoded string or null, if the file does not exists
   */
  public static function loadArmored(string $filePath): string|null {
    if (!file_exists($filePath)) {
      return null;
    }
    $armoredKey = file_get_contents($filePath);
    $startPos = strpos($armoredKey, "\n");
    $endPos = strpos($armoredKey, '-----END');
    return preg_replace('/\r\n|\r|\n/', '', substr($armoredKey, $startPos, $endPos - $startPos));
  }

  /**
   * Save a BASE64 encoded string into a file using armored text format.
   *
   * -----BEGIN ....-----
   * PYXXZrgX7N6....
   * -----END ....-----
   *
   * It will be automatically split into text lines of maximum 70 characters.
   *
   * @param string $header text that is written into header and footer
   * @param string $data the content
   * @param string $filePath
   * @return void
   */
  public static function saveArmored(string $header, string $data, string $filePath): void {
    $armoredKey = "-----BEGIN {$header}-----\n";

    $dataLines = chunk_split($data, 70, "\n");
    $armoredKey .= $dataLines;

    $armoredKey .= "-----END {$header}-----";

    file_put_contents($filePath, $armoredKey);
  }

  /**
   * @param string $secretKey
   * @param string $filePath
   * @return string|null
   * @throws SodiumException
   */
  public static function loadKeyPair(string $secretKey, string $filePath): string|null {
    $armored = self::loadArmored($filePath);
    if (!$armored) {
      return null;
    }
    $decoded = base64_decode($armored);
    $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
    $encrypted_result = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');
    return sodium_crypto_secretbox_open($encrypted_result, $nonce, $secretKey);
  }

  /**
   * Save a key pair to a file using an encryption key.
   *
   * @param string $keyPair
   * @param string $secretKey
   * @param string $type will be added to the Header and Footer of the armored key file
   * @param string $filePath
   * @throws SodiumException
   * @throws Exception
   */
  public static function saveKeypair(string $keyPair, string $secretKey, string $type, string $filePath): void {
    $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    $encrypted_result = sodium_crypto_secretbox($keyPair, $nonce, $secretKey);
    $encoded = base64_encode($nonce . $encrypted_result);
    self::saveArmored("{$type} PRIVATE KEY", $encoded, $filePath);
  }
}
