<?php

declare(strict_types=1);

namespace AbterPhp\Website\Bootstrappers\Validation;

use AbterPhp\Framework\Constant\Env;
use AbterPhp\Framework\Validation\Rules\AtLeastOne;
use AbterPhp\Framework\Validation\Rules\Uuid;
use AbterPhp\Website\Validation\Factory\Block;
use AbterPhp\Website\Validation\Factory\Page;
use AbterPhp\Website\Validation\Factory\PageLayout;
use InvalidArgumentException;
use Opulence\Framework\Configuration\Config;
use Opulence\Framework\Validation\Bootstrappers\ValidatorBootstrapper as BaseBootstrapper;
use Opulence\Ioc\IContainer;
use Opulence\Validation\Rules\Errors\ErrorTemplateRegistry;
use Opulence\Validation\Rules\RuleExtensionRegistry;

/**
 * Defines the validator bootstrapper
 */
class ValidatorBootstrapper extends BaseBootstrapper
{
    /**
     * @var array
     */
    protected $validatorFactories = [
        Block::class,
        PageLayout::class,
        Page::class,
    ];

    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        $bindings = array_merge(
            parent::getBindings(),
            $this->validatorFactories
        );

        return $bindings;
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        parent::registerBindings($container);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     *
     * Registers the error templates
     *
     * @param ErrorTemplateRegistry $errorTemplateRegistry The registry to register to
     *
     * @throws InvalidArgumentException Thrown if the config was invalid
     */
    protected function registerErrorTemplates(ErrorTemplateRegistry $errorTemplateRegistry)
    {
        $config = require sprintf(
            '%s/%s/validation.php',
            Config::get('paths', 'resources.lang'),
            getenv(Env::DEFAULT_LANGUAGE)
        );

        $errorTemplateRegistry->registerErrorTemplatesFromConfig($config);
    }

    /**
     * Registers any custom rule extensions
     *
     * @param RuleExtensionRegistry $ruleExtensionRegistry The registry to register rules to
     */
    protected function registerRuleExtensions(RuleExtensionRegistry $ruleExtensionRegistry)
    {
        $ruleExtensionRegistry->registerRuleExtension(new AtLeastOne());
        $ruleExtensionRegistry->registerRuleExtension(new Uuid());
    }
}
