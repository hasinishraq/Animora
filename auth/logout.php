<?php
session_start();
session_destroy();
header("Location: /animora/auth/login.php");
exit;
?>
