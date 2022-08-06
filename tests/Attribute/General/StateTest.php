<?php

declare(strict_types=1);

namespace Attribute\General;

use Astaroth\Attribute\General\State;
use Astaroth\Enums\ConversationType;
use Astaroth\Foundation\FacadePlaceholder;
use Astaroth\Foundation\Session;
use Astaroth\Test\TestCase;
use Exception;

use function PHPUnit\Framework\assertTrue;
use function sys_get_temp_dir;

class StateTest extends TestCase
{
    private Session $session;

    protected function setUp(): void
    {
        $this->session = new Session($this->getTestData()->messageNew()->getPeerId(), State::RESERVED_NAME, sys_get_temp_dir());
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
        FacadePlaceholder::getInstance();

        $hs = (new State("example", ConversationType::ALL))->setHaystack($this->getTestData()->messageNew());
        assertTrue($hs->validate());
    }
}
