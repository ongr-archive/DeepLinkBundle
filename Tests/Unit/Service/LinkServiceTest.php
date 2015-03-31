<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\Tests\Unit\Service;

use ONGR\DeepLinkBundle\Document\ProviderObtainInterface;
use ONGR\DeepLinkBundle\Service\LinkProviderInterface;
use ONGR\DeepLinkBundle\Service\LinkService;
use ONGR\DeepLinkBundle\Service\Modifier\LinkModifierInterface;
use ONGR\DeepLinkBundle\Service\TrackingParamsInterface;
use ONGR\DeepLinkBundle\Tests\Unit\TestDocument;
use ONGR\ElasticsearchBundle\Document\DocumentInterface;
use ONGR\ElasticsearchBundle\ORM\Repository;

class LinkServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LinkService
     */
    private $linkService;

    /**
     * @var LinkProviderInterface
     */
    private $provider;

    /**
     * @var ProviderObtainInterface
     */
    private $document;

    /**
     * @var string
     */
    private $providerName = 'testProvider';

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->document = new TestDocument();
        $this->document->setProviderName('testProvider');
        $this->document->setId(3312);

        $this->provider = $this->getMockBuilder('ONGR\DeepLinkBundle\Service\LinkProviderInterface')
            ->setMethods(['getLink'])
            ->getMock();
        $this->provider->expects($this->any())->method('getLink')->will($this->returnValue('http://google.com'));

        $this->linkService = new LinkService($this->getRepository($this->document));
    }

    /**
     * Link getter test.
     */
    public function testGetLink()
    {
        $this->linkService->setProvider($this->providerName, $this->provider);
        $link = $this->linkService->getLink(55, []);
        $expected = 'http://google.com';

        $this->assertEquals($expected, $link);
    }

    /**
     * Check if cache works.
     */
    public function testGetDocumentLocalCache()
    {
        $repository = $this->getRepository();
        $repository->expects($this->once())->method('find')->will($this->returnValue($this->document));

        $linkService = new LinkService($repository);
        $linkService->getDocument(55);
        $linkService->getDocument(55);
    }

    /**
     * Check what happens when there is no providers.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testWhenThereIsNoProviders()
    {
        $link = $this->linkService->getLink(55, []);
        $expected = 'http://google.com';

        $this->assertEquals($expected, $link);
    }

    /**
     * Check what happens when document does not exist.
     *
     * @expectedException \InvalidArgumentException
     */
    public function testIfDocumentDoesNotExist()
    {
        $repository = $this->getRepository();

        /** @var LinkProviderInterface | \PHPUnit_Framework_MockObject_MockObject $provider */
        $provider = $this->getMock('ONGR\DeepLinkBundle\Service\LinkProviderInterface');
        $provider->expects($this->any())->method('getLink')->will($this->returnValue('http://google.com'));

        $linkService = new LinkService($repository);
        $linkService->setProvider($this->providerName, $provider);
        $linkService->getLink(5, []);
    }

    /**
     * Check what happens when document doesn't implement interface.
     *
     * @expectedException \LogicException
     */
    public function testIfDocumentImplementsInterface()
    {
        $repository = $this->getRepository();
        $linkService = new LinkService($repository);
        $linkService->getLink(5, []);
    }

    /**
     * Test if link modifiers works.
     */
    public function testLinkModifiers()
    {
        $params = ['search' => 'ongr'];

        /** @var LinkModifierInterface | \PHPUnit_Framework_MockObject_MockObject $modifier */
        $modifier = $this->getMock('ONGR\DeepLinkBundle\Service\Modifier\LinkModifierInterface', ['modify']);
        $modifier->expects($this->once())->method('modify')
            ->with('http://not.modified.com', $this->document, $params)
            ->will($this->returnValue('http://modified.com'));

        /** @var LinkModifierInterface | \PHPUnit_Framework_MockObject_MockObject $falseModifier */
        $falseModifier = $this->getMock('ONGR\DeepLinkBundle\Service\Modifier\LinkModifierInterface', ['modify']);
        $falseModifier->expects($this->never())->method('modify');

        /** @var LinkProviderInterface | \PHPUnit_Framework_MockObject_MockObject $provider */
        $provider = $this->getMock('ONGR\DeepLinkBundle\Core\LinkProviderInterface', ['getLink']);
        $provider->expects($this->any())->method('getLink')->will($this->returnValue('http://not.modified.com'));

        $service = new LinkService($this->getRepository($this->document));

        $service->setProvider($this->providerName, $provider);
        $service->addModifier($this->providerName, $modifier);
        $service->addModifier('testProvider2', $falseModifier);

        $this->assertEquals('http://modified.com', $service->getLink('3312', $params));
    }

    /**
     * Test tracking params.
     */
    public function testGetTrackingParams()
    {
        $params = ['search' => 'ongr'];
        $trackParams = [];
        /** @var TrackingParamsInterface | \PHPUnit_Framework_MockObject_MockObject $trackingParams */
        $trackingParams = $this->getMockBuilder('ONGR\DeepLinkBundle\Service\TrackingParamsInterface')
            ->setMethods(['getTrackingParams'])->getMock();
        $trackingParams->expects($this->once())->method('getTrackingParams')
            ->with($this->document, $trackParams, $params)
            ->will(
                $this->returnCallback(
                    function ($doc, $trackParams, $params) {
                        $trackParams['param12'] = 49;

                        return $trackParams;
                    }
                )
            );

        $this->linkService->addTrackingParams($this->providerName, $trackingParams);
        $actual = $this->linkService->getTrackingParams(3312, $params);

        $expected = $trackParams;
        $expected['param12'] = 49;

        $this->assertEquals($expected, $actual);
    }

    /**
     * Return a mock.
     *
     * @param DocumentInterface $document
     *
     * @return Repository|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRepository($document = null)
    {
        $mock = $this->getMockBuilder('ONGR\ElasticsearchBundle\ORM\Repository')
            ->disableOriginalConstructor()
            ->getMock();

        if ($document !== null) {
            $mock->expects($this->any())->method('find')->will($this->returnValue($document));
        }

        return $mock;
    }
}
