<?php

include('../config.menuqrcode/config.php');

$object = new application();
setcookie(
    "id",
    null,
    time() + (10 * 365 * 24 * 60 * 60)
  );


 unset($_SESSION['id']);
 unset($_COOKIE['id']);

session_destroy();

header("location: login");

?>