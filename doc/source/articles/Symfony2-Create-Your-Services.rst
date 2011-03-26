Symfony2 - Create your services
===============================

.. WARNING::

   This article is being written in September 2010. Symfony2 is very
   unstable and things will change.

A new approach of conception
----------------------------

Suppose you have to create a "contact" bundle : 1 contact page with a form, a
confirmation page when submitting. It will send 2 mails : 1 to website owner,
1 to contact.

E-mails content must be template, because we will need template engine.

Every message must be persisted in database.

To resume, we require the following services :

* Web Validation
* ORM Entity Manager
* Template Engine
* Mailer service

sfContext has renamed to Container
----------------------------------

In symfony 1.x, beginners called ``sfContext::getInstance()`` anywhere in the
application : forms, templates, models, etc. It provides an easy access to
current application context (user, request, etc.); but also generated a lot
of conception problems : critical coupling, errors from outer space and lot of
funny stuff.

Now, in Symfony2, there is another big supermassive object : the container.

It contains every services of the application.

The container must kept in the controller.

Use it as argument will **never** be a good idea.

I repeat :

Use it as argument will **never** be a good idea.

For example, the very bad idea would be :

.. code-block:: html+php

    <?php
    public function saveAction($messageArray) {
        $message = new Message($messageArray);
        $manager = new MessageManager($this->container);
        $manager->save($message); // render template and send emails
    }

__Double penality__ : You created a manager, and put a container in it.

It will correctly work (sure...), but your code is coupled to the container.

It Jimmy, from Texas wants to reuse your code in his home made framework,
he will have to create a container, put 3 services in it, think about this new
container performances, the coherence with his own framework, and he will get
frustrated and trash your code because he his facing a conception problem.

::NOTICE:: If you are familiar with context problematics and understand  the
concept of dependency injection, jump to next chapter.

Now, keep in mind this "magic" manager, that will work with template engine
and mailer service.

Symfony2 ? No, never heard of that. I'm thinking about a manager, that send
rendered templates to emails.

.. code-block:: html+php

    <?php
    class MessageManager
    {
        public function __construct($engine, $mailer)
        {
            $this->engine = $engine;
            $this->mailer = $mailer;
        }

        public function sendMessage(Message $message)
        {
           $body = $this->engine->render('MailBundle:Contact:confirmation');
           $mail = ...;
           $this->mailer->send($mail);

           $body = $this->engine->render('MailBundle:Contact:notification');
           $mail = ...;
           $this->mailer->send($mail);
        }
    }

So easy to use it :

.. code-block:: html+php

    <?php
    public function saveAction($message) {
        $manager = ...;
        $manager->save($message);
    }

The only problem is : how to get my manager ?

Approach of services
--------------------

We are going to define a new service : the famous manager.

It will be called contact handler, and will handle the creation and sending of
contact messages.

What we want, in our controller :

.. code-block:: html+php

    <?php
    public function saveAction(Contact $contact) {
        $this->container->get('contact.handler')->persistAndSend($contact);

        return $this->redirect('ContactBundle:Contact:Confirmation.html.twig');
    }

How we want to configure it :

.. code-block:: yaml

    contact.handler:
        sender.name:           "Alexandre-Salome.fr - Contact message"
        sender.email:          "contact@alexandre-salome.fr"

        notified.name:         "Alexandre Salomé"
        notified.email:        "alexandre.salome@gmail.com"

        confirmation.subject:  "Your message was successfully sent"
        confirmation.template: "ContactBundle:Contact:Confirmation"

        notification.subject:  "[Website] %fullname% : %subject%"
        notification.template: "ContactBundle:Contact:Notification"

Modelize our contact handler
----------------------------

How we want it to be initialized :

.. code-block:: html+php

    <?php
    $handler = new ContactHandler($engine, $entityManager, $mailer);

How we want it to be used :

.. code-block:: html+php

    <?php
    $handler->persistAndSend($contact);

Now that we know the interface of the handler, we can modelize it.

Public API is :

@todo

To see full sourcecode, go to Github.

@todo fragment

And model is finished ! How many lines of revelant code do you see ? The big part
of this handler is configuration : 8 mandatory options.


### Now, let's define our extension.

In the container, we define a `ContactExtension`. This extension will prepare
the handler :

@todo

Load it with bundle :

@todo
