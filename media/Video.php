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

use FFMpeg\Exception\InvalidArgumentException;
use FFMpeg\Format\FormatInterface;
use phuong17889\ffmpeg\coordinate\Dimension;
use phuong17889\ffmpeg\format\X264;
use yii\helpers\Console;

class Video extends \FFMpeg\Media\Video {

	const nHD = 'nHD';

	const qHD = 'qHD';

	const SD  = 'SD';

	const HD  = 'HD';

	const FHD = 'FHD';

	protected $videos              = [];

	protected $is_hls              = false;

	private   $is_progress_started = false;

	/**
	 * @param FormatInterface $format
	 * @param                 $output_path_file
	 *
	 * @return \FFMpeg\Media\Video
	 */
	public function save(FormatInterface $format, $output_path_file) {
		if (!file_exists(dirname($output_path_file))) {
			@mkdir(dirname($output_path_file), 0777, true);
		}
		return parent::save($format, $output_path_file);
	}

	/**
	 * @param bool $enable
	 *
	 * @return $this
	 */
	public function hls($enable = true) {
		$this->is_hls = $enable;
		return $this;
	}

	/**
	 * @param string $video_type
	 * @param int    $segment_length
	 *
	 * @return $this
	 */
	public function addFormat($video_type = self::HD, $segment_length = 10) {
		if ($this->is_hls) {
			$format = new X264();
			$format->hls($segment_length);
			switch ($video_type) {
				case Video::nHD:
					$format->setKiloBitrate(360 / 2);
					break;
				case Video::qHD:
					$format->setKiloBitrate(540 / 2);
					break;
				case Video::HD:
					$format->setKiloBitrate(720 / 2);
					break;
				case Video::FHD:
					$format->setKiloBitrate(1080 / 2);
					break;
				case Video::SD:
					$format->setKiloBitrate(480 / 2);
					break;
				default:
					$format->setKiloBitrate(720 / 2);
					break;
			}
			$dimension = Dimension::instance($video_type);
			$video     = $this;
			$video->filters()->resize($dimension)->synchronize();
			$videos              = [];
			$videos['type']      = $video_type;
			$videos['format']    = $format;
			$videos['video']     = $video;
			$videos['dimension'] = $dimension;
			$videos['file_name'] = $video_type . DIRECTORY_SEPARATOR . $video_type . '.m3u8';
			$this->videos[]      = $videos;
		} else {
			throw new InvalidArgumentException('HLS must be enabled. Using `$this->hls()`.');
		}
		return $this;
	}

	/**
	 * @param      $output_path_file
	 * @param null $output_url_path
	 */
	public function export($output_path_file, $output_url_path = null) {
		if ($output_url_path != null && substr($output_url_path, - 1) != '/') {
			throw new InvalidArgumentException('$output_url_path must be ended with splash.');
		}
		if ($this->is_hls) {
			if (count($this->videos) > 0) {
				if (!file_exists(dirname($output_path_file))) {
					@mkdir(dirname($output_path_file), 0777, true);
				}
				if (\Yii::$app instanceof \Yii\console\Application && !$this->is_progress_started) {
					Console::startProgress(0, 100);
					$this->is_progress_started = true;
				}
				$content[] = "#EXTM3U";
				foreach ($this->videos as $key => $videoArray) {
					/**@var X264 $format */
					$format = $videoArray['format'];
					/**@var Dimension $dimension */
					$dimension = $videoArray['dimension'];
					/**@var Video $video */
					$video     = $videoArray['video'];
					$file_name = $videoArray['file_name'];
					$file_path = dirname($output_path_file) . DIRECTORY_SEPARATOR . $file_name;
					$format->on('progress', function($video, $format, $percentage) use ($key) {
						if (\Yii::$app instanceof \Yii\console\Application) {
							if ($key > 0 && $percentage > 0) {
								$percentage += (100 / count($this->videos) * $key);
							}
							Console::updateProgress($percentage / count($this->videos), 100);
						}
					});
					$video->save($format, $file_path);
					$content[] = "#EXT-X-STREAM-INF:BANDWIDTH=" . ($format->getKiloBitrate() * 1024) . ",RESOLUTION=" . $dimension->getWidth() . "x" . $dimension->getHeight();
					if ($output_url_path != null) {
						$content[] = $output_url_path . str_replace(DIRECTORY_SEPARATOR, '/', $file_name);
						$video->fixPath($output_url_path, $file_path, $videoArray['type']);
					} else {
						$content[] = str_replace(DIRECTORY_SEPARATOR, '/', $file_name);
					}
				}
				if (\Yii::$app instanceof \Yii\console\Application && $this->is_progress_started) {
					Console::updateProgress(100, 100);
					Console::endProgress(true);
					$this->is_progress_started = false;
				}
				file_put_contents($output_path_file, implode("\n", $content));
			} else {
				throw new InvalidArgumentException('Atlease 1 format must be added. Using `$this->addFormat()`.');
			}
		} else {
			throw new InvalidArgumentException('HLS must be enabled. Using `$this->hls()`.');
		}
	}

	/**
	 * @param $output_url_path
	 * @param $file_path
	 * @param $type
	 */
	public function fixPath($output_url_path, $file_path, $type) {
		if ($this->is_hls) {
			if (file_exists($file_path)) {
				$file_contents = explode(PHP_EOL, file_get_contents($file_path));
				$file_contents = preg_replace('/' . $type . '(\d+).ts/', $output_url_path . $type . '/' . '$0', $file_contents);
				file_put_contents($file_path, $file_contents);
			} else {
				throw new InvalidArgumentException('Can not find the file `' . $file_path . '`');
			}
		} else {
			throw new InvalidArgumentException('HLS must be enabled. Using `$this->hls()`.');
		}
	}
}
