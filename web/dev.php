<?php

require_once __DIR__.'/../alom/AlomCache.php';

$store = new Symfony\Component\HttpKernel\Cache\Store(__DIR__.'/../alom/cache/esi');

$kernelWithCache = new AlomCache($kernel = new AlomKernel('dev', true), $store);

$kernelWithCache->handle()->send();
