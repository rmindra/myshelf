<?php
session_start();
if (isset($_SESSION['user_is_logged_in'])) {
  session_destroy();
}
header('Location: login.php');
exit;
