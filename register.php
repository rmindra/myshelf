<?php
include "./koneksi.php";

if (isset($_POST['regUser']) && isset($_POST['regPass'])) {
    $username = $_POST['regUser'];
    $password = $_POST['regPass'];

    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

    if ($conn->query($sql))
        echo "<script>alert('Registrasi berhasil! Silahkan login.'); window.location.href='login.php';</script>";
    else
        echo "<script>alert('Registrasi gagal: " . $conn->error . "');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container my-5 w-50">
        <div class="card shadow-md d-flex">
            <div id="register" class="card shadow-sm h-full">
                <div class="card-header bg-pink text-white text-center">
                    <h4 class="mb-0">Fairytales Start Here!</h4>
                </div>
                <div class="card-body bg-back-pink d-flex justify-content-center align-items-center">
                    <form method="post" class="w-75 py-4">
                        <div class="mb-3">
                            <label for="username" class="form-label">Reader's Name</label>
                            <input type="text" id="username" name="regUser" class="form-control" placeholder="Reader's Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Magic Word</label>
                            <input type="password" id="password" name="regPass" class="form-control" placeholder="Simsalabim" required>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn bg-pink hover-pink border-pink text-white">Start</button>
                            <div class="d-flex flex-row">
                                <span class="align-self-center text-body-secondary">Sudah punya akun?</span>
                                <a href="./login.php" class="hover-text-pink ml-3">Login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>