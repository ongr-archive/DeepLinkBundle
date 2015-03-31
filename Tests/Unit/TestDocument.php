<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\Tests\Unit;

use ONGR\DeepLinkBundle\Document\ProviderObtainInterface;
use ONGR\ElasticsearchBundle\Document\AbstractDocument;
use ONGR\ElasticsearchBundle\Document\DocumentInterface;

/**
 * Document for testing.
 */
class TestDocument extends AbstractDocument implements DocumentInterface, ProviderObtainInterface
{
    /**
     * @var string
     */
    protected $provider;

    /**
     * Sets provider name.
     *
     * @param string $name
     */
    public function setProviderName($name)
    {
        $this->provider = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderName()
    {
        return $this->provider;
    }
}
