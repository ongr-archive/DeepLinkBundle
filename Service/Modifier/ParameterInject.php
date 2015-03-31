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
 * Basic link modifier, inject parameter.
 */
class ParameterInject implements LinkModifierInterface
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @param array $params
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Injects parameters into url.
     *
     * @param string $url
     * @param array  $params
     *
     * @return string
     */
    protected function injectParameters($url, $params)
    {
        $link = parse_url($url);

        $query = [];

        if (isset($link['query'])) {
            parse_str($link['query'], $query);
        }

        $query = array_merge($query, $params);

        $str = $link['scheme'] . '://' . $link['host'] . $link['path'];

        if (count($query) > 0) {
            $str .= '?' . http_build_query($query);
        }

        return $str;
    }

    /**
     * {@inheritdoc}
     */
    public function modify($link, $doc, &$params)
    {
        return $this->injectParameters($link, $this->params);
    }
}
