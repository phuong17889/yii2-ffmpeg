<?php
/**
 * Created by Navatech.
 * @project linkyes-net
 * @author  Phuong
 * @email   notteen[at]gmail.com
 * @date    1/19/2019
 * @time    6:13 PM
 */

namespace phuong17889\ffmpeg\format;
class X264 extends \FFMpeg\Format\Video\X264 {

	/**
	 * X264 constructor.
	 *
	 * @param string $audioCodec
	 * @param string $videoCodec
	 */
	public function __construct($audioCodec = 'libmp3lame', $videoCodec = 'libx264') {
		parent::__construct($audioCodec, $videoCodec);
	}

	/**
	 * @param $segment_length
	 *
	 * @return $this
	 */
	public function hls($segment_length) {
		$this->setAdditionalParameters([
			'-g',
			'60',
			'-hls_time',
			$segment_length,
			'-hls_list_size',
			'0',
		]);
		return $this;
	}
}
