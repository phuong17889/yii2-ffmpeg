<?php
/**
 * Created by Navatech.
 * @project linkyes-net
 * @author  Phuong
 * @email   notteen[at]gmail.com
 * @date    1/19/2019
 * @time    5:16 PM
 */

namespace phuong17889\ffmpeg\media;

use phuong17889\ffmpeg\filters\HLSFilter;
use phuong17889\ffmpeg\filters\VideoFilters;

class Video extends \FFMpeg\Media\Video {

	/**
	 * HLS video
	 *
	 * @param $segment_length
	 *
	 * @return Video
	 */
	public function hls($segment_length) {
		$this->addFilter(new HLSFilter($segment_length));
		return $this;
	}

	/**
	 * @inheritDoc
	 * @return VideoFilters
	 */
	public function filters() {
		return new VideoFilters($this);
	}
}
