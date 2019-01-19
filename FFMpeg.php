<?php

namespace phuong17889\ffmpeg;

use Alchemy\BinaryDriver\ConfigurationInterface;
use FFMpeg\Driver\FFMpegDriver;
use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\FFProbe;
use FFMpeg\Media\Audio;
use phuong17889\ffmpeg\media\Video;
use Psr\Log\LoggerInterface;

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
	 * Creates a new FFMpeg instance.
	 *
	 * @param array|ConfigurationInterface $configuration
	 * @param LoggerInterface              $logger
	 * @param FFProbe                      $probe
	 *
	 * @return self
	 */
	public static function create($configuration = array(), LoggerInterface $logger = null, FFProbe $probe = null) {
		if (null === $probe) {
			$probe = FFProbe::create($configuration, $logger, null);
		}
		return new static(FFMpegDriver::create($logger, $configuration), $probe);
	}

	public function __construct(FFMpegDriver $ffmpeg, FFProbe $ffprobe) {
		$this->driver  = $ffmpeg;
		$this->ffprobe = $ffprobe;
	}

	/**
	 * Sets FFProbe.
	 *
	 * @param FFProbe
	 *
	 * @return FFMpeg
	 */
	public function setFFProbe(FFProbe $ffprobe) {
		$this->ffprobe = $ffprobe;
		return $this;
	}

	/**
	 * Gets FFProbe.
	 *
	 * @return FFProbe
	 */
	public function getFFProbe() {
		return $this->ffprobe;
	}

	/**
	 * Sets the ffmpeg driver.
	 *
	 * @return FFMpeg
	 */
	public function setFFMpegDriver(FFMpegDriver $ffmpeg) {
		$this->driver = $ffmpeg;
		return $this;
	}

	/**
	 * Gets the ffmpeg driver.
	 *
	 * @return FFMpegDriver
	 */
	public function getFFMpegDriver() {
		return $this->driver;
	}
}
