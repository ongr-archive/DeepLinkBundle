<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\DeepLinkBundle\Controller;

use ONGR\DeepLinkBundle\Service\LinkService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Main controller.
 */
class DefaultController extends Controller
{
    /**
     * App index page controller.
     *
     * @param Request $request
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        if ($request->get('params') === null) {
            throw $this->createNotFoundException('Path not found');
        }

        $params = unserialize(base64_decode($request->get('params')));
        $documentId = $params['id'];
        unset($params['id']);

        foreach ($params as $key => $param) {
            if (is_array($param)) {
                $params[$key] = serialize($param);
            }
        }

        $locale = $request->getLocale();
        if (isset($locale)) {
            $params['locale'] = $locale;
        }

        $deepLinkService = $this->get('ONGR_deeplink.deeplink');
        $deepLinkUrl = $deepLinkService->getLink($documentId, $params);
        $trackingParams = $deepLinkService->getTrackingParams($documentId, $params);

        $document = $deepLinkService->getDocument($documentId);

        /** @var Response $response */
        $response = $this->render(
            'ONGRDeepLinkBundle::tab.html.twig',
            [
                'id' => $documentId,
                'params' => $params,
                'deep' => $deepLinkUrl,
                'providerId' => $document->provider,
                'document' => $document,
                'trackingParams' => $trackingParams,
            ]
        );

        return $response;
    }

    /**
     * Redirects to a link.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function linkRedirect(Request $request)
    {
        $documentId = $request->get('id');
        $params = $request->get('params');

        /** @var LinkService $deepLinkService */
        $deepLinkService = $this->get('ongr_deeplink.deeplink');
        $deepLinkUrl = $deepLinkService->getLink($documentId, $params);

        return $this->redirect($deepLinkUrl);
    }
}
