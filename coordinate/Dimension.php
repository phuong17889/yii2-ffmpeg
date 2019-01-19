<?php
/**
 * Created by Navatech.
 * @project linkyes-net
 * @author  Phuong
 * @email   notteen[at]gmail.com
 * @date    1/19/2019
 * @time    6:04 PM
 */

namespace phuong17889\ffmpeg\coordinate;

use phuong17889\ffmpeg\media\Video;

class Dimension extends \FFMpeg\Coordinate\Dimension {

	public static function instance($dimension) {
		switch ($dimension) {
			case Video::nHD:
				return new Dimension(640, 360);
			case Video::qHD:
				return new Dimension(960, 540);
			case Video::HD:
				return new Dimension(1280, 720);
			case Video::FHD:
				return new Dimension(1920, 1080);
			case Video::SD:
				return new Dimension(640, 480);
			default:
				return new Dimension(1280, 720);
		}
	}
}
