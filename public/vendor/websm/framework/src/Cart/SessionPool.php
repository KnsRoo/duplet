<?php

namespace Websm\Framework\Cart;

use Websm\Framework\Session\StorageInterface;

use Websm\Framework\Cart\Exceptions\BaseException;
use Websm\Framework\Cart\Exceptions\NotFoundException;
use Websm\Framework\Cart\Exceptions\InvalidArgumentException;

class SessionPool implements CartPoolInterface {

    const NAME = 'cart';

    private $session;
    private $name = '';
    private $pool = [];

    public function __construct(StorageInterface $session, $id = null) {

        $this->session = $session;

        $name = self::NAME;
        if ($id) $name .= ':'.$id;
        $this->name = $name;

        if ($session->has($name)) {

            $pool = $session->get($name);
            $this->pool = unserialize($pool) ?: [];

        }

    }

    public function createItem($id) {

        if (!is_string($id) || !$id)
            throw new InvalidArgumentException('id is empty.');

        $item = new Item($id);

        $this->pool[$id] = $item;

        return $item;

    }

    public function add(CartItemInterface $item) {

        $id = $item->getId();

        if ($this->has($id))
            throw new BaseException('already exists.');

        $this->pool[$id] = $item;
        return $this;

    }

    public function remove($id) {

        if (!is_string($id) || !$id)
            throw new InvalidArgumentException('id is empty.');

        if ($this->has($id)) {

            unset($this->pool[$id]);

        } else {

            throw new Exception('Item not found.');

        }

        return $this;

    }

    public function getItem($id) {

        if (!is_string($id) || !$id)
            throw new InvalidArgumentException('id is empty.');

        if ($this->has($id)) {

            return $this->pool[$id];

        } else {

            throw new NotFoundException('Item not found.');

        }

    }

    public function getCount() {

        $count = 0;

        foreach ($this->pool as $item)
            $count += $item->getCount();

        return $count;

    }

    public function getSumm() {

        $summ = 0;

        foreach ($this->pool as $item) {

            $summ += $item->getSumm();

        }

        return $summ;

    }

    public function getItems() {

        return $this->pool;

    }

    public function clear() {

        $this->pool = [];

    }

    public function isEmpty() {

        if ($this->getCount()) return false;
        else return true;

    }

    public function has($id) {

        if (!is_string($id) || !$id)
            throw new InvalidArgumentException('id is empty.');

        return isset($this->pool[$id]);

    }

    public function __destruct() {

        $session = $this->session;
        $name = $this->name;

        $pool = serialize($this->pool);
        $session->set($name, $pool);

    }

}
