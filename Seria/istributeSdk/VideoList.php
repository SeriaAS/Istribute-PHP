<?php

namespace Seria\istributeSdk;

class VideoList extends IstributeObject implements \Iterator {
	private $ids;

	public function __construct(Istribute $istribute) {
		parent::__construct($istribute);
		$this->ids = NULL;
	}

	public function getIds() {
		if ($this->ids === NULL) {
			$this->ids = $this->istribute->get('/v1/video/list/');
		}
		return $this->ids;
	}

	public function getVideos() {
		$videos = array();
		foreach ($this->getIds() as $id) {
			$videos[] = new Video($this->istribute, $id);
		}
		return $videos;
	}

	private $iterator = 0;

	/* Iterator */
	public function current()
	{
		$this->getIds();
		if (isset($this->ids[$this->iterator]))
			return new Video($this->istribute, $this->ids[$this->iterator]);
		else
			return NULL;
	}

	public function next()
	{
		$this->iterator++;
	}

	public function key()
	{
		return $this->iterator;
	}

	public function valid()
	{
		$this->getIds();
		return isset($this->ids[$this->iterator]);
	}

	public function rewind()
	{
		$this->iterator = 0;
	}
}