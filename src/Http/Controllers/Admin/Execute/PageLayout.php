<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Execute;

use AbterPhp\Admin\Http\Controllers\Admin\ExecuteAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Service\Execute\PageLayout as RepoService;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class PageLayout extends ExecuteAbstract
{
    const ENTITY_SINGULAR = 'pageLayout';
    const ENTITY_PLURAL   = 'pageLayouts';

    const ENTITY_TITLE_SINGULAR = 'website:pageLayout';
    const ENTITY_TITLE_PLURAL   = 'website:pageLayouts';

    const ROUTING_PATH = 'page-layouts';

    /**
     * PageLayout constructor.
     *
     * @param FlashService    $flashService
     * @param LoggerInterface $logger
     * @param ITranslator     $translator
     * @param UrlGenerator    $urlGenerator
     * @param RepoService     $repoService
     * @param ISession        $session
     */
    public function __construct(
        FlashService $flashService,
        LoggerInterface $logger,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        RepoService $repoService,
        ISession $session
    ) {
        parent::__construct(
            $flashService,
            $logger,
            $translator,
            $urlGenerator,
            $repoService,
            $session
        );
    }
}
