<?php

declare(strict_types=1);

namespace Attribute\General;

use Astaroth\Attribute\General\State;
use Astaroth\Auth\Configuration;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\Enums\ConversationType;
use Astaroth\Foundation\FacadePlaceholder;
use Astaroth\Foundation\Session;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use function dirname;
use function PHPUnit\Framework\assertTrue;
use function sys_get_temp_dir;

class StateTest extends TestCase
{
    private const DATA_DIR = __DIR__ . "/../../data.php";

    private DataFetcher $data;
    private Session $session;

    protected function setUp(): void
    {
        $this->data = require self::DATA_DIR;

        $this->session = new Session(259166248, State::RESERVED_NAME, sys_get_temp_dir());
        $this->session->put("example", true);
    }

    protected function tearDown(): void
    {
        $this->session->purge(false);
    }

    public function testSetHaystack(): void
    {
        $this->testValidate();
    }

    /**
     * @throws Exception
     */
    public function testValidate(): void
    {
        //cache directory
        FacadePlaceholder::getInstance(new ContainerBuilder(), new Configuration(dirname(__DIR__, 2)));

        $hs = (new State("example", ConversationType::PERSONAL))->setHaystack($this->data->messageNew());
        assertTrue($hs->validate());
    }
}
