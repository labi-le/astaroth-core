<?php


namespace Bot\Controller;


use Bot\Commands\CommandList;
use Bot\Models\DataParser;
use Bot\Models\MethodExecutor;
use Bot\Models\TextMatch;

final class CommandController extends Controller
{

    public function __construct(DataParser $data, string $namespace = 'Bot\\Commands\\')
    {
        foreach (CommandList::text() as $cmd) {
            is_array($cmd['text']) ?: $cmd['text'] = [$cmd['text']];
            is_array($cmd['method']) ?: $cmd['method'] = [$cmd['method']];

            foreach ($cmd['text'] as $needle) {
                if ((new TextMatch($needle, mb_strtolower($data->getText())))->compare()) {
                    new MethodExecutor($namespace, $cmd['method'], parent::$vk, $data);
                    break;
                }

            }
        }
    }
}