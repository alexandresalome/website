Alom - ContactBundle
====================

Specifications
::::::::::::::

* Basic contact form (author name, email, subject of message and body)
* E-mail sending with listener
* Templating for web pages and e-mails

Customization
::::::::::::::

Customize the message form/model
--------------------------------

Override the frontend controller, and set custom form/model configuration :

.. code-block:: php

    <?php
    class MyMessageController extends MessageController
    {
        public function getForm($message = null)
        {
            return new MyContactForm($message);
        }

        public function getEntity()
        {
            return new MyMessage();
        }
    }

Custom templates
----------------

E-mail / Message
