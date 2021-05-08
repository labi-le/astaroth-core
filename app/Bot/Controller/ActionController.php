<?php

declare(strict_types=1);

namespace Bot\Controller;

use Bot\Commands\Events;

class ActionController extends Controller
{
    /**
     * обработка action (message\\action)
     * @param string $action
     * @param array $data
     */
    public function __construct(string $action, array $data)
    {
        new Events($action, $data);
    }
}