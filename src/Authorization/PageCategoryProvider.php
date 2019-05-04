<?php

declare(strict_types=1);

namespace AbterPhp\Website\Authorization;

use AbterPhp\Admin\Authorization\PolicyProviderTrait;
use AbterPhp\Website\Databases\Queries\PageCategoryAuthLoader as AuthLoader;
use Casbin\Exceptions\CasbinException;
use Casbin\Model\Model;
use Casbin\Persist\Adapter as CasbinAdapter;

class PageCategoryProvider implements CasbinAdapter
{
    use PolicyProviderTrait;

    const PREFIX = 'page_category';

    /** @var AuthLoader */
    protected $authLoader;

    /**
     * PageCategoryProvider constructor.
     *
     * @param AuthLoader $authLoader
     */
    public function __construct(AuthLoader $authLoader)
    {
        $this->authLoader = $authLoader;
        $this->prefix     = static::PREFIX;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Model $model
     *
     * @return bool
     */
    public function savePolicy($model)
    {
        return true;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $sec
     * @param string $ptype
     * @param array  $rule
     *
     * @return void
     */
    public function addPolicy($sec, $ptype, $rule)
    {
        return;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $sec
     * @param string $ptype
     * @param array  $rule
     *
     * @return int
     */
    public function removePolicy($sec, $ptype, $rule)
    {
        $count = 0;

        return $count;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param       $sec
     * @param       $ptype
     * @param       $fieldIndex
     * @param mixed ...$fieldValues
     *
     * @throws CasbinException
     */
    public function removeFilteredPolicy($sec, $ptype, $fieldIndex, ...$fieldValues)
    {
        throw new CasbinException('not implemented');
    }
}
