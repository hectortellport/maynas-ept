<?php
session_start();
session_destroy();
header('Location: admin_noticia.php');
exit;
?>