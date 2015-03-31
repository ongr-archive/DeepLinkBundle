<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\Service;

use ONGR\DeepLinkBundle\Document\ProviderObtainInterface;
use ONGR\DeepLinkBundle\Service\Modifier\LinkModifierInterface;
use ONGR\ElasticsearchBundle\Document\DocumentInterface;
use ONGR\ElasticsearchBundle\ORM\Repository;

/**
 * Service for handling deep links.
 */
class LinkService
{
    /**
     * @var LinkProviderInterface[]
     */
    protected $providers = [];

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var DocumentInterface[]
     */
    protected $document;

    /**
     * @var array
     */
    protected $modifiers = [];

    /**
     * @var array
     */
    protected $trackingParams;

    /**
     * Constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string                $provider
     * @param LinkProviderInterface $service
     */
    public function setProvider($provider, $service)
    {
        $this->providers[$provider] = $service;
    }

    /**
     * Adds modifier.
     *
     * @param string                $provider
     * @param LinkModifierInterface $modifier
     */
    public function addModifier($provider, $modifier)
    {
        if (!isset($this->modifiers[$provider])) {
            $this->modifiers[$provider] = [];
        }

        $this->modifiers[$provider][] = $modifier;
    }

    /**
     * Adds tracking parameters.
     *
     * @param string                  $provider
     * @param TrackingParamsInterface $callback
     */
    public function addTrackingParams($provider, $callback)
    {
        if (!isset($this->trackingParams[$provider])) {
            $this->trackingParams[$provider] = [];
        }

        $this->trackingParams[$provider][] = $callback;
    }

    /**
     * Returns link.
     *
     * @param string $documentId
     * @param array  $params
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function getLink($documentId, $params)
    {
        return $this->getDocumentLink($this->getDocument($documentId), $params);
    }

    /**
     * Returns document link.
     *
     * @param ProviderObtainInterface $document
     * @param array                   $params
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function getDocumentLink($document, $params)
    {
        $providerName = $document->getProviderName();

        if (!isset($this->providers[$providerName])) {
            throw new \InvalidArgumentException('Provider "' . $providerName . '" does not exist');
        }

        $provider = $this->providers[$providerName];

        $link = $provider->getLink($document, $params);

        if (isset($this->modifiers[$providerName])) {
            /** @var LinkModifierInterface $modifier */
            foreach ($this->modifiers[$providerName] as $modifier) {
                $link = $modifier->modify($link, $document, $params);
            }
        }

        return $link;
    }

    /**
     * Returns tracking parameters.
     *
     * @param string $documentId
     * @param array  $params
     *
     * @return array
     */
    public function getTrackingParams($documentId, $params)
    {
        return $this->getDocumentTrackingParams($this->getDocument($documentId), $params);
    }

    /**
     * Returns document tracking params.
     *
     * @param ProviderObtainInterface $document
     * @param array                   $params
     *
     * @return array
     */
    public function getDocumentTrackingParams($document, $params)
    {
        $providerName = $document->getProviderName();

        $trackingParams = [];

        if (isset($this->trackingParams[$providerName])) {
            /** @var TrackingParamsInterface $modifier */
            foreach ($this->trackingParams[$providerName] as $modifier) {
                /** @var TrackingParamsInterface $modifier */
                $trackingParams = $modifier->getTrackingParams($document, $trackingParams, $params);
            }
        }

        return $trackingParams;
    }

    /**
     * Returns document.
     *
     * @param string $documentId
     *
     * @throws \LogicException
     * @throws \InvalidArgumentException
     *
     * @return DocumentInterface | ProviderObtainInterface
     */
    public function getDocument($documentId)
    {
        if (isset($this->document[$documentId])) {
            return $this->document[$documentId];
        }

        $document = $this->repository->find($documentId);

        if (!$document) {
            throw new \InvalidArgumentException('Document by id: ' . $documentId . ' not found.');
        }

        if (!$document instanceof ProviderObtainInterface) {
            throw new \LogicException(
                'Document "'
                . get_class($document)
                . '" must implement "ONGR\DeepLinkBundle\Model\ProviderObtainInterface".'
            );
        }

        $this->document[$documentId] = $document;

        return $this->document[$documentId];
    }
}
