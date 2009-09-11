<?php
/**
 * LiveImage, library for workin with images.
 * (c) Alex Kachayev, http://www.kachayev.ru
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * See http://www.gnu.org/copyleft/lesser.html
 *
 * LiveWatermark:
 * Functions for adding text or image watermark on choosen backend.
 * 
 * @version 1.2
 * @package LiveImage
 */

class LiveWatermark {
	/**
	 * @var mixed
	 */
	protected $image;
	/**
	 * @var string
	 */
	protected $type;
	/**
	 * @var int
	 */
	protected $width;
	/**
	 * @var int
	 */
	protected $height;
	/**
	 * @var mixed
	 */
	protected $marked_image;
	/**
	 * @var array
	 */
	protected $sizes;
	/**
	 * @var string
	 */
	protected $position = "C";
	/**
	 * @var int
	 */
	protected $offset_x;
	/**
	 * @var int
	 */
	protected $offset_y;
	/**
	 * @var string
	 */
	protected $orientation;
	/**
	 * @var bool
	 */
	protected $imageCreated = false;
	/**
	 * @var string
	 */
	protected $gd_version;
	/**
	 * @var string
	 */
	protected $fixedColor = ''; 

	/**
	 * You need to specify either a filename or an image resource
	 * when instatiating object
	 * 
	 * @param mixed
	 */
	public function __construct($res) {
		list($this->type, $this->image) = $this->_getImage($res);

		if (!$this->image) {
			$this->_die_error("Your current PHP setup does not support ". $this->type ." images");
		}
	
		$this->width = imagesx($this->image);
		$this->height = imagesy($this->image);

		$gdinfo = gd_info();
		if (preg_match('/(\d)\.\d/', $gdinfo["GD Version"], $gdinfo)) {
			$this->gd_version = $gdinfo[1];			
		} else {
			$this->gd_version = 0;			
		}
		unset($gdinfo);
	}

	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * Adds a watermark to the image
	 * Type defaults to TEXT for backwards compatibility
	 * 
	 * @param  mixed  $mark
	 * @param  string $type
	 */
	public function addWatermark($mark, $type = "TEXT") {
		if ($type == "TEXT") { // We are going to embed text into the image
			$this->orientation = ($this->width > $this->height) ? "H" : "V"; // Choose orientation

			$this->sizes = $this->_getTextSizes($mark);

			$this->_getOffsets();

			// Copy a chunk of the original image (this is where the watermark will be placed)
			$chunk = $this->_getChunk();
			if (!$chunk) $this->_die_error("Could not extract chunk from image");

			$img_mark = $this->_createEmptyWatermark();
			$img_mark = $this->_addTextWatermark($mark, $img_mark, $chunk);

			// Delete chunk
			imagedestroy($chunk);

			// Finish image
			$this->_createMarkedImage($img_mark, $type, 30);
		} elseif ($type == "IMAGE") { // We are going to embed an image
			list($dummy, $mark) = $this->_getImage($mark);
			$this->sizes = $this->_getImageSizes($mark);

			$this->_getOffsets();

			$img_mark = $this->_createEmptyWatermark();
			$img_mark = $this->_addImageWatermark($mark, $img_mark);

			$this->_createMarkedImage($img_mark, $type, 30);
		}
	}
	/**
	 * Public int getMarkedImage
	 * Returns the final image
	 */
	public function getMarkedImage() {
		if ($this->imageCreated == false) {
			$this->addWatermark($this->version);
		}
		return $this->marked_image;
	}

	/**
	 * Set position of watermark on image
	 * Return true on valid parameter, otherwise false
	 * 
	 * @param  string $newposition
	 * @return bool
	 */
	public function setPosition($newposition) {
		$valid_positions = array(
			"TL", "TM", "TR", "CL", "C", "CR", "BL", "BM", "BR", "RND"
		);

		$newposition = strtoupper($newposition);

		if (in_array($newposition, $valid_positions)) {
			if ($newposition == "RND") {
				$newposition = $valid_positions[rand(0, sizeof($valid_positions) - 2)];
			}
			$this->position = $newposition;
			return true;
		}
		return false;
	}
	/**
	 * Set a fixed color for text watermarks
	 * Return true on valid parameter, otherwise false
	 * 
	 * @param  array $color
	 * @return bool
	 */
	public function setFixedColor($color) {
		$text_color = array();
		if (is_array($color) and sizeof($color) == 3) {
			$text_color["r"] = $color[0];
			$text_color["g"] = $color[1];
			$text_color["b"] = $color[2];
		} elseif (preg_match('/^#?([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})$/i', $color, $matches)) {
			$text_color["r"] = hexdec($matches[1]);
			$text_color["g"] = hexdec($matches[2]);
			$text_color["b"] = hexdec($matches[3]);
		} else {
			return false;
		}
		foreach (array("r", "g", "b") as $key) {
			if (!array_key_exists($key, $text_color) or $text_color[$key] < 0 or $text_color[$key] > 255) {
				return false;
			}
		}
		$this->fixedColor = $text_color;
		return true;
	}

	protected function _die_error($errmsg) {
		die($errmsg);
	}

	protected function _getTextSizes($text) {
		$act_scale = 0;
		$act_font = 0;
		
		$marklength = strlen($text);
		$scale = ($this->orientation == "H") ? $this->width : $this->height; // Define maximum length of complete mark
		$char_widthmax = intval(($scale / $marklength) - 0.5); // Maximum character length in watermark

		for ($size = 5; $size >= 1; $size--) {
			$box_w = imagefontwidth($size);
			$box_h = imagefontheight($size);
			$box_spacer_w = 0;
			$box_spacer_h = 0;

			if ($this->orientation == "H") {
				$box_h *= 2;
				$box_w *= 1.75;
				$box_w *= $marklength;
				$box_w += intval($this->width * 0.05);
				$box_spacer_w = intval($this->width * 0.05);
				$box_spacer_h = intval($this->height * 0.01);
			} else {
				$box_w *= 3;
				$box_h *= 1.1;
				$box_h *= $marklength;
				$box_spacer_h = intval($this->height * 0.05);
				$box_spacer_w = intval($this->width * 0.01);
			}
			
			$box_scale = ($this->orientation == "H") ? $box_w + $box_spacer_w : $box_h + $box_spacer_h;

			if ($box_scale < $scale && $box_scale > $act_scale) { $act_font = $size; $act_scale = $box_scale; }
		}

		return array(	
				"fontsize"	=> $act_font,
				"box_w"		=> $box_w,
				"box_h"		=> $box_h,
				"spacer_w"	=> $box_spacer_w,
				"spacer_h"	=> $box_spacer_h
			);
	}

	protected function _getImageSizes($res) {
		// Check if the overlay image is bigger than the main image
		
		if (@imagesx($res) > $this->width || @imagesy($res) > $this->height) {
			// Need to resize the overlay image
			$box_h = $box_w = 0;
			$box_spacer_h = $box_spacer_w = 0;
			if (imagesx($res) > imagesy($res)) {
				$box_w = $this->width;
				$box_h = intval((imagesy($res) / (imagesx($res) / $this->width)) + 0.5);
				$box_spacer_h = intval(($this->height - $box_h) / 2);
			} else {
				$box_h = $this->height;
				$box_w = intval((imagesx($res) / (imagesy($res) / $this->height)) + 0.5);
				$box_spacer_w = intval(($this->width - $box_w) / 2);
			}
		} else {
			$box_spacer_h = $box_spacer_w = 0;
			$box_h = imagesy($res);
			$box_w = imagesx($res);
		}
		return array(
				"box_w"		=> $box_w,
				"box_h"		=> $box_h,
				"spacer_w"	=> $box_spacer_w,
				"spacer_h"	=> $box_spacer_h
			);
	}
		

	protected function _getChunk() {
		$chunk = imagecreatetruecolor($this->sizes["box_w"], $this->sizes["box_h"]);
		imagecopy(	
				$chunk,
				$this->image, 
				0, 
				0,
				$this->offset_x,
				$this->offset_y,
				$this->sizes["box_w"],
				$this->sizes["box_h"]
		);
		return $chunk;
	}

	protected function _createEmptyWatermark() {
		return imagecreatetruecolor($this->sizes["box_w"], $this->sizes["box_h"]);
		//return imagecreate($this->sizes["box_w"], $this->sizes["box_h"]);
	}

	protected function _addTextWatermark($mark, $img_mark, $chunk) {
		imagetruecolortopalette($chunk, true, 65535);
		$text_color = array("r" => 0, "g" => 0, "b" => 0);

		if (is_array($this->fixedColor)) {
			$text_color = $this->fixedColor;
		} else {
			// Search color for overlay text
			for($x = 0; $x <= $this->sizes["box_w"]; $x++) {
				for ($y = 0; $y <= $this->sizes["box_h"]; $y++) { 
					$colors = imagecolorsforindex($chunk, imagecolorat($chunk, $x, $y));
					$text_color["r"] += $colors["red"];
					$text_color["r"] /= 2;
					$text_color["g"] += $colors["green"];
					$text_color["g"] /= 2;
					$text_color["b"] += $colors["blue"];
					$text_color["b"] /= 2;
				}
			}
			$text_color["r"] = $text_color["r"] < 128 ? $text_color["r"] + 128 : $text_color["r"] - 128;
			$text_color["g"] = $text_color["g"] < 128 ? $text_color["g"] + 128 : $text_color["g"] - 128;
			$text_color["r"] = $text_color["r"] < 128 ? $text_color["r"] + 128 : $text_color["r"] - 128;
		}
		// Choose transparent color for watermark
		$mark_bg = imagecolorallocate(	$img_mark,
						($text_color["r"] > 128 ? 10 : 240),
						($text_color["g"] > 128 ? 10 : 240),
						($text_color["b"] > 128 ? 10 : 240));

		// Choose text color for watermark
		$mark_col = imagecolorallocate($img_mark, $text_color["r"], $text_color["g"], $text_color["b"]);

		// Fill watermark with transparent color
		imagefill($img_mark, 0, 0, $mark_bg);
		imagecolortransparent($img_mark, $mark_bg);

		// Add text to watermark
		if ($this->orientation == "H") {
			imagestring($img_mark, $this->sizes["fontsize"], 1, 0, $mark, $mark_col); 
		} else {
			imagestringup($img_mark, $this->sizes["fontsize"], 0, $this->sizes["box_h"] - 5, $mark, $mark_col);
		}

		return $img_mark;
	}

	protected function _addImageWatermark($mark, $img_mark) {
		$transparent_color_idx = imagecolortransparent($mark);
		if ($transparent_color_idx >= 0) $transparent_color = imagecolorsforindex($mark, imagecolortransparent($mark));
		imagecopy($img_mark, $mark, 0, 0, 0, 0, imagesx($mark), imagesy($mark));
		if ($transparent_color_idx >= 0) {
			$trans;
			if (function_exists("imagecolorallocatealpha")) {
				$trans = imagecolorallocatealpha(
					$img_mark,
					$transparent_color["red"],
					$transparent_color["green"],
					$transparent_color["blue"],
					127
				);
			} else {
				$trans = imagecolorallocate(
					$img_mark,
					$transparent_color["red"],
					$transparent_color["green"],
					$transparent_color["blue"]
				);
			}
			imagecolortransparent($img_mark, $trans);
		}
	
		return $img_mark;
	}
	
	protected function _createMarkedImage($img_mark, $type, $pct) {
		// Create marked image (original + watermark)
		$this->marked_image = imagecreatetruecolor($this->width, $this->height);
		imagecopy($this->marked_image, $this->image, 0, 0, 0, 0, $this->width, $this->height);
		if ($type == 'TEXT') {
			imagecopymerge(
				$this->marked_image,
				$img_mark,
				$this->offset_x,
				$this->offset_y,
				0,
				0,
				$this->sizes["box_w"],
				$this->sizes["box_h"],
				$pct
			);
			$this->imageCreated = true;
		} elseif ($type == 'IMAGE') {
			if ($this->gd_version >= 2) { // GD2: Should be the easy way
				imagealphablending($this->marked_image, true);
				
				imagecopy(
					$this->marked_image,
					$img_mark,
					$this->offset_x,
					$this->offset_y,
					0,
					0,
					$this->sizes["box_w"],
					$this->sizes["box_h"]
				);
			} else {
				imagecopymerge(
					$this->marked_image,
					$img_mark,
					$this->offset_x,
					$this->offset_y,
					0,
					0,
					$this->sizes["box_w"],
					$this->sizes["box_h"],
					$pct
				);
			}

			$this->imageCreated = true;
		}
	}

	protected function _getOffsets() {
		$width_mark  = $this->sizes["box_w"] + $this->sizes["spacer_w"];
		$height_mark = $this->sizes["box_h"] + $this->sizes["spacer_h"];
		$width_left  = $this->width - $width_mark;
		$height_left = $this->height - $height_mark; 
	
		switch ($this->position) {
			case "TL": // Top Left
				$this->offset_x = $width_left >= 5 ? 5 : $width_left;
				$this->offset_y = $height_left >= 5 ? 5 : $height_left;
				break;
			case "TM": // Top middle 
				$this->offset_x = intval(($this->width - $width_mark) / 2);
				$this->offset_y = $height_left >= 5 ? 5 : $height_left;
				break;
			case "TR": // Top right
				$this->offset_x = $this->width - $width_mark;
				$this->offset_y = $height_left >= 5 ? 5 : $height_left;
				break;
			case "CL": // Center left
				$this->offset_x = $width_left >= 5 ? 5 : $width_left;
				$this->offset_y = intval(($this->height - $height_mark) / 2);
				break;
			default:
			case "C": // Center (the default)
				$this->offset_x = intval(($this->width - $width_mark) / 2);
				$this->offset_y = intval(($this->height - $height_mark) / 2);
				break;
			case "CR": // Center right
				$this->offset_x = $this->width - $width_mark;
				$this->offset_y = intval(($this->height - $height_mark) / 2);
				break;
			case "BL": // Bottom left
				$this->offset_x = $width_left >= 5 ? 5 : $width_left;
				$this->offset_y = $this->height - $height_mark;
				break;
			case "BM": // Bottom middle
				$this->offset_x = intval(($this->width - $width_mark) / 2);
				$this->offset_y = $this->height - $height_mark;
				break;
			case "BR": // Bottom right
				$this->offset_x = $this->width - $width_mark;
				$this->offset_y = $this->height - $height_mark;
				break;
		}
	}
	/**
	 * Takes a path to an image or a php image resource as the only argument
	 * Returns image type and the appropriate image resource
	 * 
	 * @param  object $res
	 * @return array
	 */ 
	protected function _getImage($res) {
		if (intval(@imagesx($res)) > 0) {
			$img = $res;
		} else {
			$imginfo = getimagesize($res);

			switch($imginfo[2]) { // Determine type
				case 1:
					$type = "GIF";
					if (function_exists("imagecreatefromgif")) {
						$img = imagecreatefromgif($res);
					} else {
						die("Unsupported image type: $type");
					}
					break;
				case 2:
					$type = "JPG";
					if (function_exists("imagecreatefromjpeg")) {
						$img = imagecreatefromjpeg($res);
					} else {
						die("Unsupported image type: $type");
					}
					break;
				case 3:
					$type = "PNG";
					if (function_exists("imagecreatefrompng")) {
						$img = imagecreatefrompng($res);
					} else {
						die("Unsupported image type: $type");
					}
					break;
			}
		}

		return array($type, $img);
	}
}


?>
