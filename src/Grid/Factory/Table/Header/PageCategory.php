<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Factory\Table\Header;

use AbterPhp\Admin\Grid\Factory\Table\HeaderFactory;

class PageCategory extends HeaderFactory
{
    public const GROUP_NAME       = 'pageCategory-name';
    public const GROUP_IDENTIFIER = 'pageCategory-identifier';

    private const HEADER_NAME       = 'website:pageCategoryName';
    private const HEADER_IDENTIFIER = 'website:pageCategoryIdentifier';

    /** @var array */
    protected $headers = [
        self::GROUP_NAME       => self::HEADER_NAME,
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_NAME       => 'name',
        self::GROUP_IDENTIFIER => 'identifier',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_NAME       => 'page_layouts.name',
        self::GROUP_IDENTIFIER => 'page_layouts.identifier',
    ];
}
