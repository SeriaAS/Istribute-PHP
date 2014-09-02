<?php

namespace Seria\istributeSdk;

class IstributeErrorException extends \Exception {
	protected $data;

	public function __construct($message, $code, $data) {
		parent::__construct($message, $code);
		$this->data = $data;
	}

	public function getData() {
		return $this->data;
	}
}