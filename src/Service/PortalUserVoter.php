<?php

namespace App\Service;


use App\Entity\Merchant\PortalUserDetails;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


class PortalUserVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['EDIT', 'VIEW', 'DELETE'])
            && $subject instanceof PortalUserDetails;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Super admin can do anything
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        }

        // Admin can edit users from their own merchant
        if ($this->security->isGranted('ROLE_ADMIN')) {
            // Add your merchant-specific logic here
            return true; // Or your custom merchant validation
        }

        return false;
    }
}
