<?php

declare(strict_types=1);

namespace AbterPhp\Website\Databases\Queries;

use AbterPhp\Admin\Exception\Database;
use AbterPhp\Framework\TestCase\Database\QueryTestCase;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;

class BlockCacheTest extends QueryTestCase
{
    /** @var BlockCache - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new BlockCache($this->connectionPoolMock);
    }

    public function testHasAnyChangedSinceReturnsFalseIfNothingHasChanged()
    {
        $identifiers = ['foo', 'bar'];
        $cacheTime   = 'baz';

        $sql0         = 'SELECT COUNT(*) AS count FROM blocks LEFT JOIN block_layouts AS block_layouts ON block_layouts.id = blocks.layout_id WHERE (blocks.deleted_at IS NULL) AND (blocks.identifier IN (?,?)) AND (blocks.updated_at > ? OR block_layouts.updated_at > ?)'; // phpcs:ignore
        $valuesToBind = [
            [$identifiers[0], \PDO::PARAM_STR],
            [$identifiers[1], \PDO::PARAM_STR],
            [$cacheTime, \PDO::PARAM_STR],
            [$cacheTime, \PDO::PARAM_STR],
        ];
        $returnValue  = '0';
        $statement0   = MockStatementFactory::createReadColumnStatement($this, $valuesToBind, $returnValue);

        $this->readConnectionMock
            ->expects($this->once())
            ->method('prepare')
            ->with($sql0)
            ->willReturn($statement0);

        $actualResult = $this->sut->hasAnyChangedSince($identifiers, $cacheTime);

        $this->assertFalse($actualResult);
    }

    public function testHasAnyChangedSinceReturnsTrueIfSomeBlocksHaveChanged()
    {
        $identifiers = ['foo', 'bar'];
        $cacheTime   = 'baz';

        $sql0         = 'SELECT COUNT(*) AS count FROM blocks LEFT JOIN block_layouts AS block_layouts ON block_layouts.id = blocks.layout_id WHERE (blocks.deleted_at IS NULL) AND (blocks.identifier IN (?,?)) AND (blocks.updated_at > ? OR block_layouts.updated_at > ?)'; // phpcs:ignore
        $valuesToBind = [
            [$identifiers[0], \PDO::PARAM_STR],
            [$identifiers[1], \PDO::PARAM_STR],
            [$cacheTime, \PDO::PARAM_STR],
            [$cacheTime, \PDO::PARAM_STR],
        ];
        $returnValue  = '2';
        $statement0   = MockStatementFactory::createReadColumnStatement($this, $valuesToBind, $returnValue);

        $this->readConnectionMock
            ->expects($this->once())
            ->method('prepare')
            ->with($sql0)
            ->willReturn($statement0);

        $actualResult = $this->sut->hasAnyChangedSince($identifiers, $cacheTime);

        $this->assertTrue($actualResult);
    }

    public function testHasAnyChangedSinceThrowsExceptionIfQueryFails()
    {
        $identifiers = ['foo', 'bar'];
        $cacheTime   = 'baz';
        $errorInfo   = ['FOO123', 1, 'near AS v0, ar.identifier: hello'];

        $this->expectException(Database::class);
        $this->expectExceptionCode($errorInfo[1]);

        $sql0         = 'SELECT COUNT(*) AS count FROM blocks LEFT JOIN block_layouts AS block_layouts ON block_layouts.id = blocks.layout_id WHERE (blocks.deleted_at IS NULL) AND (blocks.identifier IN (?,?)) AND (blocks.updated_at > ? OR block_layouts.updated_at > ?)'; // phpcs:ignore
        $valuesToBind = [
            [$identifiers[0], \PDO::PARAM_STR],
            [$identifiers[1], \PDO::PARAM_STR],
            [$cacheTime, \PDO::PARAM_STR],
            [$cacheTime, \PDO::PARAM_STR],
        ];
        $statement0   = MockStatementFactory::createErrorStatement($this, $valuesToBind, $errorInfo);

        $this->readConnectionMock
            ->expects($this->once())
            ->method('prepare')
            ->with($sql0)
            ->willReturn($statement0);

        $this->sut->hasAnyChangedSince($identifiers, $cacheTime);
    }
}
