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

/**
 * Basic link modifier, encode parameters.
 */
class EncodeAndWrap implements LinkModifierInterface
{
    /**
     * @var string
     */
    protected $format;

    /**
     * @param string $format
     */
    public function __construct($format)
    {
        $this->format = $format;
    }

    /**
     * Encodes link.
     *
     * @param string $str
     *
     * @return string
     */
    protected function encode($str)
    {
        return urlencode($str);
    }

    /**
     * {@inheritdoc}
     */
    public function modify($link, $doc, &$params)
    {
        return sprintf($this->format, $this->encode($link));
    }
}
