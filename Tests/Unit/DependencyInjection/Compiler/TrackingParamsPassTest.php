<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\Tests\Functional\DependencyInjection\Compiler;

use ONGR\DeepLinkBundle\DependencyInjection\Compiler\TrackingParamsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class TrackingParamsPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Function test.
     */
    public function testProccess()
    {
        $container = new ContainerBuilder();
        $container->setDefinition('ongr_deeplink.deeplink', new Definition());

        $def = new Definition('AnyClass');
        $def->addTag('ongr_deeplink.tracking_params', ['provider' => 'testProvider']);
        $container->setDefinition('ongr_providers.some_abstract_modifier', $def);

        $this->assertFalse(
            $container->getDefinition('ongr_deeplink.deeplink')->hasMethodCall('addTrackingParams')
        );

        $pass = new TrackingParamsPass();
        $pass->process($container);

        $this->assertTrue(
            $container->getDefinition('ongr_deeplink.deeplink')->hasMethodCall('addTrackingParams')
        );
    }
}
