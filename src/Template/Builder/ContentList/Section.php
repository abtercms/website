<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Template\Data;
use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ParsedTemplate;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;

class Section implements IBuilder
{
    use ItemTrait;

    const IDENTIFIER = 'section';

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return static::IDENTIFIER;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Entity              $list
     * @param ParsedTemplate|null $template
     *
     * @return Data
     */
    public function build($list, ?ParsedTemplate $template = null): IData
    {
        $html = $this->buildItems($list, Html5::TAG_SECTION, Html5::TAG_DIV);

        return new Data(
            $list->getIdentifier(),
            [],
            ['body' => $html]
        );
    }
}
