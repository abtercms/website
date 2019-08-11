<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Loader;

use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ILoader;
use AbterPhp\Framework\Template\ParsedTemplate;
use AbterPhp\Website\Databases\Queries\PageCategoryCache;
use AbterPhp\Website\Domain\Entities\Page;
use AbterPhp\Website\Orm\PageRepo;

class PageCategory implements ILoader
{
    /**
     * @var PageRepo
     */
    protected $pageRepo;

    /**
     * @var PageCategoryCache
     */
    protected $pageCategoryCache;

    /**
     * @var IBuilder[]
     */
    protected $builders;

    /**
     * PageCategoryLoader constructor.
     *
     * @param PageRepo          $pageRepo
     * @param PageCategoryCache $pageCategoryCache
     * @param IBuilder[]        $builders
     */
    public function __construct(PageRepo $pageRepo, PageCategoryCache $pageCategoryCache, array $builders)
    {
        $this->pageRepo          = $pageRepo;
        $this->pageCategoryCache = $pageCategoryCache;
        $this->builders          = $builders;
    }

    /**
     * @param ParsedTemplate[][] $parsedTemplates
     *
     * @return IData[]
     * @throws \Opulence\Orm\OrmException
     */
    public function load(array $parsedTemplates): array
    {
        $identifiers = array_keys($parsedTemplates);

        $pages = $this->pageRepo->getByCategoryIdentifiers($identifiers);

        $groupedPages = $this->groupPages($pages);

        $templateData = $this->createTemplateData($parsedTemplates, $groupedPages);

        return $templateData;
    }

    /**
     * @param ParsedTemplate[][] $parsedTemplates
     * @param Page[][]           $groupedPages
     *
     * @return array
     */
    protected function createTemplateData(array $parsedTemplates, array $groupedPages): array
    {
        $templateData = [];
        foreach ($parsedTemplates as $identifier => $identifierTemplates) {
            foreach ($identifierTemplates as $parsedTemplate) {
                if (!array_key_exists($identifier, $groupedPages)) {
                    continue;
                }

                $pages = $groupedPages[$identifier];

                $builderName = $parsedTemplate->getAttribute('builder');
                if ($builderName && array_key_exists($builderName, $this->builders)) {
                    $templateData[] = $this->builders[$builderName]->build($pages);

                    continue;
                }

                $builder = reset($this->builders);

                $templateData[] = $builder->build($pages);
            }
        }

        return $templateData;
    }

    /**
     * @param array $pages
     *
     * @return Page[][]
     */
    protected function groupPages(array $pages): array
    {
        $groupedPages = [];
        foreach ($pages as $page) {
            $groupedPages[$page->getCategory()->getIdentifier()][] = $page;
        }

        return $groupedPages;
    }

    /**
     * @param string[] $identifiers
     * @param string   $cacheTime
     *
     * @return bool
     * @throws \Opulence\QueryBuilders\InvalidQueryException
     */
    public function hasAnyChangedSince(array $identifiers, string $cacheTime): bool
    {
        return $this->pageCategoryCache->hasAnyChangedSince($identifiers, $cacheTime);
    }
}
