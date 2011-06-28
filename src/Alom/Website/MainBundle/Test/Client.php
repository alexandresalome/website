<?php

namespace Alom\Website\MainBundle\Test;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;

/**
 * Test client for Alom project.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Client extends BaseClient
{
    /**
     * Connect to Alom website
     *
     * @param string $username Username to use
     * @param string $password Password to use
     *
     * @throws LogicException Throws an exception if not able to connect
     */
    public function connect($username = 'user', $password = 'user')
    {
        $crawler = $this->request('GET', '/login');

        $form = $crawler
            ->filter('div.login-form-wrapper form input[type=submit]')
            ->form(array(
            '_username' => $username,
            '_password' => $password
        ));

        $this->submit($form);

        if (! $this->getResponse()->isRedirect()) {
            throw new \LogicException("Login should redirect !");
        }

        $this->request('GET', '/');

        if (!preg_match('/Logout/', $this->getResponse()->getContent())) {
            throw new \LogicException("Doesn't look like you are connected !");
        }

        return $crawler;
    }
}
