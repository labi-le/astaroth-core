<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

use function file_get_contents;
use function file_put_contents;
use function json_decode;
use function json_encode;
use function unlink;
use const DIRECTORY_SEPARATOR;
use const JSON_PRETTY_PRINT;
use const LOCK_EX;

/**
 * Class Session
 * Simple database for recording states
 * @package Astaroth\Foundation
 */
class Session
{
    public const FILE_EXTENSION = ".json";
    /**
     * Session file path
     * @var string
     */
    private string $fullStoragePath;

    public function __construct(int $id, private string $type, string $cache_path)
    {
        $storageName = $id . self::FILE_EXTENSION;
        $this->fullStoragePath = $cache_path . DIRECTORY_SEPARATOR . $storageName;
    }

    /**
     * Switch to another type
     * @param string $type
     * @return $this
     */
    public function changeType(string $type): static
    {
        $this->type = $type;
        return $this;
    }


    /**
     * Get items from session
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->getCurrentTypeData()[$key] ?? null;
    }

    /**
     * Write data
     * @param $key
     * @param $value
     * @return bool
     */
    public function put($key, $value): bool
    {
        $storage = $this->getStorageData() ?: [];
        $storage[$this->getType()][$key] = $value;

        return $this->saveToFile($storage);
    }

    public function removeKey(string $key): bool
    {
        $storage = $this->getStorageData() ?: [];
        unset($storage[$this->getType()][$key]);

        return $this->saveToFile($storage);
    }

    /**
     * Save data to session file
     * @param array $data
     * @return bool
     * @noinspection JsonEncodingApiUsageInspection
     */
    private function saveToFile(array $data): bool
    {
        return (bool)@file_put_contents(
            $this->fullStoragePath,
            json_encode($data, JSON_PRETTY_PRINT),
            LOCK_EX
        );
    }

    /**
     * Delete session file or parameter
     * @param bool $current_type
     * @return bool
     */
    public function purge(bool $current_type = true): bool
    {
        if ($current_type) {
            $storage = $this->getStorageData();
            unset($storage[$this->getType()]);

            return $this->saveToFile($storage);
        }

        return @unlink($this->fullStoragePath);
    }

    /**
     * Get data from session file
     * @return array
     * @noinspection JsonEncodingApiUsageInspection
     */
    private function getStorageData(): array
    {
        $content = (string)@file_get_contents($this->fullStoragePath);
        return @json_decode($content, true) ?: [];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function getCurrentTypeData()
    {
        return $this->getStorageData()[$this->getType()] ?? [];
    }

}