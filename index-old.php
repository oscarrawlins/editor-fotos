<?php

die('Ok!');

set_time_limit(0);

error_reporting(0);
ini_set('display_errors', 0);

if(!empty($_FILES)){
	
	if(!empty($_POST['size'])){
	    $size = trim($_POST['size']);
    }
	
	if(!empty($_FILES['imagenes']['tmp_name'])){
		$imagenes = $_FILES['imagenes']['tmp_name'];
	}
	
	if(!empty($imagenes)){
		
		/* Eliminar zip */
		@unlink('imagenes.zip');
		
		/* Crear nuevo zip */
		$zip = new ZipArchive;
        $zip_resp = $zip->open('imagenes.zip', ZipArchive::CREATE);
		
	    foreach($imagenes as $imagen){
			if(!empty($imagen)){
				
				/* Crear canvas */
				$canvas = imagecreatetruecolor(600, 799);
				imagesavealpha($canvas, true);
				
				$alpha = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
				imagefill($canvas, 0, 0, $alpha);
                
				/* Agregan imagen */
				$get_imagen = file_get_contents($imagen);
				if(!empty($get_imagen)){
					
					$str_imagen = imagecreatefromstring($get_imagen);
					imagecopyresampled($canvas, $str_imagen, 0, 0, 0, 0, 600, 799, 600, 799);
					
					$size_png = imagecreatefrompng('img/'.$size.'.png');
					imagecopyresampled($canvas, $size_png, 0, 0, 0, 0, 600, 799, 600, 799);
					
					ob_start();
					
					imagepng($canvas, null);
					imagedestroy($canvas);
					
					if($zip_resp === true){
						$zip->addFromString(uniqid().'.png', ob_get_clean());
					}
					
				}
				
			}
		}
		
		$zip->close();
		
	}
}

?>

<!doctype html>
<html lang="es" translate="no">
<head>
	<meta charset="UTF-8">
	<meta name="google" content="notranslate">
	<meta name="viewport" content="width=device-width, initial-scale=1.0,
	maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
	
	<title>Annyis Fashion</title>
	
	<link rel="stylesheet" href="<?php echo 'css/style.css?v='.time(); ?>">
	
</head>
<body>
	
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-brand">
			<strong>Annyis Fashion</strong>
		</div>
    </div>
</div>
	
<div class="container">

    <div class="panel panel-default">
	    <div class="panel-heading text-center">
			<strong>Generados de Imagenes</strong>
		</div>
		<div class="panel-body">
	  
			<form enctype="multipart/form-data" method="post">
			
				<div class="form-group">
					<label class="control-label">Selecciona el size:</label>
					<select name="size" class="form-control input-lg">
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
					<input type="file" name="imagenes[]" class="form-control input-lg" 
					placeholder="Imagenes..." multiple>
				</div>
				
				<button class="btn btn-block btn-lg btn-primary">
					<strong>GENERAR IMAGENES</strong>
				</button>
			</form>
			
			<hr>
			
			<?php if(!empty($zip_resp) && $zip_resp === true): ?>
			<a href="imagenes.zip" download="imagenes.zip" class="btn btn-lg btn-block btn-success">
				<strong>DESCARGAR IMAGENES</strong>
			</a>
			<?php endif; ?>
			
		</div>
	</div>
	
</div>

</body>
</html>