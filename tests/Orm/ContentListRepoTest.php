<?php

declare(strict_types=1);

namespace AbterPhp\Website\Orm;

use AbterPhp\Admin\TestCase\Orm\RepoTestCase;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Orm\DataMappers\ContentListSqlDataMapper as DataMapper;
use AbterPhp\Website\Orm\ContentListRepo as Repo;
use Opulence\Orm\DataMappers\IDataMapper;
use Opulence\Orm\IEntityRegistry;
use PHPUnit\Framework\MockObject\MockObject;

class ContentListRepoTest extends RepoTestCase
{
    /** @var Repo - System Under Test */
    protected $sut;

    /** @var DataMapper|MockObject */
    protected $dataMapperMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new Repo($this->className, $this->dataMapperMock, $this->unitOfWorkMock);
    }

    /**
     * @return DataMapper|MockObject
     */
    protected function createDataMapperMock(): IDataMapper
    {
        /** @var DataMapper|MockObject $mock */
        $mock = $this->createMock(DataMapper::class);

        return $mock;
    }

    public function testGetAll()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', '', '', false, false, false, false, false, false);
        $entityStub1 = new Entity('foo1', 'foo-1', '', '', false, false, false, false, false, false);
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getAll')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getAll();

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByIdFromCache()
    {
        $entityStub = new Entity('foo0', 'foo-0', '', '', false, false, false, false, false, false);

        $entityRegistry = $this->createEntityRegistryStub($entityStub);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->never())->method('getById');

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub, $actualResult);
    }

    public function testGetByIdFromDataMapper()
    {
        $entityStub = new Entity('foo0', 'foo-0', '', '', false, false, false, false, false, false);

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->once())->method('getById')->willReturn($entityStub);

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub, $actualResult);
    }

    public function testAdd()
    {
        $entityStub = new Entity('foo0', 'foo-0', '', '', false, false, false, false, false, false);

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForInsertion')->with($entityStub);

        $this->sut->add($entityStub);
    }

    public function testDelete()
    {
        $entityStub = new Entity('foo0', 'foo-0', '', '', false, false, false, false, false, false);

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForDeletion')->with($entityStub);

        $this->sut->delete($entityStub);
    }

    public function testGetPage()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', '', '', false, false, false, false, false, false);
        $entityStub1 = new Entity('foo1', 'foo-1', '', '', false, false, false, false, false, false);
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getPage')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertSame($entities, $actualResult);
    }

    public function testGetIdentifier()
    {
        $identifier = 'foo-0';

        $entityStub0 = new Entity('foo0', 'foo-0', '', '', false, false, false, false, false, false);

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getByIdentifier')->willReturn($entityStub0);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertSame($entityStub0, $actualResult);
    }

    public function testGetWithLayoutByIdentifiers()
    {
        $identifier0 = 'foo-0';
        $identifier1 = 'foo-1';

        $entityStub0 = new Entity('foo0', $identifier0, '', '', false, false, false, false, false, false);
        $entityStub1 = new Entity('foo1', $identifier1, '', '', false, false, false, false, false, false);
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getByIdentifiers')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getByIdentifiers([$identifier0, $identifier1]);

        $this->assertSame($entities, $actualResult);
    }

    /**
     * @param Entity|null $entity
     *
     * @return MockObject
     */
    protected function createEntityRegistryStub(?Entity $entity): MockObject
    {
        $entityRegistry = $this->createMock(IEntityRegistry::class);
        $entityRegistry->expects($this->any())->method('registerEntity');
        $entityRegistry->expects($this->any())->method('getEntity')->willReturn($entity);

        return $entityRegistry;
    }
}
