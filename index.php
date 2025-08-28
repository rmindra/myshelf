<?php
session_start();
if (!isset($_SESSION['userLogin']))
    header('Location: register.php');
else
    header('Location: dashboard.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booklist</title>
</head>

<body>

</body>

</html>