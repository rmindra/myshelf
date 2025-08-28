<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil gambarnya dulu sebelum hapus
$stmt = $conn->prepare("SELECT cover_image FROM books WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if ($book) {
  if ($book['cover_image'] && file_exists("uploads/" . $book['cover_image'])) {
    unlink("uploads/" . $book['cover_image']);
  }

  // Delete book
  $stmt = $conn->prepare("DELETE FROM books WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $id, $user_id);
  $stmt->execute();
}

header("Location: dashboard.php");
