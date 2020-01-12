<?php

declare(strict_types=1);

namespace AbterPhp\Website\Service\Execute;

use AbterPhp\Admin\Service\Execute\RepoServiceAbstract;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use AbterPhp\Website\Orm\BlockRepo as GridRepo;
use AbterPhp\Website\Validation\Factory\Block as ValidatorFactory;
use Cocur\Slugify\Slugify;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Http\Requests\UploadedFile;
use Opulence\Orm\IUnitOfWork;

class Block extends RepoServiceAbstract
{
    /** @var Slugify */
    protected $slugify;

    /** @var GridRepo */
    protected $repo;

    /**
     * Block constructor.
     *
     * @param GridRepo         $repo
     * @param ValidatorFactory $validatorFactory
     * @param IUnitOfWork      $unitOfWork
     * @param IEventDispatcher $eventDispatcher
     * @param Slugify          $slugify
     */
    public function __construct(
        GridRepo $repo,
        ValidatorFactory $validatorFactory,
        IUnitOfWork $unitOfWork,
        IEventDispatcher $eventDispatcher,
        Slugify $slugify
    ) {
        parent::__construct($repo, $validatorFactory, $unitOfWork, $eventDispatcher);

        $this->slugify = $slugify;
    }

    /**
     * @param string $entityId
     *
     * @return Entity
     */
    public function createEntity(string $entityId): IStringerEntity
    {
        return new Entity($entityId, '', '', '', '', null);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param IStringerEntity $entity
     * @param array           $postData
     * @param UploadedFile[]  $fileData
     *
     * @return Entity
     */
    protected function fillEntity(IStringerEntity $entity, array $postData, array $fileData): IStringerEntity
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $title = $postData['title'];

        $identifier = $postData['identifier'] ?? $entity->getIdentifier();
        $identifier = $identifier ?: $title;
        $identifier = $this->slugify->slugify($identifier);

        $body  = $postData['body'];

        $layoutId = $postData['layout_id'];
        $layout   = '';
        if (empty($layoutId)) {
            $layoutId = null;
            $layout   = $postData['layout'] ?? '';
        }

        $entity
            ->setIdentifier($identifier)
            ->setTitle($title)
            ->setBody($body)
            ->setLayoutId($layoutId)
            ->setLayout($layout);

        return $entity;
    }
}
