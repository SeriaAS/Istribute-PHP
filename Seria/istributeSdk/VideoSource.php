<?php

namespace Seria\istributeSdk;

class VideoSource {
	protected $data;

	public function __construct($data) {
		$this->data = $data;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->data->name;
	}

	/**
	 * @return string
	 */
	public function getLabel() {
		return $this->data->label;
	}

	/**
	 * @return string
	 */
	public function getMedia() {
		return $this->data->media;
	}

	/**
	 * @return string
	 */
	public function getMethod() {
		return $this->data->method;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->data->type;
	}

	/**
	 * @return null|integer
	 */
	public function getWidth() {
		return isset($this->data->width) ? $this->data->width : NULL;
	}

	/**
	 * @return null|integer
	 */
	public function getHeight() {
		return isset($this->data->height) ? $this->data->height : NULL;
	}

	/**
	 * @return null|integer
	 */
	public function getBitrate() {
		return isset($this->data->bitrate) ? $this->data->bitrate : NULL;
	}

	/**
	 * @return null|integer
	 */
	public function getVideoBitrate() {
		return isset($this->data->videoBitrate) ? $this->data->videoBitrate : NULL;
	}

	/**
	 * @return null|integer
	 */
	public function getAudioBitrate() {
		return isset($this->data->audioBitrate) ? $this->data->audioBitrate : NULL;
	}

	/**
	 * @return string Url to the source
	 */
	public function getSrc() {
		return $this->data->src;
	}

	/**
	 * @return array|null List of codecs.
	 */
	public function getCodecs() {
		if (!isset($this->data->codecs))
			return NULL;
		$rawcodecs = explode(',', $this->data->codecs);
		$codecs = array();
		foreach ($rawcodecs as $rawcodec) {
			$codec = trim($rawcodec);
			if ($codec)
				$codecs[] = $codec;
		}
		return $codecs;
	}
}