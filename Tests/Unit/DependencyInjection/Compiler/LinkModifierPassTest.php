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

use ONGR\DeepLinkBundle\DependencyInjection\Compiler\LinkModifierPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class LinkModifierPassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Function test.
     */
    public function testProcess()
    {
        $container = new ContainerBuilder();
        $container->setDefinition('ongr_deeplink.deeplink', new Definition());

        $def = new Definition('AnyClass');
        $def->addTag('ongr_deeplink.deeplink_modifier', ['provider' => 'testProvider']);
        $container->setDefinition('ongr_providers.some_abstract_modifier', $def);

        $this->assertFalse(
            $container->getDefinition('ongr_deeplink.deeplink')->hasMethodCall('addModifier')
        );

        $pass = new LinkModifierPass();
        $pass->process($container);

        $this->assertTrue(
            $container->getDefinition('ongr_deeplink.deeplink')->hasMethodCall('addModifier')
        );
    }
}
