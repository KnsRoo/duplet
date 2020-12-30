<?php

namespace Websm\Framework\Cache;

use Websm\Framework\Cache\Exceptions\BaseException;
use Websm\Framework\Cache\Exceptions\InvalidArgumentException;

class FileCache {

    private $queue = [];
    private $dir = '';

    public function __construct($dir) {

        if (!is_string($dir))
            throw new InvalidArgumentException('$dir is not string.');

        if (!is_dir($dir))
            throw new BaseException('directory not exists.');

        $this->dir = rtrim($dir, '/');

    }

    private function getFileByKey($key) {

        return $this->dir.'/'.ltrim($key, '/').'.cache';

    }

    public function getItem($key) {

        if (!is_string($key))
            throw new InvalidArgumentException('$key is not string.');

        if (isset($this->queue[$key]))
            return $this->queue[$key];

        $item = false;
        $file = $this->getFileByKey($key);

        if (is_file($file)) {

            $value = file_get_contents($file);
            if ($value !== false) $item = unserialize($value);

        }

        if ($item instanceof CacheItemInterface) return $item;

        return new Item($key);

    }

    /**
     * Returns a traversable set of cache items.
     *
     * @param string[] $keys
     *   An indexed array of keys of items to retrieve.
     *
     * @throws InvalidArgumentException
     *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return array|\Traversable
     *   A traversable collection of Cache Items keyed by the cache keys of
     *   each item. A Cache item will be returned for each key, even if that
     *   key is not found. However, if no keys are specified then an empty
     *   traversable MUST be returned instead.
     */
    public function getItems(Array $keys = []) {

        if (!is_array($keys))
            throw new InvalidArgumentException('$keys is not array.');

        $items = array_map([$this, 'getItem'], $keys);

        return $items;

    }

    /**
     * Confirms if the cache contains specified cache item.
     *
     * Note: This method MAY avoid retrieving the cached value for performance reasons.
     * This could result in a race condition with CacheItemInterface::get(). To avoid
     * such situation use CacheItemInterface::isHit() instead.
     *
     * @param string $key
     *   The key for which to check existence.
     *
     * @throws InvalidArgumentException
     *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return bool
     *   True if item exists in the cache, false otherwise.
     */
    public function hasItem($key) {

        if (!is_string($key))
            throw new InvalidArgumentException('$key is not string.');

        if (isset($this->queue[$key])) {

            $item = $this->queue[$key];
            return $item->isHit();

        }

        return is_file($this->getFileByKey($key));

    }

    /**
     * Deletes all items in the pool.
     *
     * @return bool
     *   True if the pool was successfully cleared. False if there was an error.
     */
    public function clear() {

        $this->queue = [];

        $files = array_diff(scandir($this->dir), ['.', '..']);
        $results = array_map('unlink', $files);

        return !in_array(false, $results);

    }

    /**
     * Removes the item from the pool.
     *
     * @param string $key
     *   The key to delete.
     *
     * @throws InvalidArgumentException
     *   If the $key string is not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return bool
     *   True if the item was successfully removed. False if there was an error.
     */
    public function deleteItem($key) {

        if (!is_string($key))
            throw new InvalidArgumentException('$key is not string.');

        if (isset($this->queue[$key])) unset($this->queue[$key]);

        $file = $this->getFileByKey($key);

        if (is_file($file)) return unlink($file);

        return true;

    }

    /**
     * Removes multiple items from the pool.
     *
     * @param string[] $keys
     *   An array of keys that should be removed from the pool.

     * @throws InvalidArgumentException
     *   If any of the keys in $keys are not a legal value a \Psr\Cache\InvalidArgumentException
     *   MUST be thrown.
     *
     * @return bool
     *   True if the items were successfully removed. False if there was an error.
     */
    public function deleteItems(Array $keys) {

        if (!is_array($keys))
            throw new InvalidArgumentException('$keys is not array.');

        $results = array_map([$this, 'deleteItem'], $keys);

        return !in_array(false, $results);

    }

    /**
     * Persists a cache item immediately.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     *
     * @return bool
     *   True if the item was successfully persisted. False if there was an error.
     */
    public function save(CacheItemInterface $item) {

        if (!$item->isHit()) return false;

        $value = serialize($item);

        $file = $this->getFileByKey($item->getKey());

        $result = file_put_contents($file, $value);

        return $result === false ? false : true;

    }

    /**
     * Sets a cache item to be persisted later.
     *
     * @param CacheItemInterface $item
     *   The cache item to save.
     *
     * @return bool
     *   False if the item could not be queued or if a commit was attempted and failed. True otherwise.
     */
    public function saveDeferred(CacheItemInterface $item) {

        $this->queue[$item->getKey()] = $item;
        return true;

    }

    /**
     * Persists any deferred cache items.
     *
     * @return bool
     *   True if all not-yet-saved items were successfully saved or there were none. False otherwise.
     */
    public function commit() {

        while ($item = array_shift($this->queue)) {

            if (!$this->save($item)) return false;

        }

        return true;

    }

    public function __destruct() { $this->commit(); }

}
