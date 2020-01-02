<?php

declare(strict_types=1);

namespace AbterPhp\Website\Validation\Factory;

use AbterPhp\Admin\TestDouble\Validation\StubRulesFactory;
use AbterPhp\Framework\Validation\Rules\Uuid;
use Opulence\Validation\IValidator;
use Opulence\Validation\Rules\Factories\RulesFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContentListTest extends TestCase
{
    /** @var ContentList - System Under Test */
    protected $sut;

    /** @var RulesFactory|MockObject */
    protected $rulesFactoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesFactoryMock = StubRulesFactory::createRulesFactory(
            $this,
            [
                'uuid' => new Uuid(),
            ]
        );

        $this->sut = new ContentList($this->rulesFactoryMock);
    }

    /**
     * @return array
     */
    public function createValidatorProvider(): array
    {
        return [
            'empty-data'                          => [
                [],
                false,
            ],
            'valid-data'                          => [
                [
                    'id'         => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'identifier' => 'foo',
                    'name'       => 'bar',
                    'classes'    => 'baz',
                    'protected'  => '1',
                    'with_image' => '1',
                    'with_links' => '1',
                    'with_html'  => '1',
                ],
                true,
            ],
            'valid-data-missing-all-not-required' => [
                [
                    'id'   => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'name' => 'bar',
                ],
                true,
            ],
            'invalid-id-not-uuid'                 => [
                [
                    'id'   => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'name' => 'bar',
                ],
                false,
            ],
        ];
    }

    /**
     * @dataProvider createValidatorProvider
     *
     * @param array $data
     * @param bool  $expectedResult
     */
    public function testCreateValidator(array $data, bool $expectedResult)
    {
        $validator = $this->sut->createValidator();

        $this->assertInstanceOf(IValidator::class, $validator);

        $actualResult = $validator->isValid($data);

        $this->assertSame($expectedResult, $actualResult);
    }
}
