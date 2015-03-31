<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Collects tracking parameters.
 */
class TrackingParamsPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $converterService = $container->getDefinition('ongr_deeplink.deeplink');

        $taggedDefinitions = $container->findTaggedServiceIds('ongr_deeplink.tracking_params');

        foreach ($taggedDefinitions as $id => $tag) {
            $tag = current($tag);
            $converterService->addMethodCall(
                'addTrackingParams',
                [
                    $tag['provider'],
                    new Reference($id),
                ]
            );
        }
    }
}
