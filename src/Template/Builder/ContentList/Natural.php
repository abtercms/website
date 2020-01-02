<?php

declare(strict_types=1);

namespace AbterPhp\Website\Template\Builder\ContentList;

use AbterPhp\Framework\Template\Data;
use AbterPhp\Framework\Template\IBuilder;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;

class Natural implements IBuilder
{
    use ItemTrait;

    const IDENTIFIER = 'natural';

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return static::IDENTIFIER;
    }

    /**
     * @param Entity $list
     *
     * @return Data
     */
    public function build($list): IData
    {
        $html = $this->buildItems($list, 'div', 'div');

        return new Data(
            $list->getIdentifier(),
            [],
            ['body' => $html]
        );
    }
}
