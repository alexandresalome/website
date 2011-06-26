<?php
namespace Alom\Website\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUser(UserInterface $user)
    {
        $this->getEntityManager()->refresh($user);
    }

    public function loadUserByUsername($username)
    {
        return $this->findOneBy(array('username' => $username));
    }

    public function supportsClass($class)
    {
        return $class == 'Alom\Website\MainBundle\Entity\User';
    }

    public function refreshUser(UserInterface $user)
    {
        $this->getEntityManager()->refresh($user);

        return $user;
    }
}
