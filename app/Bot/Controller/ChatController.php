<?php

declare(strict_types=1);


namespace Bot\Controller;


use Bot\Commands\Actions;
use Bot\Models\DataParser;

final class ChatController extends Controller
{
    /**
     * @param DataParser $data
     */
    public function __construct(DataParser $data)
    {
        if ($data->getAction()) {
            new Actions(parent::$vk, $data);
        }
    }
}