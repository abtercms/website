<?php

declare(strict_types=1);

namespace AbterPhp\Website\Databases\Migrations;

use AbterPhp\Framework\Databases\Migrations\BaseMigration;
use DateTime;

class Init extends BaseMigration
{
    const FILENAME = 'website.sql';

    /**
     * Gets the creation date, which is used for ordering
     *
     * @return DateTime The date this migration was created
     */
    public static function getCreationDate() : DateTime
    {
        return DateTime::createFromFormat(DateTime::ATOM, '2019-02-28T21:01:00+00:00');
    }
}
