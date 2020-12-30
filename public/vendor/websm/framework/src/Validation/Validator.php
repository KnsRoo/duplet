<?php

namespace Websm\Framework\Validation;

use Websm\Framework\Types;

class Validator {

	protected $scenario = 'default';
	protected $data = [];
	protected $errors = [];
	protected $rules = [];
	protected $on = ['any'];

	public function __get($key) {

		return $this->data[$key];

	}

	public function __set($key, $value) {

		$this->data[$key] = $value;

	}

	public function __construct($scenario = null) {

		if ($scenario) $this->setScenario($scenario);

	}

	public static function init($scenario = null) {

		$instance = new self($scenario);
		return $instance;

	}

	public function setScenario($scenario = null) {

		if ($scenario && !is_string($scenario))
			throw new Exceptions\ScenarioException('Scenario is not string.');

		$this->scenario = $scenario;
		return $this;

	}

	public function add($fields, $rule, $message = null) {

		if ($message && !is_string($message))
			throw new Exceptions\MessageException('Message is not string.');

		if (!($rule instanceof Rules\RuleInterface)) {

			if (is_callable($rule))
				$rule = Rules\CallabckWrapper::handler($rule);

			else throw new Exceptions\InvalidArgument('Rule type is not valid.');

		}

		if (!in_array('any', $this->on) && 
			!in_array($this->scenario, $this->on)) return $this;

		if (!is_array($fields)) $fields = [$fields];

		foreach ($fields as $field) {

			if (!isset($this->rules[$field])) 
				$this->rules[$field] = [];

			if ($message) $rule->setMessage($message);
			$this->rules[$field][] = $rule;

		}

		return $this;

	}

	public function on($scenarios) {

		if (!is_array($scenarios)) $scenarios = [$scenarios];
		$this->on = $scenarios;
		return $this;

	}

	public function bind($data) {

		if (!Types::isIterable($data))
			throw new Exceptions\InvalidArgument('$data is not iterable.');

		$this->data = $data;

		return $this;

	}

	public function getData() {

		return $this->data;

	}

	public function validate() {

		foreach ($this->rules as $field => $rules) {

			foreach ($rules as $rule) {

				$result = $rule->check($field, $this->data);

				if ($result === false)
					$this->createError($field, $rule->getMessage());

			}

		}

		return !$this->errors;

	}

	protected function createError($field, $message = '') {

		$errors = &$this->errors[$field];
		if (!is_array($errors)) $errors = [];
		$errors[] = $message;

	}

	public function getErrors() {

		return $this->errors;

	}

	public function getListErrors() {

		$allErrors = [];

		foreach ($this->errors as $errors)
			$allErrors = array_merge($allErrors, $errors);

		return $allErrors;

	}

}
