<?php

include '../config.menuqrcode/config.php';

$object = new application(); 

include_once(__DIR__ . '/securite.php');
securite_exiger_connexion($object);


$page_actuel = $object->get_page_url();

if (isset($_POST["action"])) {   
  
    if ($_POST["action"] == 'Edit') {
        $error = '';

        $success = '';   
        
        if($object->autorisation('u',$_SESSION['id'],$page_actuel) == 2 ) { 

            $data = array(
                ':data'                             => $object->clean_input($_POST['data']),
                ':width'                            => $object->clean_input($_POST['width']),
                ':height'                           => $object->clean_input($_POST['height']),
                ':margin'                           => $object->clean_input($_POST['margin']), 
                ':qr_error_correction_level'        => $object->clean_input($_POST['qr-error-correction-level']),
                ':dots_type'                        => $object->clean_input($_POST['dots-type']), 
                ':dots_color_type'                  => $object->clean_input($_POST['dots_color_type']), 
                ':dots_color'                       => $object->clean_input($_POST['dots-color']), 
                ':dots_gradient_type'               => $object->clean_input($_POST['dots_gradient_type']), 
                ':dots_gradient_color1'             => $object->clean_input($_POST['dots-gradient-color1']), 
                ':dots_gradient_color2'             => $object->clean_input($_POST['dots-gradient-color2']), 
                ':dots_gradient_rotation'           => $object->clean_input($_POST['dots-gradient-rotation']), 
                ':corners_square_type'              => $object->clean_input($_POST['corners-square-type']), 
                ':corners_square_color_type'        => $object->clean_input($_POST['corners_square_color_type']), 
                ':corners_square_color'             => $object->clean_input($_POST['corners-square-color']), 
                ':corners_square_gradient_type'     => $object->clean_input($_POST['corners_square_gradient_type']), 
                ':corners_square_gradient_color1'   => $object->clean_input($_POST['corners-square-gradient-color1']), 
                ':corners_square_gradient_color2'   => $object->clean_input($_POST['corners-square-gradient-color2']), 
                ':corners_square_gradient_rotation' => $object->clean_input($_POST['corners-square-gradient-rotation']), 
                ':corners_dot_type'                 => $object->clean_input($_POST['corners-dot-type']), 
                ':corners_dot_color_type'           => $object->clean_input($_POST['corners_dot_color_type']), 
                ':corners_dot_color'                => $object->clean_input($_POST['corners-dot-color']), 
                ':corners_dot_gradient_type'        => $object->clean_input($_POST['corners_dot_gradient_type']), 
                ':corners_dot_gradient_color1'      => $object->clean_input($_POST['corners-dot-gradient-color1']), 
                ':corners_dot_gradient_color2'      => $object->clean_input($_POST['corners-dot-gradient-color2']),
                ':corners_dot_gradient_rotation'    => $object->clean_input($_POST['corners-dot-gradient-rotation']),
                ':background_color_type'            => $object->clean_input($_POST['background_color_type']),
                ':background_color'                 => $object->clean_input($_POST['background-color']),
                ':background_gradient_type'         => $object->clean_input($_POST['background_gradient_type']),
                ':background_gradient_color1'       => $object->clean_input($_POST['background-gradient-color1']),
                ':background_gradient_color2'       => $object->clean_input($_POST['background-gradient-color2']),
                ':background_gradient_rotation'     => $object->clean_input($_POST['background-gradient-rotation']),
                ':image_size'                       => $object->clean_input($_POST['image-size']),
                ':image_margin'                     => $object->clean_input($_POST['image-margin']), 
                ':market'                           => $object->clean_input($_SESSION['market']),
            );
    
            $object->query = "  UPDATE
                                    qrcodes
                                SET 
                                    data = :data,
                                    width = :width,
                                    height = :height,
                                    margin = :margin,
                                    qr_error_correction_level = :qr_error_correction_level,
                                    dots_type = :dots_type,
                                    dots_color_type = :dots_color_type,
                                    dots_color = :dots_color,
                                    dots_gradient_type = :dots_gradient_type,
                                    dots_gradient_color1 = :dots_gradient_color1,
                                    dots_gradient_color2 = :dots_gradient_color2,
                                    dots_gradient_rotation = :dots_gradient_rotation,
                                    corners_square_type = :corners_square_type,
                                    corners_square_color_type = :corners_square_color_type,
                                    corners_square_color = :corners_square_color,
                                    corners_square_gradient_type =  :corners_square_gradient_type,
                                    corners_square_gradient_color1 =  :corners_square_gradient_color1,
                                    corners_square_gradient_color2 =  :corners_square_gradient_color2,
                                    corners_square_gradient_rotation =  :corners_square_gradient_rotation,
                                    corners_dot_type =  :corners_dot_type,
                                    corners_dot_color_type =  :corners_dot_color_type,
                                    corners_dot_color =  :corners_dot_color,
                                    corners_dot_gradient_type =  :corners_dot_gradient_type,
                                    corners_dot_gradient_color1 =  :corners_dot_gradient_color1,
                                    corners_dot_gradient_color2 =  :corners_dot_gradient_color2,
                                    corners_dot_gradient_rotation =  :corners_dot_gradient_rotation,
                                    background_color_type =  :background_color_type,
                                    background_color =  :background_color,
                                    background_gradient_type =  :background_gradient_type,
                                    background_gradient_color1 =  :background_gradient_color1,
                                    background_gradient_color2 =  :background_gradient_color2,
                                    background_gradient_rotation =  :background_gradient_rotation,
                                    image_size =  :image_size,
                                    image_margin =  :image_margin
                                WHERE
                        			  market = :market ";
                        			
    		$object->execute($data); 
    
    
            $success = 'QR Code Modifié';
        
        }else{
            $error = 'Autorisation requise  ' ;
        }

        $output = array(
            'error' => $error,
            'success' => $success, 
            'data' => $data, 
        );

        echo json_encode($output);

    }
 
 
}

 
