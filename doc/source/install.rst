Installation
============

Requirements
::::::::::::

* PHP 5.3.2
* PHP-PDO SQLite
* PHPUnit 3.5 *(optional, for testing)*
* Ant *(optional, for building)*


Initialization
::::::::::::::

Alom uses **Ant for automated build**.

Default task will update project (recursive submodule), copy distributed files,
build the project and launch tests.

.. code-block:: bash

    #!/bin/bash

    ant

    # is equivalent to

    ant update
    ant copy-dist
    ant build
    ant test

Another task ``clean`` is available for cleaning project.
