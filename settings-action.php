<?php

    include('../config.menuqrcode/config.php'); 
      
    $object = new application();

include_once(__DIR__ . '/securite.php');
securite_exiger_connexion($object);

    if(isset($_POST["action"]))
    {
        if($_POST['action'] == 'update_market')
        { 
            $error = '';
    
    		$success = '';
    		
    		$logo = $_POST['hidden_logo'];
        	if($_FILES["logo"]["name"] != '')
        	{
        		$logo = upload_image();
        	}else{
    		    $logo    = str_replace('https://','../',$_POST["hidden_logo"]);  
    		}
    
    		$data = array(
                
                ':market'	    	=>	empty($object->clean_input($_POST["libelle"])) ? null : $object->clean_input($_POST["libelle"]) ,
                ':email'	    	=>	empty($object->clean_input($_POST["email"])) ? null : $object->clean_input($_POST["email"]) ,
                ':siteweb'	    	=>	empty($object->clean_input($_POST["siteweb"])) ? null : $object->clean_input($_POST["siteweb"]) ,
                ':address'	    	=>	empty($object->clean_input($_POST["address"])) ? null : $object->clean_input($_POST["address"]) ,
                ':logo'             =>  $logo , 
                ':tel'              =>	empty($object->clean_input($_POST["tel"])) ? null : $object->clean_input($_POST["tel"]) ,  
                ':description'      =>  empty($object->clean_input($_POST["description"])) ? null : $object->clean_input($_POST["description"]) ,
                ':display'          =>  ($_POST["display"] ==  'true' ) ?  '1' : '0' , 
                ':id'	            =>  $object->clean_input($_POST["market"]) , 
    		);
    
    		$object->query = "  UPDATE
                                    market
                                SET 
                                    market = :market,
                                    email = :email,
                                    siteweb = :siteweb,
                                    address = :address,
                                    logo = :logo,
                                    tel = :tel , 
                                    description = :description , 
                                    display = :display 
                                WHERE id = :id ";
    		
            $object->execute($data);
 
    		$success = 'Mis à jour avec succés';
    
    		$output = array(
    			'error'		=>	$error,
    			'success'	=>	$success,
    			'logo'      =>  str_replace('../','https://',$logo),
    			'data'      => $data,
    		);
    
    		echo json_encode($output); 
        }
        
        if($_POST['action'] == 'update_social')
        { 
            $error = '';
    
    		$success = ''; 
    		
    		$data = array( 
                ':facebook'	        =>  empty($object->clean_input($_POST["facebook"])) ? null : $object->clean_input($_POST["facebook"]) , 
                ':instagram'	    =>  empty($object->clean_input($_POST["instagram"])) ? null : $object->clean_input($_POST["instagram"]) , 
                ':tiktok'	        =>  empty($object->clean_input($_POST["tiktok"])) ? null : $object->clean_input($_POST["tiktok"]) , 
                ':linkedin'	        =>  empty($object->clean_input($_POST["linkedin"])) ? null : $object->clean_input($_POST["linkedin"]) , 
                ':youtube'	        =>  empty($object->clean_input($_POST["youtube"])) ? null : $object->clean_input($_POST["youtube"]) , 
                ':twitter'	        =>  empty($object->clean_input($_POST["twitter"])) ? null : $object->clean_input($_POST["twitter"]) , 
                ':whatsapp'	        =>  empty($object->clean_input($_POST["whatsapp"])) ? null : $object->clean_input($_POST["whatsapp"]) , 
                ':tripadvisor'	    =>  empty($object->clean_input($_POST["tripadvisor"])) ? null : $object->clean_input($_POST["tripadvisor"]) , 
                ':google'	        =>  empty($object->clean_input($_POST["google"])) ? null : $object->clean_input($_POST["google"]) , 
                 ':id'	            =>  $object->clean_input($_POST["market"]) , 
    		);
    
    		$object->query = "  UPDATE
                                    market
                                SET
                                    facebook    = :facebook, 
                                    instagram   = :instagram,
                                    tiktok      = :tiktok,
                                    linkedin      = :linkedin,
                                    youtube     = :youtube,
                                    twitter     = :twitter ,
                                    whatsapp    = :whatsapp ,  
                                    tripadvisor    = :tripadvisor ,  
                                    google    = :google  
                                WHERE id = :id ";
    		
            $object->execute($data);
 
    		$success = 'Mis à jour avec succés';
    
    		$output = array(
    			'error'		=>	$error,
    			'success'	=>	$success, 
    		);
    
    		echo json_encode($output); 
        }
        
        if($_POST['action'] == 'update_wifi')
        { 
            $error = '';
    
    		$success = ''; 
    		
    		$data = array( 
                ':wifi_ssid'	        =>  empty($object->clean_input($_POST["wifi_ssid"])) ? null : $object->clean_input($_POST["wifi_ssid"]) , 
                ':wifi_password'	    =>  empty($object->clean_input($_POST["wifi_password"])) ? null : $object->clean_input($_POST["wifi_password"]) ,  
                 ':id'	            =>  $object->clean_input($_POST["market"]) , 
    		);
    
    		$object->query = "  UPDATE
                                    market
                                SET
                                    wifi_ssid    = :wifi_ssid,  
                                    wifi_password    = :wifi_password 
                                WHERE id = :id ";
    		
            $object->execute($data);
 
    		$success = 'Mis à jour avec succés';
    
    		$output = array(
    			'error'		=>	$error,
    			'success'	=>	$success, 
    		);
    
    		echo json_encode($output); 
        }
        
        if($_POST['action'] == 'update_theme')
        { 
            $error = '';
    
    		$success = ''; 
    		
			$background =  $_POST["hidden_"];
    		if($_FILES["background"]["name"] != '')
    		{
    			$background = upload_background();
    		} else{
    		    $background    = str_replace('https://','../',$_POST["hidden_background"]);  
    		}
    		
    		$data = array( 
                ':background'           =>  $background,
                ':background_color0'    =>  ($object->clean_input($_POST["background_color0"])) ? $object->clean_input($_POST["background_color0"]) : '#cda45e',  
                ':background_color1'    =>  ($object->clean_input($_POST["background_color1"])) ? $object->clean_input($_POST["background_color1"]) : '#0a3955',  
                ':background_color2'    =>  ($object->clean_input($_POST["background_color2"])) ? $object->clean_input($_POST["background_color2"]) : '#cda45e', 
                ':background_color3'    =>  ($object->clean_input($_POST["background_color3"])) ? $object->clean_input($_POST["background_color3"]) : '#ffffff', 
                ':background_color4'    =>  ($object->clean_input($_POST["background_color4"])) ? $object->clean_input($_POST["background_color4"]) : '#0a3955', 
                ':text_color0'          =>  ($object->clean_input($_POST["text_color0"])) ? $object->clean_input($_POST["text_color0"]) : '#ffffff',
                ':text_color1'          =>  ($object->clean_input($_POST["text_color1"])) ? $object->clean_input($_POST["text_color1"]) : '#ffffff',
                ':text_color2'          =>  ($object->clean_input($_POST["text_color2"])) ? $object->clean_input($_POST["text_color2"]) : '#000000', 
                ':text_color3'          =>  ($object->clean_input($_POST["text_color3"])) ? $object->clean_input($_POST["text_color3"]) : '#0a3955', 
                ':text_color4'          =>  ($object->clean_input($_POST["text_color4"])) ? $object->clean_input($_POST["text_color4"]) : '#ffffff', 
                 ':id'	                =>  $object->clean_input($_POST["market"]) , 
    		);
    
    		$object->query = "  UPDATE
                                    market
                                SET  
                                    background = :background,
                                    background_color0 = :background_color0,
                                    background_color1 = :background_color1,
                                    background_color2 = :background_color2,
                                    background_color3 = :background_color3,
                                    background_color4 = :background_color4,
                                    text_color0 = :text_color0,
                                    text_color1 = :text_color1,
                                    text_color2 = :text_color2,
                                    text_color3 = :text_color3,
                                    text_color4 = :text_color4
                                WHERE id = :id ";
    		
            $object->execute($data);
 
    		$success = 'Mis à jour avec succés';
    
    		$output = array(
    			'error'		=>	$error,
    			'success'	=>	$success, 
				'background'      =>  str_replace('../','https://',$background)  ,
    			'lien'      =>  $object->get_url_menu_selected($_POST["market"]).'?data='.$object->encrypt($_POST["market"]),
    		);
    
    		echo json_encode($output); 
        }
    }



function upload_image()
{
    if(isset($_FILES['logo']))
    {
        $filename = $_FILES['logo']['name'];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $new_name = rand() . '.' . $extension;
        $destination = '../app.menuqrcode.tn/img/' . $new_name;
        $source_path = $_FILES['logo']['tmp_name'];

        // Set the desired maximum width and height
        $max_width = 600;
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


function upload_background()
{
    if(isset($_FILES["background"]))
    {
        $filename = $_FILES['background']['name'];
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $new_name = rand() . '.' . $extension;
        $destination = '../app.menuqrcode.tn/img/' . $new_name;
        $source_path = $_FILES['background']['tmp_name'];

        // Set the desired maximum width and height
        $max_width = 1080;
        $max_height = 1920;

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

?>