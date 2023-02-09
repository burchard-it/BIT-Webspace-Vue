<?php

namespace App\bit\webspace\security;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface, EventSubscriberInterface {

  public function __construct(protected LoggerInterface $logger) {
  }

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents() {
    return [
      LoginFailureEvent::class => 'onLoginFailure',
      LoginSuccessEvent::class => 'onLoginSuccess',
    ];
  }

  /**
   * Symfony calls this method if you use features like switch_user
   * or remember_me.
   *
   * If you're not using these features, you do not need to implement
   * this method.
   *
   * @throws UserNotFoundException if the user is not found
   */
  public function loadUserByIdentifier($identifier): UserInterface {
    $this->logger->debug('UserProvider->loadUserByIdentifier', ['identifier' => $identifier]);

    // Load a User object from your data source or throw UserNotFoundException.
    // The $identifier argument may not actually be a username:
    // it is whatever value is being returned by the getUserIdentifier()
    // method in your User class.
    $user = new User();
    $user->setUsername($identifier);
    $user->setPassword('<A secret BCrypt hash for testing>');
    return $user;
//    $ex = new UserNotFoundException();
//    $ex->setUserIdentifier($identifier);
//    throw $ex;
  }

  /**
   * Not quite as nice, but the only reasonably good way I have found to find out the user for whom an incorrect login
   * attempt was made.
   * This only works because I know what structure my login has.
   * Only because I use `json_login` exclusively and because I have configured the properties `username_path` and
   * `password_path` in `security.yaml`.
   *
   * @param LoginFailureEvent $event
   * @return void
   */
  public function onLoginFailure(LoginFailureEvent $event): void {
    $data = json_decode($event->getRequest()->getContent(), true);
    $this->logger->debug('login failure', ['user' => $data['userId']]);
  }

  /**
   * Very nice way to get the user.
   *
   * @param LoginSuccessEvent $event
   * @return void
   */
  public function onLoginSuccess(LoginSuccessEvent $event): void {
    $this->logger->debug('login success', ['user' => $event->getUser()->getUserIdentifier()]);
  }

  /**
   * Refreshes the user after being reloaded from the session.
   *
   * When a user is logged in, at the beginning of each request, the
   * User object is loaded from the session and then this method is
   * called. Your job is to make sure the user's data is still fresh by,
   * for example, re-querying for fresh User data.
   *
   * If your firewall is "stateless: true" (for a pure API), this
   * method is not called.
   */
  public function refreshUser(UserInterface $user): UserInterface {
    $this->logger->debug('UserProvider->refreshUser', ['user' => $user]);
    if (!$user instanceof User) {
      throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
    }

    // Return a User object after making sure its data is "fresh".
    // Or throw a UsernameNotFoundException if the user no longer exists.
    return $user;
  }

  /**
   * Tells Symfony to use this provider for this User class.
   */
  public function supportsClass(string $class): bool {
    $this->logger->debug('UserProvider->supportsClass', ['class' => $class]);
    return User::class === $class || is_subclass_of($class, User::class);
  }

  /**
   * Upgrades the hashed password of a user, typically for using a better hash algorithm.
   */
  public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void {
    // TODO: when hashed passwords are in use, this method should:
    // 1. persist the new password in the user storage
    // 2. update the $user object with $user->setPassword($newHashedPassword);
    $this->logger->debug('UserProvider->upgradePassword', ['user' => $user, 'newHashedPassword' => $newHashedPassword]);
  }

}
