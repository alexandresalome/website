<?php
/**
 * This file is part of the Alom project.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * This source file is subject to the GPL license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Alom\Test;

use Symfony\Framework\Test\WebTestCase as BaseWebTestCase;

/**
 * Base test case for application.
 *
 * @author     Alexandre Salomé <alexandre.salome@gmail.com>
 */
class WebTestCase extends BaseWebTestCase
{
  /**
   * @inherited
   */
  protected function createKernel(array $options = array()) {
    require_once(__DIR__.'/../../../alom/AlomKernel.php');

    return new \AlomKernel(
      isset($options['environment']) ? $options['environment'] : 'test',
      isset($options['debug']) ? $debug : true
    );
  }
}
