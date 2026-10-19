<?php

    include('/home5/qdwlxrcz/config.menuqrcode/config.php');

    $object = new application();   
   
    echo  $object->get_rapelle($_POST['market']); 