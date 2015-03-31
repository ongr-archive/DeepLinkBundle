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

use ONGR\DeepLinkBundle\Service\Modifier\ParameterInject;
use ONGR\DeepLinkBundle\Tests\Unit\TestDocument;

class ParameterInjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function getTestModifyData()
    {
        $out = [];

        // Case #0.
        $link = 'http://example.com/offer/123';
        $config = ['partner' => 23];
        $expected = 'http://example.com/offer/123?partner=23';
        $out[] = [$link, $config, $expected];

        // Case #1.
        $link = 'http://example.com/offer/123?date=2014-01-01';
        $config = ['partner' => 22];
        $expected = 'http://example.com/offer/123?date=2014-01-01&partner=22';
        $out[] = [$link, $config, $expected];

        // Case #2.
        $link = 'http://example.com/offer/123?date=2014-01-01&search=ongr';
        $config = ['partner' => 22];
        $expected = 'http://example.com/offer/123?date=2014-01-01&search=ongr&partner=22';
        $out[] = [$link, $config, $expected];


        return $out;
    }

    /**
     * Test if modifying works as expected.
     *
     * @param string $link
     * @param array  $config
     * @param string $expected
     *
     * @dataProvider getTestModifyData()
     */
    public function testModify($link, $config, $expected)
    {
        $service = new ParameterInject($config);

        $params = [];
        $this->assertEquals($expected, $service->modify($link, new TestDocument(), $params));
    }
}
