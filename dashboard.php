<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['user_is_logged_in'])) {
  header('Location: login.php');
}
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : null;

if ($search) {
  $stmt = $conn->prepare("SELECT * FROM books WHERE user_id = ? AND (title LIKE ? OR author LIKE ?)");
  $stmt->bind_param("iss", $user_id, $search, $search);
} else {
  $stmt = $conn->prepare("SELECT * FROM books WHERE user_id = ?");
  $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Comic Sans MS', cursive, sans-serif;
    }

    .gallery-title {
      color: #ff4da6;
      font-size: 3rem;
      font-weight: bold;
      text-align: center;
      margin: 40px 0 20px;
    }

    .container {
      background: #fff0f5;
      padding: 0.5rem;
      border-radius: 20px;
    }

    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3);
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-10px);
    }

    .card-img-top {
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
      height: 300px;
      object-fit: cover;
    }

    .card-body {
      background-color: #fff0f5;
      border-bottom-left-radius: 20px;
      border-bottom-right-radius: 20px;
    }

    .card-title {
      color: #d63384;
      font-size: 1.2rem;
      font-weight: bold;
    }

    .card-text {
      color: #a64d79;
    }

    .badge {
      font-size: 0.75rem;
      padding: 5px 10px;
      border-radius: 10px;
    }

    .bg-warning {
      background-color: #ffc107 !important;
      color: black;
    }

    .bg-success {
      background-color: #28a745 !important;
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

    .form-control:focus {
      border-color: #ff4da6;
      box-shadow: 0 0 0 0.25rem rgba(255, 77, 166, 0.25);
    }
  </style>
</head>

<body>

  <!-- Top Navbar -->
  <nav class="navbar navbar-expand bg-pink shadow-sm">
    <div class="container-fluid">
      <span class="text-white navbar-text">
        Selamat Datang di Dashboard, <?php echo "$username"; ?>!
      </span>
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link text-white pink-active rounded mx-3" href="#">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white hover-pink rounded" href="./logout.php">Logout</a></li>
      </ul>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">

      <!-- Main content -->
      <main class="ms-sm-auto px-4 py-4">
        <div class="container shadow-sm">
          <!-- Bookshelf Style -->
          <div class="container my-5">
            <h1 class="gallery-title">📚 My Fairyshelf 📚</h1>
            <a href="add.php" class="btn btn-pink mb-4">➕ Add New Book</a>
            <form method="GET" class="mb-4">
              <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari judul atau penulis..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button class="btn btn-pink text-white" type="submit">🔍 Cari</button>
              </div>
            </form>
            <div class="row row-cols-2 row-cols-md-4 g-4">
              <?php while ($book = $result->fetch_assoc()): ?>
                <?php $cover = $book['cover_image'] ? './uploads/' . $book['cover_image'] : './img/default-cute-cover.jpg'; ?>
                <div class="col">
                  <div class="card text-center h-100">
                    <img src="<?= $cover ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>">
                    <div class="card-body">
                      <h6 class="card-title"><?= htmlspecialchars($book['title']) ?></h6>
                      <p class="card-text">
                        <?= !empty($book['description']) ? htmlspecialchars($book['description']) . "<br>" : "" ?>
                        <strong>ISBN:</strong> <?= htmlspecialchars($book['isbn']) ?><br>
                        <strong>Author:</strong> <?= htmlspecialchars($book['author']) ?><br>
                        <strong>Status:</strong> <span class="badge bg-<?= $book['status'] == 'dipinjam' ? 'warning' : 'success' ?>">
                          <?= htmlspecialchars($book['status']) ?>
                        </span><br />
                      </p>
                      <a href="edit.php?id=<?= $book['id'] ?>" class="btn btn-sm btn-outline-primary">✏️ Edit</a>
                      <a href="delete.php?id=<?= $book['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger">🗑️ Delete</a>
                    </div>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          </div>

        </div>
      </main>

    </div>
  </div>
</body>

</html>