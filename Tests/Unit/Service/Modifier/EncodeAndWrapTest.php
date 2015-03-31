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

use ONGR\DeepLinkBundle\Service\Modifier\EncodeAndWrap;
use ONGR\DeepLinkBundle\Tests\Unit\TestDocument;

class EncodeAndWrapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function getTestModifyData()
    {
        $out = [];

        // Case #0.
        $link = 'http://www.example.org/offer/3';
        $format = 'http://www.tracking.com/redirect.php?target=%s';
        $expected = 'http://www.tracking.com/redirect.php?target=http%3A%2F%2Fwww.example.org%2Foffer%2F3';
        $out[] = [$link, $format, $expected];

        // Case #1.
        $link = 'http://www.example.org/offer/3?search=ongr';
        $format = 'http://www.tracking.com/redirect.php?target=%s';
        $expected = 'http://www.tracking.com/redirect.php?target=';
        $expected .= 'http%3A%2F%2Fwww.example.org%2Foffer%2F3%3Fsearch%3Dongr';
        $out[] = [$link, $format, $expected];

        return $out;
    }

    /**
     * Check if modify works as expected.
     *
     * @param string $link
     * @param string $format
     * @param string $expected
     *
     * @dataProvider getTestModifyData()
     */
    public function testModify($link, $format, $expected)
    {
        $service = new EncodeAndWrap($format);

        $params = [];
        $this->assertEquals($expected, $service->modify($link, new TestDocument(), $params));
    }
}
