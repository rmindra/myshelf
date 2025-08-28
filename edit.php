<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM books WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $isbn = $_POST['isbn'];
  $author = $_POST['author'];
  $status = $_POST['status'];

  // Mengecek cover lama apakah ada atau tidak
  $stmt = $conn->prepare("SELECT cover_image FROM books WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $id, $user_id);
  $stmt->execute();
  $old_result = $stmt->get_result();
  $old_book = $old_result->fetch_assoc();
  $old_cover = $old_book['cover_image'];

  // Cover baru
  $cover_image = $old_cover;
  if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['cover']['tmp_name'];
    $name = time() . '_' . basename($_FILES['cover']['name']);
    $upload_dir = './uploads/';

    if (move_uploaded_file($tmp_name, $upload_dir . $name)) {
      // hapus cover lama jika tidak default
      if ($old_cover && file_exists("uploads/$old_cover")) {
        unlink("./uploads/$old_cover");
      }
      $cover_image = $name;
    }
  }

  $update = $conn->prepare("UPDATE books SET title=?, description=?, isbn=?, author=?, status=?, cover_image=? WHERE id=? AND user_id=?");
  $update->bind_param("ssssssii", $title, $description, $isbn, $author, $status, $cover_image, $id, $user_id);
  $update->execute();

  header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Edit Book</title>
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
    <h2 class="text-center text-pink">✏️ Edit Book</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3"><label>Title</label><input class="form-control" name="title" value="<?= htmlspecialchars($book['title']) ?>"></div>
      <div class="mb-3"><label>Description</label><textarea class="form-control" name="description"><?= htmlspecialchars($book['description']) ?></textarea></div>
      <div class="mb-3"><label>ISBN</label><input class="form-control" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>"></div>
      <div class="mb-3"><label>Author</label><input class="form-control" name="author" value="<?= htmlspecialchars($book['author']) ?>"></div>
      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control">
          <option value="disimpan" <?= $book['status'] == 'disimpan' ? 'selected' : '' ?>>Disimpan</option>
          <option value="dipinjam" <?= $book['status'] == 'dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
        </select>
      </div>
      <label>Change cover (optional):</label>
      <input type="file" name="cover" class="form-control mb-2">
      <button class="btn btn-pink w-100">Update Book</button>
    </form>
  </div>
</body>

</html>