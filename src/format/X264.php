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
	public function __construct($audioCodec = 'libfaac', $videoCodec = 'libx264') {
		parent::__construct($audioCodec, $videoCodec);
		$this->setAudioCodec("libmp3lame");
	}
}
