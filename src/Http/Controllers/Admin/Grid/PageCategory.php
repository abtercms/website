<?php

declare(strict_types=1);

namespace AbterPhp\Website\Http\Controllers\Admin\Grid;

use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\Http\Controllers\Admin\GridAbstract;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use AbterPhp\Website\Service\RepoGrid\PageCategory as RepoGrid;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;

class PageCategory extends GridAbstract
{
    const ENTITY_SINGULAR = 'pageCategory';
    const ENTITY_PLURAL   = 'pageCategories';

    const ENTITY_TITLE_PLURAL = 'website:pageCategories';

    /** @var string */
    protected $resource = 'page_categories';

    /**
     * PageCategory constructor.
     *
     * @param FlashService     $flashService
     * @param ITranslator      $translator
     * @param UrlGenerator     $urlGenerator
     * @param AssetManager     $assets
     * @param RepoGrid         $repoGrid
     * @param IEventDispatcher $eventDispatcher
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        AssetManager $assets,
        RepoGrid $repoGrid,
        IEventDispatcher $eventDispatcher
    ) {
        parent::__construct(
            $flashService,
            $translator,
            $urlGenerator,
            $assets,
            $repoGrid,
            $eventDispatcher
        );
    }
}