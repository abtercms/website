<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Orm\UserGroupRepo;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\PageCategory as Entity;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Sessions\ISession;
use Opulence\Sessions\Session;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PageCategoryTest extends TestCase
{
    /** @var PageCategory - System Under Test */
    protected $sut;

    /** @var ISession|MockObject */
    protected $sessionMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    /** @var UserGroupRepo|MockObject */
    protected $userGroupRepoMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->sessionMock = $this->createMock(Session::class);
        $this->sessionMock->expects($this->any())->method('get')->willReturnArgument(0);

        $this->translatorMock = $this->createMock(ITranslator::class);
        $this->translatorMock->expects($this->any())->method('translate')->willReturnArgument(0);

        $this->userGroupRepoMock = $this->createMock(UserGroupRepo::class);

        $this->sut = new PageCategory($this->sessionMock, $this->translatorMock, $this->userGroupRepoMock);
    }

    public function testCreate()
    {
        $action     = 'foo';
        $method     = RequestMethods::POST;
        $showUrl    = 'bar';
        $entityId   = 'c5098ee4-ab53-4d96-9d23-bde122f8f09b';
        $identifier = 'blah';
        $name       = 'mah';
        $allUserGroups = [
            new UserGroup('c6f1db1f-7f6c-408a-b8ba-4ad6ea0b08e1', 'ug-22', 'UG 22', []),
            new UserGroup('a26bee22-4b9e-4db6-be61-e3b3434218b7', 'ug-73', 'UG 73', []),
            new UserGroup('31bc7d78-834d-4cb9-9d94-263ce5e2bfc0', 'ug-112', 'UG 112', []),
            new UserGroup('221fef6e-ebf6-4531-9029-178c024b4bb2', 'ug-432', 'UG 432', []),
        ];
        $userGroups    = [
            new UserGroup('a26bee22-4b9e-4db6-be61-e3b3434218b7', 'ug-73', 'UG 73', []),
            new UserGroup('31bc7d78-834d-4cb9-9d94-263ce5e2bfc0', 'ug-112', 'UG 112', []),
        ];

        $this->userGroupRepoMock->expects($this->any())->method('getAll')->willReturn($allUserGroups);

        /** @var Entity|MockObject $entityMock */
        $entityMock = $this->createMock(Entity::class);

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getIdentifier')->willReturn($identifier);
        $entityMock->expects($this->any())->method('getName')->willReturn($name);
        $entityMock->expects($this->any())->method('getUserGroups')->willReturn($userGroups);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertStringContainsString($action, $form);
        $this->assertStringContainsString($showUrl, $form);
        $this->assertStringContainsString('CSRF', $form);
        $this->assertStringContainsString('POST', $form);
        $this->assertStringContainsString('identifier', $form);
        $this->assertStringContainsString('name', $form);
        $this->assertStringContainsString('button', $form);
    }
}
