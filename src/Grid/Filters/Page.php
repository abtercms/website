<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Filters;

use AbterPhp\Framework\Grid\Component\Filters;
use AbterPhp\Framework\Grid\Filter\ExactFilter;
use AbterPhp\Framework\Grid\Filter\LikeFilter;

class Page extends Filters
{
    /**
     * Page constructor.
     *
     * @param string[]    $intents
     * @param array       $attributes
     * @param string|null $tag
     */
    public function __construct(array $intents = [], array $attributes = [], ?string $tag = null)
    {
        parent::__construct($intents, $attributes, $tag);

        $this->nodes[] = new ExactFilter('identifier', 'website:pageIdentifier');

        $this->nodes[] = new LikeFilter('title', 'website:pageTitle');

        $this->nodes[] = new LikeFilter('lede', 'website:pageLede');

        $this->nodes[] = new LikeFilter('body', 'website:pageBody');
    }
}
