<?php


namespace Bot\Models;


use Bot\Commands\CommandList;
use Bot\Commands\Commands;
use Bot\Controller\Controller;

class CommandController extends Controller
{
    public function __construct(string $originalText)
    {
        $list = CommandList::text();
        $commands = new Commands($this->vk);
        if (is_array($list)) {

            foreach ($list as $cmd) {
                if (!is_array($cmd['text']) && Utils::formatText(( string)$cmd['text'], $originalText)) {
                    new MethodExecutor($cmd['method'], $commands);
                    break;
                }

                if (is_array($cmd['text'])) {
                    foreach ($cmd['text'] as $textFromArray) {
                        if (Utils::formatText($textFromArray, $originalText)) {
                            new MethodExecutor($cmd['method'], $commands);
                            break;
                        }

                    }
                }
            }
        }
    }
}