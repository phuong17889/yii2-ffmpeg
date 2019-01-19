<?php

namespace phuong17889\ffmpeg\filters;

use FFMpeg\Filters\Video\VideoFilterInterface;
use FFMpeg\Format\VideoInterface;
use FFMpeg\Media\Video;

class HLSFilter implements VideoFilterInterface {

	protected $segment_length;

	/**
	 * HLSFilter constructor.
	 *
	 * @param $segment_length
	 */
	public function __construct($segment_length) {
		$this->segment_length = $segment_length;
	}

	/**
	 * @param Video          $video
	 * @param VideoInterface $format
	 *
	 * @return array
	 */
	public function apply(Video $video, VideoInterface $format) {
		return [
			'-map',
			'0',
			'-flags',
			'-global_header',
			'-f',
			'segment',
			'-segment_format',
			'mpeg_ts',
			'-segment_time',
			$this->getSegmentLength(),
		];
	}

	/**
	 * @return mixed
	 */
	public function getSegmentLength() {
		return $this->segment_length;
	}

	/**
	 * Returns the priority of the filter.
	 *
	 * @return integer
	 */
	public function getPriority() {
		return 0;
	}
}
