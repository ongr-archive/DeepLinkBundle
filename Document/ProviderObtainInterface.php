<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\Document;

/**
 * Extract provider name from document.
 */
interface ProviderObtainInterface
{
    /**
     * @return string
     */
    public function getProviderName();
}
