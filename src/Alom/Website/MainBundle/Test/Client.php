<?php

namespace Alom\Website\MainBundle\Test;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;

/**
 * Test client for Alom project.
 *
 * By default, it enables the isolation of tests. If you want to override this
 * behavior, you can call the ``commit`` method at the end of your execution.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Client extends BaseClient
{
    /**
     * Doctrine DBAL connection of the client
     *
     * @var Doctrine\DBAL\Driver\Connection
     */
    protected $connection;

    /**
     * @inherited
     */
    protected function doRequest($request)
    {
        if (null === $this->connection) {
            $this->connection = $this->kernel->getContainer()->get('doctrine.dbal.default_connection');
            $this->connection->beginTransaction();
        }
        $this->kernel->shutdown();
        $this->kernel->boot();
        $this->kernel->getContainer()->set('doctrine.dbal.default_connection', $this->connection);

        return $this->kernel->handle($request);
    }

    /**
     * Commit the Doctrine connection transaction.
     *
     * @throws \LogicException Throws an exception if no connection was found
     */
    public function commit()
    {
        if ($this->connection === null) {
            throw new \LogicException("Cannot commit : no connection found");
        }
        $this->connection->commit();
        $this->connection = null;
    }

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
