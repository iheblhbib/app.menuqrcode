<?php

    include('../config.menuqrcode/config.php');
    
    include "config/phpqrcode/qrlib.php"; 
 
    $PNG_TEMP_DIR = 'archive_qrcode/';
 
    $PNG_WEB_DIR = '../app.menuqrcode.tn/archive_qrcode/';
      
    $object = new application();

include_once(__DIR__ . '/securite.php');
securite_exiger_connexion($object);
    
    if($_SESSION["id"]){
        
        $error = '';
        
        $success = '';
  
		$logo = '';
		if($_FILES["logo"]["name"] != '')
		{
			$logo = upload_logo();
		}
		else
		{
			$logo = '../app.menuqrcode.tn/img/1905184577.png';
		}
      	
        $data = array(
            ':gestionnaire'         =>	$object->clean_input($_SESSION["id"]),
            ':market'               =>	$object->clean_input($_POST["name"]),           
            ':logo'                 =>  $logo,
            ':address'              =>	($object->clean_input($_POST["address"])) ? $object->clean_input($_POST["address"]) : null, 
            ':theme'                =>	 ($_POST["niveau"] == '2') ? '19': '13' , 
            ':niveau'               =>  $object->clean_input($_POST["niveau"]),           
            ':tel'                  =>  ($object->clean_input($_POST["tel"])) ? $object->clean_input($_POST["tel"]) : null,
            ':email'                =>  ($object->clean_input($_POST["email"])) ? $object->clean_input($_POST["email"]) : null,
            ':background'           => '../app.menuqrcode.tn/img/904258097.webp',
            ':background_color0'    => '#cda45e',  
            ':background_color1'    => '#0a3955',  
            ':background_color2'    => '#cda45e', 
            ':background_color3'    => '#ffffff', 
            ':background_color4'    => '#0a3955', 
            ':text_color0'          => '#ffffff',
            ':text_color1'          => '#ffffff',
            ':text_color2'          => '#000000', 
            ':text_color3'          => '#0a3955', 
            ':text_color4'          => '#ffffff',      
            ':multi_langue'         =>  ($object->clean_input($_POST["multi_langue"])) ,                             
            ':wifi'                 =>  ($object->clean_input($_POST["wifi"])) ,                             
            ':feedback'             =>  ($object->clean_input($_POST["feedback"])) ,            
            ':event'                =>  ($object->clean_input($_POST["event"])) ,            
            ':design_qrcode'        =>  ($object->clean_input($_POST["design_qrcode"])) ,  
    	);
            
        $object->query = " INSERT INTO market(
                                    gestionnaire,
                                    market, 
                                    logo,
                                    address, 
                                    theme,
                                    niveau, 
                                    tel,
                                    email,
                                    background,
                                    background_color0,
                                    background_color1,
                                    background_color2,
                                    background_color3,
                                    background_color4,
                                    text_color0,
                                    text_color1,
                                    text_color2,
                                    text_color3,
                                    text_color4 ,
                                    multi_langue, 
                                    wifi, 
                                    feedback,
                                    event,
                                    design_qrcode 
                                )
                                VALUES(
                                    :gestionnaire,
                                    :market, 
                                    :logo,
                                    :address, 
                                    :theme,
                                    :niveau, 
                                    :tel,
                                    :email,
                                    :background,
                                    :background_color0,
                                    :background_color1,
                                    :background_color2,
                                    :background_color3,
                                    :background_color4,
                                    :text_color0,
                                    :text_color1,
                                    :text_color2,
                                    :text_color3,
                                    :text_color4, 
                                    :multi_langue, 
                                    :wifi, 
                                    :feedback,
                                    :event,
                                    :design_qrcode 
                                )";
	
		$object->execute($data); 
		
		$market = $object->connect->lastInsertId();
		
        if ( $market > 0) {
            $success = 'Point de vente Ajouté';
            
    		$object->query="INSERT INTO assoc_moder (market,user)
                        		SELECT s.id , u.id from market s , user u where u.type = 'moderateur'
                        		AND u.id NOT IN (Select user 
                        								FROM assoc_moder
                        								WHERE market =s.id )";
    		$object->execute();
    		
			$object->query="INSERT INTO market_langue (market,langue)
                        		SELECT m.id , l.id from market m , langue l where l.etat = '0'
                        		AND l.id NOT IN (Select langue 
                        								FROM market_langue
                        								WHERE market = m.id )";
        	$object->execute();
    		
			$object->query="INSERT INTO qrcodes (market) VALUES ('".$market."') ";
			
        	$object->execute();
        	
			//*************************************************************
		
    		// creation de qr code du market
    		
    		//************************************************************* 
    		
    		$filename = 'qrcode.png';
    
            $errorCorrectionLevel = 'L';
            
            $matrixPointSize = 10;
 
            $link = $object->menu_url.'qrcode?data='.$object->encrypt($market);
            
            $filename = $PNG_TEMP_DIR.'qrcode'.md5($link.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
            
            QRcode::png($link, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
            
            $object->query = "UPDATE market SET qrcode= '".$PNG_WEB_DIR.basename($filename)."'   WHERE id =  '".$market."' ";
            
            $object->execute();
        
		
        }else{
            $error = '<div class="alert alert-danger">Erreur d\'insertion</div>'; 
        } 
	 

        $output = array(
            'error' => $error,
            'success' => $success,  
            '$data' => $data,  
        );

        echo json_encode($output);
        
    }    
    
function upload_logo()
{
    if(isset($_FILES["logo"]))
    {
        $filename = $_FILES['logo']['name'];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $new_name = rand() . '.' . $extension;
        $destination = '../app.menuqrcode.tn/img/' . $new_name;
        $source_path = $_FILES['logo']['tmp_name'];

        // Set the desired maximum width and height
        $max_width = 800;
        $max_height = 600;

        // Get the original image dimensions
        list($width, $height) = getimagesize($source_path);

        // Calculate the proportional scaling factor
        $scale = min($max_width / $width, $max_height / $height);

        // Calculate the new dimensions
        $new_width = $width * $scale;
        $new_height = $height * $scale;

        // Create a new image with the resized dimensions and a transparent background
        $resized_image = imagecreatetruecolor($new_width, $new_height);
        imagesavealpha($resized_image, true);
        $transparent_color = imagecolorallocatealpha($resized_image, 0, 0, 0, 127);
        imagefill($resized_image, 0, 0, $transparent_color);

        // Load the original image based on its extension
        if ($extension === 'jpg' || $extension === 'jpeg') {
            $source_image = imagecreatefromjpeg($source_path);
        } elseif ($extension === 'png') {
            $source_image = imagecreatefrompng($source_path);
        } elseif ($extension === 'webp') {
            $source_image = imagecreatefromwebp($source_path);
        } else {
            // Unsupported image format
            return false;
        }

        // Enable alpha blending for transparent images
        imagealphablending($resized_image, true);
        imagesavealpha($resized_image, true);

        // Resize the image
        imagecopyresampled($resized_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Save the resized image based on its extension
        if ($extension === 'jpg' || $extension === 'jpeg') {
            imagejpeg($resized_image, $destination);
        } elseif ($extension === 'png') {
            imagepng($resized_image, $destination);
        } elseif ($extension === 'webp') {
            imagewebp($resized_image, $destination);
        }

        // Free up memory
        imagedestroy($resized_image);
        imagedestroy($source_image);

        return $destination;
    }
}