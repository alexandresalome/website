The best IDE is your IDE
========================

I regularly see the question on user groups "What is (your|the) best IDE ?".

You are editing a variety of languages
--------------------------------------

symfony ? PHP, Yaml, Xml, Markdown, CSS, HTML

.. WARNING::

    Trouver d'autres frameworks

Combine it with your tools
--------------------------

Needs evolve, and when you are facing simple problems, you must be able to answer with a pragmatic and simple solution.

When you use a complete IDE, you are not forced to use it for all actions on project.

You can use external tools : bash scripting, .

A **example** is renaming a class from
``Application\HelloBundle\Controller\DefaultBundle``
to
``Application\MainBundle\Controller\DefaultBundle``.

Solution :

.. code-block:: bash

    $ mv src/Application/HelloBindle src/Application/MainBundle
    $ find . -type f -name \*.php -exec sed -i 's/Application\\HelloBundle/Application\\MainBundle\\' {} \;
