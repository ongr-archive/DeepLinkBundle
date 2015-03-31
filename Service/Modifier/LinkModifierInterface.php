<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\Service\Modifier;

use ONGR\DeepLinkBundle\Document\ProviderObtainInterface;
use ONGR\ElasticsearchBundle\Document\DocumentInterface;

/**
 * Interface LinkModifierInterface.
 */
interface LinkModifierInterface
{
    /**
     * Modifies link.
     *
     * @param string                                    $link   Basic link.
     * @param DocumentInterface|ProviderObtainInterface $doc    Related document.
     * @param array                                     $params Parameters.
     *
     * @return string
     */
    public function modify($link, $doc, &$params);
}
