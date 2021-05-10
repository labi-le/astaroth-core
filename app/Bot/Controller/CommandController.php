<?php


namespace Bot\Controller;


use Bot\Commands\CommandList;
use Bot\Models\MethodExecutor;
use Bot\Models\Utils;

final class CommandController extends Controller
{
    public function __construct(string $originalText, string $namespace = 'Bot\\Commands\\')
    {
        $list = CommandList::text();

        foreach ($list as $cmd) {
            is_array($cmd['text']) ?: $cmd['text'] = [$cmd['text']];
            is_array($cmd['method']) ?: $cmd['method'] = [$cmd['method']];

            foreach ($cmd['text'] as $textFromArray) {
                if (Utils::formatText($textFromArray, $originalText)) {
                    new MethodExecutor($namespace, $cmd['method'], parent::$vk);
                    break;
                }

            }
        }
    }
}