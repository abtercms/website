<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use Opulence\Validation\Factories\ValidatorFactory;
use Opulence\Validation\IValidator;

class ContentList extends ValidatorFactory
{
    /**
     * @return IValidator
     */
    public function createValidator(): IValidator
    {
        $validator = parent::createValidator();

        $validator
            ->field('id')
            ->forbidden();

        $validator
            ->field('identifier');

        $validator
            ->field('name')
            ->required();

        $validator
            ->field('classes');

        $validator
            ->field('protected')
            ->min(1)
            ->max(1);

        $validator
            ->field('with_links')
            ->min(1)
            ->max(1);

        $validator
            ->field('with_html')
            ->min(1)
            ->max(1);

        $validator
            ->field('with_label_links')
            ->min(1)
            ->max(1);

        $validator
            ->field('with_images')
            ->min(1)
            ->max(1);

        $validator
            ->field('with_classes')
            ->min(1)
            ->max(1);

        return $validator;
    }
}
