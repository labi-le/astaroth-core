<?php
declare(strict_types=1);

namespace Parser;

use Astaroth\Attribute\Description;
use Astaroth\Parser\DescriptionParser;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsObject;

#[Description("test description")]
class DescriptionParserTest extends TestCase
{

    public function test__construct()
    {
        assertIsObject(new DescriptionParser(__CLASS__));
    }

    public function testGetClassDescription()
    {
        assertEquals("test description", (new DescriptionParser(__CLASS__))->getClassDescription());
    }

    #[Description("test method description")]
    public function testGetMethodDescription()
    {
        assertEquals("test method description", (new DescriptionParser(__CLASS__))->getMethodDescription("testGetMethodDescription"));
    }
}
