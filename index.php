<?php

/*
set_time_limit(0);
error_reporting(0);
ini_set('display_errors', 0);

if(!empty($_POST) && !empty($_FILES)){
	
	if(!empty($_POST['size'])){
	    $size = trim($_POST['size']);
    }
	
	if(!empty($_FILES['imagenes']['tmp_name'])){
		$imagenes = $_FILES['imagenes']['tmp_name'];
	}
	
	if(!empty($size) && !empty($imagenes)){
		
		@unlink('imagenes.zip');
		
		$zip = new ZipArchive;
        $zip_open = $zip->open('imagenes.zip', ZipArchive::CREATE);
		
		foreach($imagenes as $imagen){
			if(!empty($imagen) && !empty($zip_open)){
				
				$file = @file_get_contents($imagen);
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

					//$new_x = (0 - ($new_width - $final_width) / 2);
					//$new_y = (0 - ($new_height - $final_height) / 2);
					
					$new_x = (int) round(0 - ($new_width - $final_width) / 2);
					$new_y = (int) round(0 - ($new_height - $final_height) / 2);
					
					$canvas = @imagecreatetruecolor($final_width, $final_height);
					
					@imagecopyresampled($canvas, $image, $new_x, $new_y, 
					0, 0, $new_width, $new_height, $width, $height);
					
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
		
	}
	
}
*/

?>

<!doctype html>
<html lang="es" translate="no">
<head>

	<meta charset="UTF-8">
	<meta name="referrer" content="never"/>
	<meta name="robots" content="noindex, nofollow"/>
	
	<meta name="google" content="notranslate"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0,
	maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
	
	<title>Annyis Fashion</title>
	
	<link rel="stylesheet" href="<?php echo 'css/style.css?v='.time(); ?>">
	
</head>
<body>
	
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-brand">
			<img class="uh54l7qjy" src="img/logo-2.png">
		</div>
    </div>
</div>
	
<div class="container">

    <div class="panel panel-default">
	    <div class="panel-heading text-center">
			<strong>Generados de Imagenes</strong>
		</div>
		<div class="panel-body">
	  
			<form enctype="multipart/form-data" 
			method="post" id="formulario">
			
				<div class="form-group">
					<label class="control-label">Selecciona el size:</label>
					<select name="size" id="size" class="form-control input-lg" required>
					    <option value="NONE">NONE</option>
					    <option value="S">S</option>
					    <option value="M">M</option>
					    <option value="L">L</option>
					    <option value="X">X</option>
					    <option value="XL">XL</option>
					    <option value="1XL">1XL</option>
					    <option value="2XL">2XL</option>
					    <option value="3XL">3XL</option>
					    <option value="4XL">4XL</option>
					</select>
				</div>
				
				<div class="form-group">
					<label class="control-label">Selecciona la imagen o imagenes:</label>
					<input type="file" name="imagenes[]" class="form-control input-lg" id="imagenes" 
					placeholder="Imagenes..." multiple required>
				</div>
				
				<button class="btn btn-block btn-lg btn-primary" id="generar">
					<strong>GENERAR IMAGENES</strong>
				</button>
			</form>
			
			<hr>
			
			<a href="imagenes.zip" download="imagenes.zip" 
			class="btn btn-lg btn-block btn-success" id="descargar">
				<strong>DESCARGAR IMAGENES</strong>
			</a>
			
		</div>
	</div>
	
</div>

<script>

const max_files = 30;
const formulario = document.getElementById('formulario');

formulario.addEventListener('submit', function(e){
	
	e.preventDefault();
	
	const formData = new FormData(this);
    const xhr = new XMLHttpRequest();
	
	const size = document.getElementById('size').value;
	const imagenes = document.getElementById('imagenes').files;
	
	const generar = document.getElementById('generar');
	const descargar = document.getElementById('descargar');

    /*
	if(size === 'NONE'){
		
        alert('Debes seleccionar un size válido!');
		return;
		
    } else
	*/

	if(imagenes.length > max_files){
		
        alert('Solo puedes subir hasta '+max_files+' imagenes!');
        return;
		
    } else {
	
		generar.disabled = true;
		descargar.style.display = 'none';
		
		xhr.upload.addEventListener('progress', function(e){
			if(e.lengthComputable){
				
				const percent = Math.round((e.loaded / e.total) * 100);
				generar.textContent = percent+'% Subiendo imagenes...';
				
				if(percent >= 100){
					generar.textContent = 'Generando imagenes...';
				}
				
			}
		});

		xhr.onreadystatechange = function(){
			if(xhr.readyState === 4){
				
				if(xhr.status === 200){
					
					alert('Imagenes generadas correctamente!');
					descargar.style.display = 'block';
					
				} else {
					
					alert('Ocurrió un error en el envío.');
					window.location.reload();
					
				}
				
				generar.disabled = false; 
				generar.textContent = 'GENERAR IMAGENES';
				
			}
		};

		xhr.open('POST', 'generar.php');
		xhr.send(formData);
		
	}
	
});

</script>

</body>
</html>