<?php

namespace phuong17889\ffmpeg;

use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\FFProbe;
use FFMpeg\Media\Audio;
use phuong17889\ffmpeg\media\Video;

class FFMpeg extends \FFMpeg\FFMpeg {

	/** @var FFMpegDriver */
	private $driver;

	/** @var FFProbe */
	private $ffprobe;

	/**
	 * Opens a file in order to be processed.
	 *
	 * @param string $pathfile A pathfile
	 *
	 * @return Audio|Video
	 *
	 * @throws InvalidArgumentException
	 */
	public function open($pathfile) {
		if (($streams = $this->ffprobe->streams($pathfile)) === null) {
			throw new RuntimeException(sprintf('Unable to probe "%s".', $pathfile));
		}
		if (0 < count($streams->videos())) {
			return new Video($pathfile, $this->driver, $this->ffprobe);
		} elseif (0 < count($streams->audios())) {
			return new Audio($pathfile, $this->driver, $this->ffprobe);
		}
		throw new InvalidArgumentException('Unable to detect file format, only audio and video supported');
	}

	/**
	 * FFMpeg constructor.
	 *
	 * @param FFMpegDriver $ffmpeg
	 * @param FFProbe      $ffprobe
	 */
	public function __construct(FFMpegDriver $ffmpeg, FFProbe $ffprobe) {
		parent::__construct($ffmpeg, $ffprobe);
		$this->driver  = $ffmpeg;
		$this->ffprobe = $ffprobe;
	}
}
