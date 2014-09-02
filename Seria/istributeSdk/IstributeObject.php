<?php

namespace Seria\istributeSdk;

class IstributeObject {
	protected $istribute;

	public function __construct(Istribute $istribute) {
		$this->istribute = $istribute;
	}
}