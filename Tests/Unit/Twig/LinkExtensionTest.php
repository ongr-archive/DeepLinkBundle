<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\Tests\Unit\Twig;

use ONGR\DeepLinkBundle\Twig\LinkExtension;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class LinkExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Router
     */
    private $router;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->router = $this->getMock(
            'Symfony\Bundle\FrameworkBundle\Routing\Router',
            ['generate'],
            [],
            '',
            false
        );
        $this->router->expects($this->any())->method('generate')->will($this->returnValue('/demo'));
    }

    /**
     * Test if extension has filters set.
     */
    public function testHasFunctions()
    {
        $extension = new LinkExtension($this->router);

        $this->assertNotEmpty($extension->getFunctions());
    }

    /**
     * Name getter test.
     */
    public function testGetName()
    {
        $extension = new LinkExtension($this->router);

        $this->assertEquals('deeplink_extension', $extension->getName());
    }

    /**
     * Link getter test.
     */
    public function testGetLink()
    {
        $extension = new LinkExtension($this->router);

        $this->assertEquals('/demo', $extension->getDeepLink('5', []));
    }
}
