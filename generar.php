<?php

set_time_limit(0);
error_reporting(0);

ini_set('display_errors', 0);

//http_response_code(404);

if(!empty($_POST) && !empty($_FILES)){
	
	if(!empty($_POST['size'])){
	    $size = trim($_POST['size']);
    }
	
	if(!empty($_FILES['imagenes']['tmp_name'])){
		$imagenes = $_FILES['imagenes']['tmp_name'];
	}
	
	if(!empty($size) && !empty($imagenes)){
		
		/* Eliminar zip */
		@unlink('imagenes.zip');
		
		/* Crear nuevo zip */
		$zip = new ZipArchive;
        $zip_open = $zip->open('imagenes.zip', ZipArchive::CREATE);
		
		foreach($imagenes as $imagen){
			if(!empty($imagen) && !empty($zip_open)){
				
				$file = @file_get_contents($imagen);
				$image = @imagecreatefromstring($file);

				if($image !== false){
					
					//$final_width = 900;
					//$final_height = 1280;
					
					$final_width = 1080;
					$final_height = 1920;
					
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

					//$new_x = (0 - ($new_width - $final_width) / 2);
					//$new_y = (0 - ($new_height - $final_height) / 2);
					
					$new_x = (int) round(0 - ($new_width - $final_width) / 2);
					$new_y = (int) round(0 - ($new_height - $final_height) / 2);
					
					/* Canvas */
					$canvas = @imagecreatetruecolor($final_width, $final_height);
					
					/* Original */
					@imagecopyresampled($canvas, $image, $new_x, $new_y, 
					0, 0, $new_width, $new_height, $width, $height);
					
					/* Marca de agua */
					$size_png = @imagecreatefrompng('sizes/'.$size.'.png');
					$size_png_x = imagesx($size_png);
					$size_png_y = imagesy($size_png);
					
					$size_png_h = ($final_height - $size_png_y);
					$size_png_w = ($final_width - $size_png_x);
					
					imagecopyresampled($canvas, $size_png, $size_png_w, $size_png_h, 
					0, 0, $size_png_x, $size_png_y, $size_png_x, $size_png_y);
					
					ob_start();
					
					imagepng($canvas, null, 0);
					
					imagedestroy($image);
					imagedestroy($canvas);
					imagedestroy($size_png);
					
					$zip_name = uniqid().'.png';
					$zip->addFromString($zip_name, ob_get_clean());
					
				}
				
			}
		}
		
		$zip->close();
		die('Ok!');
		
	}
	
}

?>