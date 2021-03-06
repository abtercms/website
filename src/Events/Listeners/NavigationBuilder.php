<?php

declare(strict_types=1);

namespace AbterPhp\Website\Events\Listeners;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Events\NavigationReady;
use AbterPhp\Framework\Html\Component\ButtonFactory;
use AbterPhp\Framework\Navigation\Dropdown;
use AbterPhp\Framework\Navigation\Item;
use AbterPhp\Framework\Navigation\Navigation;
use AbterPhp\Website\Constant\Resource;
use AbterPhp\Website\Constant\Route;

class NavigationBuilder
{
    const BASE_WEIGHT = 400;

    /** @var ButtonFactory */
    protected $buttonFactory;

    /**
     * NavigationRegistrar constructor.
     *
     * @param ButtonFactory $buttonFactory
     */
    public function __construct(ButtonFactory $buttonFactory)
    {
        $this->buttonFactory = $buttonFactory;
    }

    /**
     * @param NavigationReady $event
     */
    public function handle(NavigationReady $event)
    {
        $navigation = $event->getNavigation();

        if (!$navigation->hasIntent(Navigation::INTENT_PRIMARY)) {
            return;
        }

        $dropdown   = new Dropdown();
        $dropdown[] = $this->createPageItem();
        $dropdown[] = $this->createPageCategoryItem();
        $dropdown[] = $this->createPageLayoutItem();
        $dropdown[] = $this->createBlockItem();
        $dropdown[] = $this->createBlockLayoutItem();
        $dropdown[] = $this->createContentListItem();

        $item   = $this->createPageItem();
        $item->setIntent(Item::INTENT_DROPDOWN);
        $item->setAttribute(Html5::ATTR_ID, 'nav-pages');
        $item[0]->setAttribute(Html5::ATTR_HREF, 'javascript:void(0);');
        $item[1] = $dropdown;

        $navigation->addItem($item, static::BASE_WEIGHT);
    }

    /**
     * @return Item
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createPageItem(): Item
    {
        $text = 'website:pages';
        $icon = 'bookmark_border';

        $button   = $this->buttonFactory->createFromName($text, Route::PAGES_LIST, [], $icon);
        $resource = $this->getAdminResource(Resource::PAGES);

        $item = new Item($button);
        $item->setResource($resource);

        return $item;
    }

    /**
     * @return Item
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createPageCategoryItem(): Item
    {
        $text = 'website:pageCategories';
        $icon = 'collections_bookmark';

        $button   = $this->buttonFactory->createFromName($text, Route::PAGE_CATEGORIES_LIST, [], $icon);
        $resource = $this->getAdminResource(Resource::PAGE_CATEGORIES);

        $item = new Item($button);
        $item->setResource($resource);

        return $item;
    }

    /**
     * @return Item
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createPageLayoutItem(): Item
    {
        $text = 'website:pageLayouts';
        $icon = 'view_compact';

        $button   = $this->buttonFactory->createFromName($text, Route::PAGE_LAYOUTS_LIST, [], $icon);
        $resource = $this->getAdminResource(Resource::PAGE_LAYOUTS);

        $item = new Item($button);
        $item->setResource($resource);

        return $item;
    }

    /**
     * @return Item
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createBlockItem(): Item
    {
        $text = 'website:blocks';
        $icon = 'view_module';

        $button   = $this->buttonFactory->createFromName($text, Route::BLOCKS_LIST, [], $icon);
        $resource = $this->getAdminResource(Resource::BLOCKS);

        $item = new Item($button);
        $item->setResource($resource);

        return $item;
    }

    /**
     * @return Item
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createBlockLayoutItem(): Item
    {
        $text = 'website:blockLayouts';
        $icon = 'view_compact';

        $button   = $this->buttonFactory->createFromName($text, Route::BLOCK_LAYOUTS_LIST, [], $icon);
        $resource = $this->getAdminResource(Resource::BLOCK_LAYOUTS);

        $item = new Item($button);
        $item->setResource($resource);

        return $item;
    }

    /**
     * @return Item
     * @throws \Opulence\Routing\Urls\UrlException
     */
    protected function createContentListItem(): Item
    {
        $text = 'website:contentLists';
        $icon = 'format_align_left';

        $button   = $this->buttonFactory->createFromName($text, Route::CONTENT_LISTS_LIST, [], $icon);
        $resource = $this->getAdminResource(Resource::CONTENT_LISTS);

        $item = new Item($button);
        $item->setResource($resource);

        return $item;
    }

    /**
     * @param string $resource
     *
     * @return string
     */
    protected function getAdminResource(string $resource): string
    {
        return sprintf('admin_resource_%s', $resource);
    }
}
