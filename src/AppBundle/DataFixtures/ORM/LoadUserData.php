<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('user');
        $user->setIsAdmin(false);
        $this->setPassword($user, 'user');
        $manager->persist($user);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setIsAdmin(true);
        $this->setPassword($admin, 'admin');
        $manager->persist($admin);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function setPassword(User $user, $password)
    {
        $factory = $this->container->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword($password, $user->regenerateSalt());
        $user->setPassword($password);
    }
}
