<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm\DataMappers;

use AbterPhp\Website\Domain\Entities\Page as Entity;
use Opulence\Orm\DataMappers\IDataMapper;

interface IPageDataMapper extends IDataMapper
{
    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getByIdentifier(string $identifier): ?Entity;

    /**
     * @param string $identifier
     *
     * @return Entity|null
     */
    public function getWithLayout(string $identifier): ?Entity;

    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $filters
     * @param array    $params
     *
     * @return Entity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $filters, array $params): array;

    /**
     * @param array $identifiers
     *
     * @return Entity[]
     */
    public function getByCategoryIdentifiers(array $identifiers): array;
}
