<?php

namespace Alom\Website\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass="Alom\Website\MainBundle\Entity\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length="32")
     */
    protected $username;


    /**
     * @ORM\Column(type="string", length="128")
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length="32")
     */
    protected $passwordSalt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isAdmin;

    public function getId()
    {
        return $this->id;
    }

    public function getSalt()
    {
        return $this->passwordSalt;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        if ($this->isAdmin()) {
            return array('ROLE_USER', 'ROLE_ADMIN');
        } else {
            return array('ROLE_USER');
        }
    }

    public function eraseCredentials()
    {
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function equals(UserInterface $user)
    {
        return $user->getId() == $this->getId();
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function isAdmin()
    {
        return $this->isAdmin;
    }

    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function regenerateSalt()
    {
        return $this->passwordSalt = md5(microtime() . uniqid());
    }
}
