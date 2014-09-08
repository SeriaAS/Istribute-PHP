<?php

namespace Seria\istributeSdk;

class Http {
	protected $serverUrl;
	protected $appId;
	protected $appKey;

	public function __construct($appId, $appKey, $serverUrl) {
		$this->serverUrl = $serverUrl;
		$this->appId = $appId;
		$this->appKey = $appKey;
	}

	public function getAppId() {
		return $this->appId;
	}
	public function getAppKey() {
		return $this->appKey;
	}
	public function getServerUrl() {
		return $this->serverUrl;
	}

	public function sign($path, $expiry = null) {
		if (!$this->appKey) {
			return $this->serverUrl . $path;
		}

		// Add the appId GET parameter
		if (strpos($path, '?') !== false) {
			$path .= '&';
		} else {
			$path .= '?';
		}
		$path .= 'appId=' . urlencode($this->appId);

		// If expiry is not provided, expire the URL after 24 hours
		if (!$expiry) {
			$expiry = time() + (3600 * 24);
		}
		$path .= '&signExpiry=' . $expiry;

		// Sign the path
		$signature = hash_hmac('sha256', $path, $this->appKey);
		// Concat path to endpoint
		$url = $this->serverUrl . $path . '&signature=' . urlencode($signature);

		return $url;
	}

	/**
	 * @param $data
	 * @return mixed
	 * @throws InvalidResponseException
	 * @throws IstributeErrorException
	 */
	private function jsonResponseFilter($data) {
		$data = json_decode($data);
		if ($data === NULL) {
			throw new InvalidResponseException('Result from Istribute is not valid JSON');
		}
		if (is_object($data)) {
			if (isset($data->error) && $data->error) {
				throw new IstributeErrorException('Error from Istribute: ' . $data->error, 500, $data);
			}
		}

		return $data;
	}

	/**
	 * @param $path
	 * @return mixed
	 * @throws InvalidResponseException
	 * @throws IstributeErrorException
	 */
	public function get($path) {
		$signedUrl = $this->sign($path);

		$curl = curl_init($signedUrl);

		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$data = curl_exec($curl);

		return $this->jsonResponseFilter($data);
	}

	/**
	 * @param $path
	 * @param $fields
	 * @return mixed
	 * @throws InvalidResponseException
	 * @throws IstributeErrorException
	 */
	public function post($path, $fields) {
		$postData = http_build_query($fields, '', '&');
		$postChecksum = md5($postData);
		if (strpos($path, '?')) {
			$path .= '&postChecksum=' . urlencode($postChecksum);
		} else {
			$path .= '?postChecksum=' . urlencode($postChecksum);
		}

		$signedUrl = $this->sign($path);

		$curl = curl_init($signedUrl);

		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

		$data = curl_exec($curl);

		return $this->jsonResponseFilter($data);
	}

	/**
	 * @param $path
	 * @param $filename
	 * @return mixed
	 * @throws InvalidResponseException
	 * @throws IstributeErrorException
	 */
	public function put($path, $filename) {
		$signedUrl = $this->sign($path);

		$curl = curl_init($signedUrl);

		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_PUT, true);
		curl_setopt($curl, CURLOPT_INFILE, fopen($filename, 'r'));
		curl_setopt($curl, CURLOPT_INFILESIZE, filesize($filename));

		$data = curl_exec($curl);

		return $this->jsonResponseFilter($data);
	}
}
