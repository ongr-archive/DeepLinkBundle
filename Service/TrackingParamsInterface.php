<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\Service;

use ONGR\DeepLinkBundle\Document\ProviderObtainInterface;
use ONGR\ElasticsearchBundle\Document\DocumentInterface;

/**
 * Extract tracking parameters from document.
 */
interface TrackingParamsInterface
{
    /**
     * Return tracking parameters.
     *
     * @param DocumentInterface|ProviderObtainInterface $document
     * @param array                                     $trackingParams
     * @param array                                     $params
     *
     * @return array
     */
    public function getTrackingParams($document, $trackingParams, $params);
}
