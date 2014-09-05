<?php

namespace Seria\istributeSdk;

class Istribute extends Http {
	public function __construct($appId, $appKey, $serverUrl='http://api.istribute.com') {
		parent::__construct($appId, $appKey, $serverUrl);
	}

	public function getAnonymousIstribute() {
		return new Istribute($this->getAppId(), FALSE, $this->getServerUrl());
	}

	/**
	 * @return VideoList
	 */
	public function getVideoList() {
		return new VideoList($this);
	}

	/**
	 * @param $id
	 * @return Video
	 */
	public function getVideo($id) {
		return new Video($this, $id);
	}

	/**
	 * @param $filename
	 * @return null|Video
	 */
	public function uploadVideo($filename) {
		$data = $this->put('/v1/video/put/?md5=' . urlencode(md5_file($filename)), $filename);
		if (!$data || !is_object($data) || !isset($data->videoId))
			return NULL;
		$id = $data->videoId;
		if ($id)
			return new Video($this, $id);
		else
			return NULL;
	}
}