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
 * Basic link modifier, inject parameter from document.
 */
class DocumentParameterInject extends ParameterInject
{
    /**
     * {@inheritdoc}
     */
    public function modify($link, $doc, &$params)
    {
        $inject = [];

        foreach ($this->params as $key => $param) {
            $getter = sprintf('get%s', ucfirst($key));

            if (isset($doc->$key)) {
                $inject[$param] = $doc->$key;
            } elseif (method_exists($doc, $getter)) {
                $inject[$param] = $doc->$getter();
            }
        }

        return $this->injectParameters($link, $inject);
    }
}
