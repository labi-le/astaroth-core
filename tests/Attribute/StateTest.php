<?php

declare(strict_types=1);

namespace Attribute;

use Astaroth\Attribute\State;
use Astaroth\Auth\Configuration;
use Astaroth\DataFetcher\DataFetcher;
use Astaroth\Foundation\FacadePlaceholder;
use Astaroth\Foundation\Session;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use function dirname;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class StateTest extends TestCase
{
    private DataFetcher $data;
    private Session $session;

    protected function setUp(): void
    {
        $this->data = (require __DIR__ . "/../data.php");

        $this->session = new Session(259166248, State::RESERVED_NAME, sys_get_temp_dir());
        $this->session->put("example", true);
    }

    protected function tearDown(): void
    {
        $this->session->purge(false);
    }

    public function testSetHaystack()
    {
        $hs = (new State("button_set", State::USER))->setHaystack($this->data);
        assertEquals(State::class, $hs::class);

        $hs = (new State("button_set", State::USER))->setHaystack($this->data->messageNew());
        assertEquals(State::class, $hs::class);

        $hs = (new State("button_set", State::USER))->setHaystack($this->data->messageEvent());
        assertEquals(State::class, $hs::class);


    }

    public function testValidate()
    {
        FacadePlaceholder::getInstance(new ContainerBuilder(), Configuration::set(dirname(__DIR__)));

        $hs = (new State("example", State::USER))->setHaystack($this->data->messageNew());
        assertTrue($hs->validate());
    }
}
