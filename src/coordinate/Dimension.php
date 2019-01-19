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
class Dimension extends \FFMpeg\Coordinate\Dimension {

	const DIMENSION_240P  = 240;

	const DIMENSION_360P  = 360;

	const DIMENSION_480P  = 480;

	const DIMENSION_720P  = 720;

	const DIMENSION_1080P = 1080;

	const DIMENSION_1440P = 1440;

	const DIMENSION_2160P = 2160;

	public static function instance($dimension) {
		switch ($dimension) {
			case  self::DIMENSION_240P:
				return new Dimension(426, 240);
			case self::DIMENSION_360P:
				return new Dimension(640, 360);
			case self::DIMENSION_480P:
				return new Dimension(854, 480);
			case self::DIMENSION_720P:
				return new Dimension(1280, 720);
			case self::DIMENSION_1080P:
				return new Dimension(1920, 1080);
			case self::DIMENSION_1440P:
				return new Dimension(2560, 1440);
			case self::DIMENSION_2160P:
				return new Dimension(3840, 2160);
			default:
				return new Dimension(426, 240);
		}
	}
}
