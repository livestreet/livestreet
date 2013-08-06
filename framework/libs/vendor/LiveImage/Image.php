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
 * LiveImage:
 * Main functions for resize, crop and stylize your picture.
 *
 * @author  Alex Kachayev   
 * @version 1.2
 * @package LiveImage
 */

class LiveImage {
	/**
	 * Image object handler
	 *
	 * @var object
	 */
	protected $image=null;
	/**
	 * @var bool
	 */
	protected $truecolor=true;
	/**
	 * @var int
	 */
	protected $width=0;
	/**
	 * @var int
	 */
	protected $height=0;
	/**
	 * Color param (RGB code)
	 * 
	 * @var array
	 */
	protected $color=array('r'=>255,'g'=>255,'b'=>255); 
	/**
	 * Pixel font size
	 * 
	 * @var int
	 */	
	protected $font_size=20;
	/**
	 * Font name for making image labels.
	 * For saving true type fonts use /font directory.
	 * 
	 * @var string
	 */
	protected $font='';
	/**
	 * Resizing scale
	 * 
	 * @var int
	 */
	protected $scale = 1;
	/**
	 * Format of image file\object
	 *
	 * @var string
	 */
	protected $format='';
	/**
	 * Quality of output JPG image
	 * 
	 * @var int
	 */
	protected $jpg_quality = 99;
	/**
	 * Error texts
	 *
	 * @var array
	 */
	protected $error_messages = array(
		1  => 'Can`t create image',
		2  => 'No font was given',
		3  => 'No file was given',
		4  => 'Can`t open image from file',
		5  => 'Unknown file format given',
		6  => 'Failed image resource given'
	);
	/**
	 * Last error text
	 *
	 * @var strng
	 */
	protected $last_err_text='';
	/**
	 * Last error code
	 *
	 * @var int
	 */
	protected $last_err_num=0;

	/**
	 * Создает объект изображения из переданного файла
	 *
	 * @param  string $file
	 * @return bool
	 */
	public function __construct($file) {
		if(!$file || !($size=getimagesize($file))) {
			$this->set_last_error(3);
			return false;
		}
		/**
		 * Определяем тип файла изображения
		 */
		switch ($size['mime']) {
			case 'image/png':
			case "image/x-png":			
				$tmp=imagecreatefrompng($file);
				$this->format='png';
				break;
			case 'image/gif':
				$tmp=imagecreatefromgif($file);
				$this->format='gif';
				break;
		    case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$tmp=imagecreatefromjpeg($file);
				$this->format='jpg';
				break;
			default:
				$this->set_last_error(5);				
				return false;
		}		
		/**
		 * Если изображение не удалось создать
		 */
		if(!$tmp){
			$this->set_last_error(4);
			return false;
		}

		$this->image=$tmp;
		$this->width=$size[0];
		$this->height=$size[1];
		$this->truecolor=true;
		
		return true;
	}

	/**
	 * Resize handle image
	 *
	 * @param  int   $width
	 * @param  int   $height
	 * @param  int   $src_resize
	 * @param  int   $scale
	 * @return mixed
	 */
	public function resize($width=null,$height=null,$scale=false,$alfa=true) {
		$this->clear_error();
		/**
		 * Если не указана новая высота, значит применяем масштабирование.
		 * Если не указана ширина, то "забираем" ширину исходного.
		 */
		$height=(!$height)?1:$height;
		$width=(!$width)?$this->width:$width;
		
		if( $scale ){
			$scale_x = $this->width / $width;
			$scale_y = $this->height / $height;
			$this->scale = min($scale_x, $scale_y);
						
			$width  = round($this->width / $this->scale);
			$height = round($this->height / $this->scale);
		}

		$tmp=($this->truecolor)
			? imagecreatetruecolor($width,$height)
			: imagecreate($width,$height);
		/**
		 * Если темп-изображение не создано, ставим отметку об ошикбе
		 */
		if(!$tmp) {
			$this->set_last_error(1);
			return false;
		}
									
		if($this->format=='gif') { 
			imagealphablending($this->image, false);
			$ct = @imagecolortransparent($this->image);
			$color_tran = @imagecolorsforindex($this->image, $ct);
		
			if($color_tran) {
				$ct2 = imagecolorexact($tmp, $color_tran['red'], $color_tran['green'], $color_tran['blue']);
				imagefill($tmp,0,0,$ct2);
			}

	
			/**
			 * Определяем функцию, которой будет выполнен ресайз изображения
			 */
			$sResizeFunction = 'imagecopyresampled';
			if(!function_exists($sResizeFunction)) $sResizeFunction = 'imagecopyresized';
			if(isset($ct) and $ct!=-1) $sResizeFunction = 'imagecopyresized';
							 				
			if(!@$sResizeFunction($tmp,$this->image,0,0,0,0,$width,$height,$this->width,$this->height)) {
				imagedestroy($tmp);
				return false;
			}
			
		 	imagesavealpha($tmp, true);
			if(isset($ct2)) imagecolortransparent($tmp, $ct2);
		} else {
			/**
		     * Регулируем альфа-канал, если не указано обработное
		     */
			if($alfa) {
				@imagesavealpha($tmp,true);
				@imagealphablending($tmp,false);
			}
			
			if(!@imagecopyresampled($tmp,$this->image,0,0,0,0,$width,$height,$this->width,$this->height)) {
				imagedestroy($tmp);
				return false;
			}		
		}
		
		imagedestroy($this->image);
		$this->set_image($tmp);
		
		return true;
	}

	/**
	 * Crop image
	 *
	 * @param  int   $width
	 * @param  int   $height
	 * @param  int   $start_width
	 * @param  int   $start_height
	 * @return mixed
	 */
	public function crop($width, $height, $start_width, $start_height) {	
		$tmp=($this->truecolor)
			? imagecreatetruecolor($width,$height)
			: imagecreate($width,$height);
		/**
		 * Если темп-изображение не создано, ставим отметку об ошикбе
		 */
		if(!$tmp) {
			$this->set_last_error(1);
			return false;
		}
									
		if($this->format=='gif') { 
			imagealphablending($this->image, false);
			$ct = @imagecolortransparent($this->image);
			$color_tran = @imagecolorsforindex($this->image, $ct);
		
			if($color_tran) {
				$ct2 = imagecolorexact($tmp, $color_tran['red'], $color_tran['green'], $color_tran['blue']);
				imagefill($tmp,0,0,$ct2);
			}

	
			/**
			 * Определяем функцию, которой будет выполнен ресайз изображения
			 */
			$sResizeFunction = 'imagecopyresampled';
			if(!function_exists($sResizeFunction)) $sResizeFunction = 'imagecopyresized';
			if(isset($ct) and $ct!=-1) $sResizeFunction = 'imagecopyresized';
							 				
			if(!@$sResizeFunction($tmp,$this->image,0,0,$start_width,$start_height,$width,$height,$width,$height)) {
				imagedestroy($tmp);
				return false;
			}
			
		 	imagesavealpha($tmp, true);
			if(isset($ct2)) imagecolortransparent($tmp, $ct2);
		} else {
			@imagesavealpha($tmp,true);
			@imagealphablending($tmp,false);
	
			if(!imagecopyresampled($tmp,$this->image,0,0,$start_width,$start_height,$width,$height,$width,$height)) {
				imagedestroy($tmp);
				return false;
			}
		}

		imagedestroy($this->image);
		$this->set_image($tmp);

		return true;		
	}
	
	/**
	 * Return image object
	 *
	 * @return mixed
	 */
	public function get_image() {
		return $this->image;
	}
	/**
	 * Add new image object to current handler
	 *
	 * @param  resource $image_res
	 * @return bool
	 * 
	 * @todo   Find format of given image
	 */
	public function set_image($image_res) {
		if (intval(@imagesx($image_res)) > 0) {
			$this->image=$image_res;
			$this->width=imagesx($image_res);
			$this->height=imagesy($image_res);
			return true;		
		}
		
		$this->set_last_error(6);
		return false;
	}
	
	/**
	 * Return image params
	 *
	 * @param  string $key
	 * @return array
	 */
	public function get_image_params($key=null) {
		$params=array(
			'width'     => $this->width, 
			'height'    => $this->height, 
			'truecolor' => $this->truecolor, 
			'format'    => $this->format
		);
		if(is_null($key)) {
			return $params;
		}
		
		if(array_key_exists($key,$params)){
			return $params[$key];
		}
		
		return false;
	}

	/**
	 * Setter for font params
	 *
	 * @param string $font_size
	 * @param int    $font_angle
	 * @param string $name
	 */
	public function set_font($font_size=20,$font_angle=0,$name='') {
		if($name) {
			$this->font=$name;
		}

		$this->font_size=$font_size;
		$this->font_angle=$font_angle;
	}

	/**
	 * Setter for color
	 *
	 * @param  int  $r
	 * @param  int  $g
	 * @param  int  $b
	 * @param  bool $transparency
	 * 
	 * @return mixed	 
	 */
	public function set_color($r=255,$g=255,$b=255,$transparency=false) {
		$this->color=array('r'=>$r,'g'=>$g,'b'=>$b);

		if(!$transparency) {
	 		$this->color['locate']=imagecolorallocate($this->image,$this->color['r'],$this->color['g'],$this->color['b']);
		} else {
			$this->color['locate']=imagecolorallocatealpha($this->image,$this->color['r'],$this->color['g'],$this->color['b'],$transparency);			
		}

		return $this->color['locate'];
	}

	/**
	 * Set JPG output quality
	 *
	 * @param  int $quality
	 * @return null
	 */
	public function set_jpg_quality($quality=null) {
		$this->jpg_quality = $quality;
	}
	
	/**
	 * Make true type font text label on image
	 *
	 * @param  string $text
	 * @param  int    $x
	 * @param  int    $y
	 * @param  bool   $unicode
	 * @param  int    $letter_space
	 * @return bool
	 */
	public function ttf_text($text,$x=0,$y=0,$unicode=false,$letter_space=20) {
		$this->clear_error();

		if(!$this->font) {
			$this->set_last_error(2);
			return false;
		}

		if($unicode) {
			$text=$this->to_unicode($text);				
		}
		return imagettftext($this->image,$this->font_size,$this->font_angle,$x,$y,$this->color['locate'],$this->font,$text);
	}

	/**
	 * Create text watermark
	 *
	 * @param  string $text
	 * @param  array  $position
	 * @param  array  $font_color
	 * @param  array  $bg_color
	 * @param  int    $font_alpha
	 * @param  int    $bg_alfa
	 * @return bool
	 */
	public function watermark($text, $position=array(0,24), $font_color=array(255, 255, 255), $bg_color=array(0,0,0), $font_alpha=0, $bg_alfa=40 ){
		$text = " ".$text." ";
		list($r_font, $g_font, $b_font) = $font_color;
		list($r_bg, $g_bg, $b_bg) = $bg_color;
		list($x, $y) = $position;

		/// Вычисляем размер надписи
		/// Наносим фон надписи согласно расчетам размера и позиции
		$box  = imagettfbbox($this->font_size, 0, $this->font, $text);
		/// Производим замену отрицательных кодов в позиции
		/// и кодов вида 1/2 - центрирование относительно оси
		if(substr_count($x, '-')==1) {
			$x = $this->width-abs($box[4])-10-substr_replace($x, '', 0, 1);
		} elseif($x=='1/2') {
			$x=round(($this->width-abs($box[4]))/2)-5;
		}
		if(substr_count($y, '-')==1) {
			$y = $this->height-abs($box[5])-10-substr_replace($y, '', 0, 1);
		} elseif($y=='1/2') {
			$y=round(($this->height-abs($box[5]))/2)-5;
		}
		/// Наносим фон для будущей надписи
		$this->set_color($r_bg, $g_bg, $b_bg, $bg_alfa);
		imagefilledrectangle($this->image,$x,$y,$x+abs($box[4])+10,$y+abs($box[5])+10,$this->color['locate']);

		/// Наносим надпись водянного знака
		$this->set_color($r_font, $g_font, $b_font, $font_alpha);
		imagettftext($this->image, $this->font_size, 0, $x+5, $y+abs($box[5])+5, $this->color['locate'], $this->font, $text);
		return true;
	}

	/**
	 * Make rounded corners
	 *
	 * @param  int  $radius
	 * @param  int  $rate
	 * @return bool
	 */
	public function round_corners($radius=5, $rate=5) {
		imagealphablending($this->image, false);
		imagesavealpha($this->image, true);

		$rs_radius = $radius * $rate;
		$rs_size = $rs_radius * 2;

		$corner = imagecreatetruecolor($rs_size, $rs_size);
		imagealphablending($corner, false);

		$trans = imagecolorallocatealpha($corner, 255, 255, 255, 0);
		imagefill($corner, 0, 0, $trans);

		$positions = array(
			array(0, 0, 0, 0),
			array($rs_radius, 0, $this->width - $radius, 0),
			array($rs_radius, $rs_radius, $this->width - $radius, $this->height - $radius),
			array(0, $rs_radius, 0, $this->height - $radius),
		);

		foreach ($positions as $pos) {
			imagecopyresampled($corner, $this->image, $pos[0], $pos[1], $pos[2], $pos[3], $rs_radius, $rs_radius, $radius, $radius);
		}

		$lx = $ly = 0;
		$i = -$rs_radius;
		$y2 = -$i;
		$r_2 = $rs_radius * $rs_radius;

		for (; $i <= $y2; $i++) {
			$y = $i;
			$x = sqrt($r_2 - $y * $y);

			$y += $rs_radius;
			$x += $rs_radius;

			imageline($corner, $x, $y, $rs_size, $y, $trans);
			imageline($corner, 0, $y, $rs_size - $x, $y, $trans);

			$lx = $x;
			$ly = $y;
		}

		foreach ($positions as $i => $pos) {
			imagecopyresampled($this->image, $corner, $pos[2], $pos[3], $pos[0], $pos[1], $radius, $radius, $rs_radius, $rs_radius);
		}
		imagedestroy($corner);
		return true;
	}

	/**
	 * Make image output in file or in browser.
	 * Can output image in one of this formats: png, gif, jpg.
	 * If you don`t give format, it will use 
	 * the format of image object.
	 *
	 * @param string $format
	 * @param string $file
	 */
	public function output($format=null,$file=null) {
		/**
		 * Если формат не указан, значит сохраняем формат исходного объекта
		 */
		if(is_null($format)) {
			$format=$this->format;
		}
		/**
		 * Производим преобразование и отдаем результат
		 */
		switch($format) {
			default:
			case 'png':
				@imagesavealpha($this->image,true);
				if(!$file) {
					header("Content-type: image/png");
					imagepng($this->image);
				} else {
					imagepng($this->image,$file);
				}
				break;
			
			case 'jpg':
				if(!$file) {
					header("Content-type: image/jpeg");
					imagejpeg($this->image);
				} else {
					imagejpeg($this->image,$file,$this->jpg_quality);
				}
				break;
			
			case 'gif':
				if(!$file) {
					header("Content-type: image/gif");
					imagegif($this->image);
				} else {
					imagegif($this->image,$file);
				}
				break;
		}

	}

	public function paste_image($file,$copyresized=false,$position=array(0,0),$src_x=0,$src_y=0,$src_w=-1,$src_h=-1,$dst_w=-1,$dst_h=-1) {
		$this->clear_error();

		if(!$file || !($size=getimagesize($file))) {
			$this->set_last_error(3);
			return false;
		}

		/**
		 * Определяем тип файла изображения
		 */
		switch ($size['mime']) {
			case 'image/png':
			case "image/x-png":			
				$tmp=imagecreatefrompng($file);
				break;
			case 'image/gif':
				$tmp=imagecreatefromgif($file);
				break;
		    case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$tmp=imagecreatefromjpeg($file);
				break;
			default:
				$this->set_last_error(5);				
				return false;
		}				

		if(!$tmp) {
			$this->set_last_error(4);
			return false;
		}

		if($copyresized) {
			$dst_w = round(imagesx($tmp)/$this->scale);
			$dst_h = round(imagesy($tmp)/$this->scale);
		} 
		
		$dst_w=$dst_w<0 ? imagesx($tmp) : $dst_w;
		$dst_h=$dst_h<0 ? imagesy($tmp) : $dst_h;
		
		$src_w=$src_w<0 ? imagesx($tmp) : $src_w;
		$src_h=$src_h<0 ? imagesy($tmp) : $src_h;

		list($dst_x, $dst_y) = $position;

		/// Производим замену отрицательных кодов в позиции
		/// и кодов вида 1/2 - центрирование относительно оси
		if(substr_count($dst_x, '-')==1) {
			$dst_x = $this->width-$dst_w-substr_replace($dst_x, '', 0, 1);
		} elseif($dst_x=='1/2') {
			$dst_x=round(($this->width-$dst_w)/2);
		}
		if(substr_count($dst_y, '-')==1) {
			$dst_y = $this->height-$dst_h-substr_replace($dst_y, '', 0, 1);
		} elseif($dst_y=='1/2') {
			$dst_y=round(($this->height-$dst_h)/2);
		}

		if($copyresized) {
			$ret=imagecopyresampled($this->image,$tmp,$dst_x,$dst_y,$src_x,$src_y,$dst_w,$dst_h,$src_w,$src_h);
		} else {
			$ret=imagecopy($this->image,$tmp,$dst_x,$dst_y,$src_x,$src_y,$src_w,$src_h);
		}
		imagedestroy($tmp);
		return $ret;

	}

	public function rgb($r=255,$g=255,$b=255) {
		return imagecolorallocate($this->image,$r,$g,$b);
	}

	public function set_last_error($id) {
		$this->last_err_text = $this->error_messages[$id];
		$this->last_err_num  = $id;
	}

	public function get_last_error() {
		return empty($this->last_err_num) ? false : $this->last_err_text;
	}

	public function clear_error() {
		$this->last_err_text='';
		$this->last_err_num=0;
	}

	/**
	 * Convert string to unicode for making text label using true type font.
	 *
	 * @param  string $text
	 * @param  string $from
	 * @return string
	 */
	protected function to_unicode($text,$from='w') {
		$text=convert_cyr_string($text,$from,'i');
		$uni='';

		for($i=0, $len=strlen($text); $i<$len; $i++)
		{
			$char=$text{$i};
			$code=ord($char);
			$uni.=($code>175) ? "&#".(1040+($code-176)).";" : $char;
		}

		return $uni;
	}

	public function destroy_all() {
		if(imagedestroy($this->image))
		$this->image=null;

		return true;
	}
}
?>