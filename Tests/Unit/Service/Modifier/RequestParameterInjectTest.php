<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\Tests\Functional\Service\Modifier;

use ONGR\DeepLinkBundle\Service\Modifier\RequestParameterInject;
use ONGR\DeepLinkBundle\Tests\Unit\TestDocument;

class RequestParameterInjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function getTestModifyData()
    {
        $out = [];

        // Case #0.
        $link = 'http://example.com/offer/123';
        $config = ['partner' => 'p'];
        $params = ['partner' => 23];
        $expected = 'http://example.com/offer/123?p=23';
        $out[] = [$link, $config, $params, $expected];

        // Case #1.
        $link = 'http://example.com/offer/123';
        $config = ['partner' => 'p', 'super' => 's'];
        $params = ['partner' => 23];
        $expected = 'http://example.com/offer/123?p=23';
        $out[] = [$link, $config, $params, $expected];

        // Case #2.
        $link = 'http://example.com/offer/123?search=ongr';
        $config = ['partner' => 'p', 'super' => 's'];
        $params = ['partner' => 23];
        $expected = 'http://example.com/offer/123?search=ongr&p=23';
        $out[] = [$link, $config, $params, $expected];

        // Case #3.
        $link = 'http://example.com/offer/123?search=ongr';
        $config = ['partner' => 'p', 'super' => 's'];
        $params = ['partner' => 23, 'super' => 'deeplink'];
        $expected = 'http://example.com/offer/123?search=ongr&p=23&s=deeplink';
        $out[] = [$link, $config, $params, $expected];


        return $out;
    }

    /**
     * Check if modifying works as expected.
     *
     * @param string $link
     * @param array  $config
     * @param array  $params
     * @param string $expected
     *
     * @dataProvider getTestModifyData()
     */
    public function testModify($link, $config, $params, $expected)
    {
        $service = new RequestParameterInject($config);

        $this->assertEquals($expected, $service->modify($link, new TestDocument(), $params));
    }
}
