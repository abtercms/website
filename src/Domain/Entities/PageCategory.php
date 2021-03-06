<?php

declare(strict_types=1);

namespace AbterPhp\Website\Domain\Entities;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;

class PageCategory implements IStringerEntity
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $identifier;

    /** @var UserGroup[] */
    protected $userGroups;

    /**
     * PageCategory constructor.
     *
     * @param string      $id
     * @param string      $name
     * @param string      $identifier
     * @param UserGroup[] $userGroups
     */
    public function __construct(string $id, string $name, string $identifier, array $userGroups = [])
    {
        $this->id         = $id;
        $this->name       = $name;
        $this->identifier = $identifier;
        $this->userGroups = $userGroups;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): PageCategory
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     *
     * @return $this
     */
    public function setIdentifier(string $identifier): PageCategory
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return UserGroup[]
     */
    public function getUserGroups(): array
    {
        return $this->userGroups;
    }

    /**
     * @param UserGroup[] $userGroups
     *
     * @return $this
     */
    public function setUserGroups(array $userGroups): PageCategory
    {
        $this->userGroups = $userGroups;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getIdentifier();
    }

    /**
     * @return array|null
     */
    public function toData(): ?array
    {
        $userGroupIds = [];
        foreach ($this->getUserGroups() as $userGroup) {
            $userGroupIds[] = $userGroup->getId();
        }

        return [
            'id'             => $this->getId(),
            'identifier'     => $this->getIdentifier(),
            'name'           => $this->getName(),
            'user_group_ids' => $userGroupIds,
        ];
    }

    /**
     * @return string
     */
    public function toJSON(): string
    {
        return json_encode($this->toData());
    }
}
