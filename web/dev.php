<?php

require_once __DIR__.'/../alom/AlomCache.php';

$kernelWithCache = new AlomCache($kernel = new AlomKernel('dev', true));

$kernelWithCache->handle()->send();
