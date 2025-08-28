<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $title = $_POST['title'];
  $description = $_POST['description'];
  $isbn = $_POST['isbn'];
  $author = $_POST['author'];
  $status = $_POST['status'];
  $cover_image = null;
  if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['cover']['tmp_name'];
    $name = basename($_FILES['cover']['name']);
    $upload_dir = 'uploads/';

    $unique_name = time() . '_' . $name;

    if (move_uploaded_file($tmp_name, $upload_dir . $unique_name)) {
      $cover_image = $unique_name;
    }
  }

  $stmt = $conn->prepare("INSERT INTO books (user_id, title, description, isbn, author, status, cover_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("issssss", $user_id, $title, $description, $isbn, $author, $status, $cover_image);
  $stmt->execute();

  header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Add Book</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-image: url(./img/back.jpg);
      font-family: 'Comic Sans MS';
    }

    .form-container {
      background: #fff0f5;
      border-radius: 20px;
      padding: 2rem;
      max-width: 600px;
      margin: auto;
      margin-top: 50px;
    }

    .text-pink {
      color: #ff4da6;
    }

    .btn-pink {
      background-color: #ff99cc;
      border: none;
      color: white;
    }

    .btn-pink:hover {
      background-color: #ff66b2;
    }
  </style>
</head>

<body>
  <div class="form-container shadow">
    <h2 class="text-center text-pink">➕ Add New Book</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label>Title</label>
        <input class="form-control" name="title" required>
      </div>
      <div class="mb-3">
        <label>Description</label>
        <textarea class="form-control" name="description"></textarea>
      </div>
      <div class="mb-3">
        <label>ISBN</label>
        <input class="form-control" name="isbn" required>
      </div>
      <div class="mb-3">
        <label>Author</label>
        <input class="form-control" name="author" required>
      </div>
      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
          <option value="disimpan">Disimpan</option>
          <option value="dipinjam">Dipinjam</option>
        </select>
      </div>
      <div class="mb-3">
        <input type="file" name="cover" class="form-control mb-2">
      </div>
      <button class="btn btn-pink w-100">Save Book</button>
    </form>
  </div>
</body>

</html>