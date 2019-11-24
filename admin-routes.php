<?php

declare(strict_types=1);

use AbterPhp\Admin\Config\Routes as RoutesConfig;
use AbterPhp\Admin\Http\Middleware\Authentication;
use AbterPhp\Admin\Http\Middleware\Authorization;
use AbterPhp\Admin\Http\Middleware\LastGridPage;
use AbterPhp\Framework\Authorization\Constant\Role;
use AbterPhp\Website\Constant\Routes as RoutesConstant;
use Opulence\Routing\Router;

/**
 * ----------------------------------------------------------
 * Create all of the routes for the HTTP kernel
 * ----------------------------------------------------------
 *
 * @var Router $router
 */
$router->group(
    ['controllerNamespace' => 'AbterPhp\Website\Http\Controllers'],
    function (Router $router) {
        $router->group(
            [
                'path'       => RoutesConfig::getAdminBasePath(),
                'middleware' => [
                    Authentication::class,
                ],
            ],
            function (Router $router) {
                $entities = [
                    'pages'          => 'Page',
                    'pagelayouts'    => 'PageLayout',
                    'pagecategories' => 'PageCategory',
                    'blocks'         => 'Block',
                    'blocklayouts'   => 'BlockLayout',
                ];

                foreach ($entities as $route => $controllerName) {
                    $path = strtolower($controllerName);

                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Grid\Block::show() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Grid\BlockLayout::show() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Grid\Page::show() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Grid\PageLayout::show() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Grid\PageCategory::show() */
                    $router->get(
                        "/${path}",
                        "Admin\Grid\\${controllerName}@show",
                        [
                            RoutesConstant::OPTION_NAME       => "${route}",
                            RoutesConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::READ,
                                    ]
                                ),
                                LastGridPage::class,
                            ],
                        ]
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\Block::new() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageLayout::new() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\Page::new() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageLayout::new() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageCategory::new() */
                    $router->get(
                        "/${path}/new",
                        "Admin\Form\\${controllerName}@new",
                        [
                            RoutesConstant::OPTION_NAME       => "${route}-new",
                            RoutesConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\Block::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageLayout::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\Page::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageLayout::create() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageCategory::create() */
                    $router->post(
                        "/${path}/new",
                        "Admin\Execute\\${controllerName}@create",
                        [
                            RoutesConstant::OPTION_NAME       => "${route}-create",
                            RoutesConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\Block::edit() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageLayout::edit() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\Page::edit() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageLayout::edit() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Form\PageCategory::edit() */
                    $router->get(
                        "/${path}/:entityId/edit",
                        "Admin\Form\\${controllerName}@edit",
                        [
                            RoutesConstant::OPTION_NAME       => "${route}-edit",
                            RoutesConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\Block::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\BlockLayout::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\Page::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\PageLayout::update() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\PageCategory::update() */
                    $router->put(
                        "/${path}/:entityId/edit",
                        "Admin\Execute\\${controllerName}@update",
                        [
                            RoutesConstant::OPTION_NAME       => "${route}-update",
                            RoutesConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );

                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\Block::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\BlockLayout::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\Page::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\PageLayout::delete() */
                    /** @see \AbterPhp\Website\Http\Controllers\Admin\Execute\PageCategory::delete() */
                    $router->get(
                        "/${path}/:entityId/delete",
                        "Admin\Execute\\${controllerName}@delete",
                        [
                            RoutesConstant::OPTION_NAME       => "${route}-delete",
                            RoutesConstant::OPTION_MIDDLEWARE => [
                                Authorization::withParameters(
                                    [
                                        Authorization::RESOURCE => $route,
                                        Authorization::ROLE     => Role::WRITE,
                                    ]
                                ),
                            ],
                        ]
                    );
                }
            }
        );
    }
);
