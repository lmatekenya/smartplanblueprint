<?php

namespace App\Service;

use App\Entity\Merchant\PortalUserDetails;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

//        if (!$user->isEnabled()) {
//            throw new CustomUserMessageAccountStatusException('Your account has been disabled. Please contact an administrator.');
//        }

        if (!method_exists($user, 'isEnabled')) {
            throw new \RuntimeException('User entity does not have isEnabled method');
        }

        if (!$user->isEnabled()) {
            throw new CustomUserMessageAccountStatusException('Your account has been disabled. Please contact an administrator.');
        }

    }

    public function checkPostAuth(UserInterface $user): void
    {
        // No action needed after authentication
    }
}
