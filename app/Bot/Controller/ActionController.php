<?php

declare(strict_types=1);

namespace Bot\Controller;

use Bot\Commands\Events;

class ActionController extends Controller
{
    /**
     * обработка action (message\\action)
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (isset($data['action'])) {
            $event = $data['action']['type'];
            $member_id = $data['action']['member_id'];

            new Events(parent::$vk, $event, $member_id);
        }
    }
}