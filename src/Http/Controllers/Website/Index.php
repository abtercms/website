<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Website;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Config\EnvReader;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Http\Controllers\ControllerAbstract;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Constant\Env;
use AbterPhp\Website\Constant\Routes;
use AbterPhp\Website\Domain\Entities\Page\Assets;
use AbterPhp\Website\Domain\Entities\Page\Meta;
use AbterPhp\Website\Domain\Entities\PageLayout\Assets as LayoutAssets;
use AbterPhp\Website\Service\Website\Index as IndexService;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class Index extends ControllerAbstract
{
    /** @var ISession */
    protected $indexService;

    /** @var IndexService */
    protected $session;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var AssetManager */
    protected $assetManager;

    /** @var string */
    protected $baseUrl;

    /** @var string */
    protected $siteTitle;

    /**
     * Index constructor.
     *
     * @param FlashService $flashService
     * @param ISession     $session
     * @param IndexService $indexService
     * @param UrlGenerator $urlGenerator
     * @param AssetManager $assetManager
     * @param EnvReader    $envReader
     */
    public function __construct(
        FlashService $flashService,
        ISession $session,
        IndexService $indexService,
        UrlGenerator $urlGenerator,
        AssetManager $assetManager,
        EnvReader $envReader
    ) {
        $this->session      = $session;
        $this->indexService = $indexService;
        $this->urlGenerator = $urlGenerator;
        $this->assetManager = $assetManager;

        $this->baseUrl   = $envReader->get(Env::WEBSITE_BASE_URL);
        $this->siteTitle = $envReader->get(Env::WEBSITE_SITE_TITLE);

        parent::__construct($flashService);
    }

    /**
     * Shows the homepage
     *
     * @return Response The response
     */
    public function index(): Response
    {
        return $this->fallback('index');
    }

    /**
     * Shows the homepage
     *
     * @return Response The response
     */
    public function fallback(string $identifier): Response
    {
        $this->view = $this->viewFactory->createView('contents/frontend/page');

        $page = $this->indexService->getRenderedPage($identifier, $this->getUserGroupIdentifiers());
        if (null === $page) {
            return $this->notFound();
        }

        $pageUrl     = $this->urlGenerator->createFromName(Routes::ROUTE_FALLBACK, $identifier);
        $homepageUrl = $this->urlGenerator->createFromName(Routes::ROUTE_INDEX);

        $this->view->setVar('body', $page->getRenderedBody());
        $this->view->setVar('siteTitle', $this->siteTitle);
        $this->view->setVar('pageUrl', $pageUrl);
        $this->view->setVar('homepageUrl', $homepageUrl);

        $this->setMetaVars($page->getMeta());
        $this->setAssetsVars($page->getAssets());

        return $this->createResponse($page->getTitle());
    }

    /**
     * @return string[]
     */
    protected function getUserGroupIdentifiers(): array
    {
        $username = $this->session->get(Session::USERNAME, '');
        if (!$username) {
            return [];
        }

        $userGroupIdentifiers = $this->indexService->getUserGroupIdentifiers($username);

        return $userGroupIdentifiers;
    }

    /**
     * @param Meta $meta
     */
    protected function setMetaVars(Meta $meta)
    {
        $this->view->setVar('metaDescription', $meta->getDescription());
        $this->view->setVar('metaKeywords', explode(',', $meta->getKeywords()));
        $this->view->setVar('metaCopyright', $meta->getCopyright());
        $this->view->setVar('metaAuthor', $meta->getAuthor());
        $this->view->setVar('metaRobots', $meta->getRobots());
        $this->view->setVar('metaOGDescription', $meta->getOGDescription());
        $this->view->setVar('metaOGTitle', $meta->getOGTitle());
        $this->view->setVar('metaOGImage', $meta->getOGImage());
    }

    /**
     * @param Assets|null $assets
     */
    protected function setAssetsVars(?Assets $assets)
    {
        if ($assets === null) {
            return;
        }

        $origHeader = $this->view->hasVar('header') ? (string)$this->view->getVar('header') : '';
        $origFooter = $this->view->hasVar('footer') ? (string)$this->view->getVar('footer') : '';

        $this->view->setVar('header', $origHeader . $assets->getHeader());
        $this->view->setVar('footer', $origFooter . $assets->getFooter());
        $this->view->setVar('page', $assets->getKey());

        $key = $assets->getKey();
        foreach ($assets->getJsFiles() as $jsFile) {
            $this->assetManager->addJs($key, $jsFile);
        }
        foreach ($assets->getCssFiles() as $cssFile) {
            $this->assetManager->addCss($key, $cssFile);
        }

        $this->setLayoutAssetsVars($assets->getLayoutAssets());
    }

    /**
     * @param LayoutAssets|null $assets
     */
    protected function setLayoutAssetsVars(?LayoutAssets $assets)
    {
        if ($assets === null) {
            return;
        }

        $origHeader = $this->view->hasVar('header') ? (string)$this->view->getVar('header') : '';
        $origFooter = $this->view->hasVar('footer') ? (string)$this->view->getVar('footer') : '';

        $this->view->setVar('header', $origHeader . $assets->getHeader());
        $this->view->setVar('footer', $origFooter . $assets->getFooter());
        $this->view->setVar('layout', $assets->getKey());

        $key = $assets->getKey();
        foreach ($assets->getJsFiles() as $jsFile) {
            $this->assetManager->addJs($key, $jsFile);
        }
        foreach ($assets->getCssFiles() as $cssFile) {
            $this->assetManager->addCss($key, $cssFile);
        }
    }

    /**
     * @param string $route
     *
     * @return string
     * @throws \Opulence\Routing\Urls\URLException
     */
    protected function getCanonicalUrl(string $route): string
    {
        $path = $this->urlGenerator->createFromName($route);

        return $this->baseUrl . ltrim($path, '/');
    }

    /**
     * @return string
     */
    protected function getBaseUrl(): string
    {
        if ($this->baseUrl === null) {
            $this->baseUrl = sprintf(
                '%s://%s/',
                $this->request->getServer()->get('REQUEST_SCHEME'),
                $this->request->getServer()->get('SERVER_NAME')
            );
        }

        return $this->baseUrl;
    }

    /**
     * 404 page
     *
     * @return Response The response
     */
    protected function notFound(): Response
    {
        $this->view = $this->viewFactory->createView('contents/frontend/404');

        $response = $this->createResponse('404 Page not Found');
        $response->setStatusCode(ResponseHeaders::HTTP_NOT_FOUND);

        return $response;
    }
}
