<?php
include "./koneksi.php";
session_start();

if (isset($_POST['logUser']) && isset($_POST['logPass'])) {
    $username = $_POST['logUser'];
    $password = $_POST['logPass'];

    $sql = "SELECT * from users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0 && $result->num_rows < 2) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_is_logged_in'] = true;

        header("Location: dashboard.php");
        exit();
    } else
        echo "<script>alert('Username atau password salah!'); window.location.href='login.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container my-5 w-50">
        <div class="card shadow-md d-flex">
            <div id="login" class="card shadow-sm h-full">
                <div class="card-header bg-pink text-white text-center">
                    <h4 class="mb-0">Login</h4>
                </div>
                <div class="card-body d-flex justify-content-center align-items-center">
                    <form method="post" class="w-75 py-4">
                        <div class="mb-3">
                            <label for="username" class="form-label">Reader's Name</label>
                            <input type="text" id="username" name="logUser" class="form-control" placeholder="Reader's Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Magic Word</label>
                            <input type="password" id="password" name="logPass" class="form-control" placeholder="Simsalabim" required>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn bg-pink hover-pink border-pink text-white">Login</button>
                            <div class="d-flex flex-row">
                                <span class="align-self-center text-body-secondary">Belum punya akun?</span>
                                <a href="./register.php" class="hover-text-pink ml-3">Daftar Sekarang</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>