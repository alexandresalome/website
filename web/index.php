<?php

require_once __DIR__.'/../alom/AlomCache.php';

$kernelWithCache = new AlomCache(new AlomKernel('prod', false));

$kernelWithCache->handle()->send();
