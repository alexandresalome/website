Symfony2 - Performance and measures
===================================

.. WARNING::
   This article is being written in August 2010. Symfony2 is very unstable and things will change.

   This benchmark is used using Symfony2 PR3, in development state. Do not
   consider this results for other versions.

Web application becomes more and more complex. Developers must care about
number of SQL queries, execution time and keep the application fluid.

Functional description
----------------------

I made this benchmarks using my playground for Symfony2 tests : CodeGuard.

The test is composed of 7 checkpoints :

First, **Nothing**.

Next, we must **instanciate a kernel**. Once the kernel loaded, it is
ready-to-be-used and autoloading is ready. It's a good checkpoint for your
application workflow.

The next step is **minimum controller**. This checkpoint is useful because it's
the cost for the request to be handled to the controller.

.. code-block:: html+php

    <?php
    class DefaultController extends Controller
    {
        public function showAction($slug)
        {
            return new Response();
        }
    }

In the controller, we retrieve a Project object. This phase is splitted in two
parts : **getting ORM** and **getting an entity**.

Fetching of objects has a cost. Querying a count over a table has a low cost.
So we need to know the difference between initialization of ORM and hydration
of objects.

At last, we **set content and send** it to browser.


The test environment
--------------------

Optimize server
^^^^^^^^^^^^^^^
First, we must improve the performances : **disable XDebug**.

Next step is to install **APC** and configure it in Symfony2. Doctrine uses
a cache for metatag, queries and results. So we configure it in project :

.. code-block:: yaml

   doctrine.orm:
     metadata_cache_driver: apc
     query_cache_driver:    apc
     result_cache_driver:   apc


The benchmark script
^^^^^^^^^^^^^^^^^^^^

The benchmark framework **iterates 100 times** to get a medium value.

It is composed of 2 parts : a bash script that will hit the web application,
and a front controller on web server that returns memory/time measures.

The benchmark script


.. code-block:: html+php

    <?php
    $uri = '/codeguard/browse/README';

    $memorySum = 0;
    $timeSum   = 0;

    for ($i=0; $i<100; $i++)
    {
      $result = file_get_contents("http://codeguard$uri");
      list($memory, $time) = explode("\t", $result);

      $memorySum += $memory;
      $timeSum   += $time;

      echo sprintf("-  %15.10f - %15.10f\n", $memory, $time);
    }

    echo sprintf("\n>> %15.10f - %15.10f\n", $memorySum / 100, $timeSum / 100);

The front controller

.. code-block:: html+php

    <?php
    $memory = memory_get_usage();
    $time   = microtime(true);

    // code to bench

    $memory = memory_get_usage() - $memory;
    $time   = microtime(true) - $time;

    $memory /= 1024 * 1024;

    echo "$memory\t$time";exit;

It's a very basic benchmark script, but it is correct.

The measures
------------

 +-------------------------+--------------+--------------+
 |                         | Memory (Mbs) | Time (sec)   |
 +=========================+==============+==============+
 | Nothing                 | 0.000274582  | 0.0000011301 |
 +-------------------------+--------------+--------------+
 | Instanciation of kernel | 0.0923004150 | 0.0004278421 |
 +-------------------------+--------------+--------------+
 | Mini-controller         | 0.5537170410 | 0.0031502247 |
 +-------------------------+--------------+--------------+
 | Get the ORM             | 1.1144027710 | 0.0049045515 |
 +-------------------------+--------------+--------------+
 | Get a project           | 1.4640884399 | 0.0072014427 |
 +-------------------------+--------------+--------------+
 | Controller Response     | 1.4648971558 | 0.0091884613 |
 +-------------------------+--------------+--------------+

This measures shows 3 levels of load-charge in this scenario :

* The controller
* The ORM initialization
* The object hydration

When developing an application, you must keep in mind the load-charge of your
application. Even if Symfony2 is a light framework, you must keep improving
performances by mastering different routes/requests and time/memory execution
of each of them.
