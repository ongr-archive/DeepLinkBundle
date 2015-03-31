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
 * Get link from document.
 */
interface LinkProviderInterface
{
    /**
     * Return link.
     *
     * @param DocumentInterface|ProviderObtainInterface $document
     * @param array                                     $params
     *
     * @return mixed
     */
    public function getLink($document, array $params = []);
}
