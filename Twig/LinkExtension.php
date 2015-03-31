<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\Twig;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Twig extension for getting deep link.
 */
class LinkExtension extends \Twig_Extension
{
    /**
     * Extension name
     */
    const NAME = 'deeplink_extension';

    /**
     * @var Router
     */
    protected $router;

    /**
     * Constructor.
     *
     * @param Router $router
     */
    public function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [new \Twig_SimpleFunction('getDeepLink', [$this, 'getDeepLink'])];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Returns deep link.
     *
     * @param string $id
     * @param array  $params
     *
     * @return string
     */
    public function getDeepLink($id, $params)
    {
        $params['id'] = $id;
        $url = $this->router->generate('deeplink_route', ['params' => base64_encode(serialize($params))]);

        return $url;
    }
}
