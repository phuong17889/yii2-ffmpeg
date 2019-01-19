<?php
/**
 * Created by Navatech.
 * @project linkyes-net
 * @author  Phuong
 * @email   notteen[at]gmail.com
 * @date    1/19/2019
 * @time    5:58 PM
 */

namespace phuong17889\ffmpeg\filters;
class VideoFilters extends \FFMpeg\Filters\Video\VideoFilters {

	/**
	 * Resizes a video to a given dimension.
	 *
	 * @param $segment_length
	 *
	 * @return VideoFilters
	 */
	public function hls($segment_length) {
		$this->media->addFilter(new HLSFilter($segment_length));
		return $this;
	}
}
