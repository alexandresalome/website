HTTP Caching
============

There are 2 types of cache strategy : expiration and validation.

The **expiration** relies on the fact that a resource has an age and will expire
in the future. It can be 5 seconds, or 1 day. The cache will serve this response
until the age expires, and request a new response to the application.

The **validation** will request the application with a timestamp and a ETag each
time the page is requested. The application will answer "Not Modified" or send
the new content.

Expiration
----------

HTTP Headers

TTL

Validation
----------

304

The HTTP directives
-------------------

Cache-Control
^^^^^^^^^^^^^
