<?php

declare(strict_types=1);

namespace Astaroth\Foundation;

/**
 * Class Session
 * Simple database for recording states
 * @package Astaroth\Foundation
 */
class Session
{
    /**
     * Session file path
     * @var string
     */
    private string $fullStoragePath;

    public function __construct(int $id, private string $type)
    {
        $storageName = $id . ".json";
        $this->fullStoragePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $storageName;
    }


    /**
     * Get items from session
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->getStorageData()[$this->type][$key] ?? null;
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

        $storage[$this->type][$key] = $value;

        return $this->saveToFile($storage);
    }

    /**
     * Save data to session file
     * @param array $data
     * @return bool
     */
    private function saveToFile(array $data): bool
    {
        return @file_put_contents(
            $this->fullStoragePath,
            json_encode($data, JSON_PRETTY_PRINT),
            LOCK_EX
        );
    }

    /**
     * Delete session file or parameter
     * @param string|null $key
     * @return bool
     */
    public function purge(string $key = null): bool
    {
        if ($key !== null) {
            $storage = $this->getStorageData() ?: [];
            unset($storage[$this->type]);

            return $this->saveToFile($storage);
        }
        return unlink($this->fullStoragePath);
    }

    /**
     * Get data from session file
     * @return mixed
     */
    private function getStorageData(): mixed
    {
        $content = @file_get_contents($this->fullStoragePath);
        return json_decode($content, true);
    }


}