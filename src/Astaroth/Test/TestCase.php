<?php

declare(strict_types=1);

namespace Astaroth\Test;

use Astaroth\DataFetcher\DataFetcher;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{

    private const TEST_PATH = __DIR__;
    private ?DataFetcher $testData = null;

    protected function setUp(): void
    {
        $this->loadTestClass();
        parent::setUp();
    }

    private function loadTestClass(): void
    {
        require_once self::TEST_PATH . "/TestClass.php";
    }

    protected function getTestData(): DataFetcher
    {
        if ($this->testData === null) {
            $this->testData = require self::TEST_PATH . "/data.php";
        }
        return $this->testData;
    }
}