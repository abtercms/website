<?php

declare(strict_types=1);

namespace AbterPhp\Website\Grid\Filters;

use PHPUnit\Framework\TestCase;

class BlockLayoutTest extends TestCase
{
    /**
     * @return array
     */
    public function filterProvider(): array
    {
        return [
            [[], [], null],
        ];
    }

    /**
     * @dataProvider filterProvider
     *
     * @param string[]    $intents
     * @param array       $attributes
     * @param string|null $tag
     */
    public function testFilter(array $intents, array $attributes, ?string $tag)
    {
        $sut = new BlockLayout($intents, $attributes, $tag);

        $html = (string)$sut;

        $this->assertStringContainsString('<div class="hidable">', $html);
        $this->assertStringContainsString('filter-identifier', $html);
    }
}
