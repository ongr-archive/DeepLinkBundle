<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle;

use ONGR\DeepLinkBundle\DependencyInjection\Compiler\DataProviderPass;
use ONGR\DeepLinkBundle\DependencyInjection\Compiler\LinkModifierPass;
use ONGR\DeepLinkBundle\DependencyInjection\Compiler\TrackingParamsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Bundle class.
 */
class ONGRDeepLinkBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DataProviderPass());
        $container->addCompilerPass(new LinkModifierPass());
        $container->addCompilerPass(new TrackingParamsPass());
    }
}
