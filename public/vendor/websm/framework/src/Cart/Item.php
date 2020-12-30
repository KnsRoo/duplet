<?php

namespace Websm\Framework\Cart;

use Websm\Framework\Cart\Exceptions\BaseException;
use Websm\Framework\Cart\Exceptions\NotFoundException;
use Websm\Framework\Cart\Exceptions\InvalidArgumentException;

class Item implements CartItemInterface {

	private $id;
	private $count = 1;
	private $price = 0;

	public function __construct($id) {

		if (!is_string($id) || !$id)
			throw new InvalidArgumentException('id is empty.');

		$this->id = $id;

	}

	public function __get($property) {

		switch ($property) {
			case 'id':
				return $this->getId();
				break;

			case 'count':
				return $this->getCount();
				break;

			case 'price':
				return $this->getPrice();
				break;

			case 'summ':
				return $this->getSumm();
				break;

			default:
				if (isset($this->$property)) {

					return $this->$property;

				} else return null;
				break;
		}

	}

	public function __set($key, $value) {

		switch ($key) {
			case 'id':
				throw new BaseException('property "id" read only.');
				break;

			case 'summ':
				throw new BaseException('property "summ" read only.');
				break;

			case 'count':
				$this->setCount($value);
				break;

			case 'price':
				$this->setPrice($value);
				break;

			default:
				$this->$key = $value;
				break;
		}

	}

	public function asArray() {

		$ret = [];
		foreach ($this as $key => $value)
			$ret[$key] = $value;

		return $ret;

	}

	public function setData(Array $data) {

		foreach ($data as $key => $value) {

			try { $this->__set($key, $value); }
			catch (BaseException $ex) { continue; }

		}

	}

	public function setCount($count) {

		if (!is_numeric($count))
			throw new InvalidArgumentException('count not integer.');

		if ($count < 1) $count = 1;

		$this->count = (int)$count;
		return $this;

	}

	public function getCount() {

		return (int)$this->count;

	}

	public function incCount() {

		$this->count++;

	}

	public function decCount() {

		if ($this->count > 1) $this->count--;

	}

	public function setPrice($price) {

		if (!is_numeric($price))
			throw new InvalidArgumentException('price not numeric.');

		$this->price = (float)$price;
		return $this;

	}

	public function getPrice() {

		return (float)$this->price;

	}

	public function getSumm() {

		return (float)($this->count * $this->price);

	}

	public function getId() {

		return $this->id;

	}

}
