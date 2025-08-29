<?php

namespace App\Repository\HeaderIcons;

use App\Entity\HeaderIcons\UserPreference;
use App\Entity\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserPreferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPreference::class);
    }

    /**
     * Find all preferences for a user
     */
    public function findByUser(User $user): array
    {
        return $this->findBy(['user' => $user]);
    }

    /**
     * Find a specific preference by key for a user
     */
    public function findOneByKey(User $user, string $key): ?UserPreference
    {
        return $this->findOneBy([
            'user' => $user,
            'key' => $key
        ]);
    }

    /**
     * Save a preference
     */
    public function save(UserPreference $preference): void
    {
        $this->_em->persist($preference);
        $this->_em->flush();
    }

    /**
     * Delete a preference
     */
    public function delete(UserPreference $preference): void
    {
        $this->_em->remove($preference);
        $this->_em->flush();
    }

    /**
     * Get a preference value or return default
     */
    public function getValue(User $user, string $key, $default = null)
    {
        $preference = $this->findOneByKey($user, $key);
        if ($preference) {
            return $preference->getValue();
        }
        return $default;

    }
}
