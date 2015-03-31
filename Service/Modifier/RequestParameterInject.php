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
 * Basic link modifier, inject parameter from request.
 */
class RequestParameterInject extends ParameterInject
{
    /**
     * {@inheritdoc}
     */
    public function modify($link, $doc, &$params)
    {
        $inject = [];

        foreach ($this->params as $key => $param) {
            if (isset($params[$key])) {
                $inject[$param] = $params[$key];
            }
        }

        return $this->injectParameters($link, $inject);
    }
}
