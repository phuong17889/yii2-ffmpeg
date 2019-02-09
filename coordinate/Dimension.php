<?php
/**
 * Created by Navatech.
 *
 * @project linkyes-net
 * @author  Phuong
 * @email   notteen[at]gmail.com
 * @date    1/19/2019
 * @time    6:04 PM
 */

namespace phuong17889\ffmpeg\coordinate;
class Dimension extends \FFMpeg\Coordinate\Dimension {

	const H360  = 360;

	const H480  = 480;

	const H720  = 720;

	const H1080 = 1080;

	/**
	 * @param $dimension
	 *
	 * @return Dimension
	 */
	public static function instance($dimension) {
		switch ($dimension) {
			case self::H360:
				return new Dimension(640, 360);
			case self::H480:
				return new Dimension(854, 480);
			case self::H720:
				return new Dimension(1280, 720);
			case self::H1080:
				return new Dimension(1920, 1080);
			default:
				return new Dimension(1280, 720);
		}
	}
}
