<?php

namespace Seria\istributeSdk;

class Video extends IstributeObject {
	protected $id;
	private $data;

	public function __construct(Istribute $istribute, $id) {
		parent::__construct($istribute);
		$this->id = $id;
		$this->data = NULL;
	}

	protected function getUrlPath() {
		return '/video/' . $this->istribute->getAppId() . '/' . $this->id;
	}
	protected function getData() {
		if ($this->data === NULL) {
			$this->data = $this->istribute->get($this->getUrlPath() . '.json');
		}
		return clone $this->data;
	}
	protected function getSourcesData() {
		return $this->getData()->sources;
	}

	public function getPlayerUrl($authenticated = FALSE) {
		$path = $this->getUrlPath();
		if ($authenticated) {
			$path = $this->istribute->sign($path);
		}
		return $this->istribute->getServerUrl().$path;
	}

	/**
	 * @return array
	 */
	public function getSources() {
		$sources = array();
		foreach ($this->getSourcesData() as $data) {
			$sources[] = new VideoSource($data);
		}
		return $sources;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->getData()->title;
	}

	/**
	 * @param $title string
	 */
	public function setTitle($title) {
		if ($this->data == null)
			$this->getData();
		$this->data->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getState() {
		return $this->getData()->state;
	}

	/**
	 * @return string Url to preview image.
	 */
	public function getPreviewImage() {
		if ($this->getData()->previewImage)
			return $this->istribute->getServerUrl().$this->getData()->previewImage;
		else
			return NULL;
	}
	/**
	 * @return float
	 */
	public function getAspect() {
		return $this->getData()->aspect;
	}

	/**
	 * @return mixed
	 */
	public function getDownloadUrls() {
		return $this->getData()->downloadUrls;
	}

	/**
	 * @return mixed
	 */
	public function getThumbnailStatus() {
		return $this->getData()->thumbnailStatus;
	}

	/**
	 * @return mixed
	 */
	public function getIntervalThumbnailVtt() {
		return $this->getData()->intervalThumbnailVtt;
	}

	/**
	 * @return integer The unix timestamp.
	 */
	public function getTimestamp() {
		return $this->getData()->timestamp;
	}

	/**
	 * Be aware that the API may return the old data for a while
	 * because of caching.
	 *
	 * @return bool
	 * @throws InvalidResponseException
	 */
	public function save() {
		if ($this->data != null) {
			$save = array(
				'video_title' => $this->data->title
			);
			$result = $this->istribute->post('/v1/video/edit/?videoId=' . urlencode($this->id), $save);
			if (isset($result->status) && $result->status)
				return TRUE;
			throw new InvalidResponseException("Missing result status");
		}
	}
}
