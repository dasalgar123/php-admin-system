<?php
session_start();

// Destruir la sesión
session_destroy();
 
// Redirigir al login
header('Location: LoginController.php');
exit;
?> 