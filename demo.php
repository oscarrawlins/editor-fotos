<?php

die('Ok!');
set_time_limit(0);

error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: image/png');

/*

$get_imagen = file_get_contents('img/chica.jpg');

if(!empty($get_imagen)){
	
	$str_imagen = imagecreatefromstring($get_imagen);
	$str_imagen_height = imagesy($str_imagen);
	$str_imagen_width = imagesx($str_imagen);

	$size_png = imagecreatefrompng('img/SIZE-L.png');
	$size_png_height = ($str_imagen_height - imagesy($size_png));
	$size_png_width = ($str_imagen_width - imagesx($size_png));
	
	imagecopyresampled($str_imagen, $size_png, $size_png_width, $size_png_height, 0, 0, 
	imagesx($size_png), imagesy($size_png), imagesx($size_png), imagesy($size_png));
	
	imagepng($str_imagen, null, 0);
	imagedestroy($str_imagen);
	
}
*/

$file = file_get_contents('img/chica.jpg');
$image = @imagecreatefromstring($file);

if($image !== false){
	
	$final_width = 900;
	$final_height = 1280;
	
	$width = imagesx($image);
	$height = imagesy($image);
	
	$original_aspect = $width / $height;
	$thumb_aspect = $final_width / $final_height;

	if($original_aspect >= $thumb_aspect){
		
		$new_height = $final_height;
		$new_width = $width / ($height / $final_height);
		
	} else {
		
		$new_width = $final_width;
		$new_height = $height / ($width / $final_width);
		
	}

	$new_x = (0 - ($new_width - $final_width) / 2);
	$new_y = (0 - ($new_height - $final_height) / 2);
	
	$canvas = imagecreatetruecolor($final_width, $final_height);
	
	/* Original */
	imagecopyresampled($canvas, $image, $new_x, $new_y, 
	0, 0, $new_width, $new_height, $width, $height);
	
	/* Marca de agua */
	$size = imagecreatefrompng('sizes/L.png');
	$size_height = ($final_height - imagesy($size));
	$size_width = ($final_width - imagesx($size));
	
	imagecopyresampled($canvas, $size, 
	$size_width, $size_height, 0, 0, 200, 200, 200, 200);
	
	imagepng($canvas, null, 0);
	
	imagedestroy($image);
	imagedestroy($canvas);
	
}

?>