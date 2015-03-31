<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\Tests\Functional\DependencyInjection;

use ONGR\DeepLinkBundle\DependencyInjection\ONGRDeepLinkExtension;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ONGRDeepLinkExtensionTest extends WebTestCase
{
    /**
     * Test if configuration is loaded.
     */
    public function testLoadInjectionConfig()
    {
        $container = self::createClient()->getContainer();

        $this->assertNotNull($container->get('ongr_deeplink.twig.deeplink'));
    }
}
