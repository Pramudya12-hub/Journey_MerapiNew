<?php
session_start();
require "../include/db.php";

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($username === "" || $email === "" || $password === "") {
        $errors[] = "Semua field harus diisi.";
    }

    $check = $conn->prepare("SELECT * FROM users WHERE Username = ? OR Email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Username atau email sudah digunakan!";
    }

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $insert = $conn->prepare("INSERT INTO users (Username, Email, Password) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $username, $email, $hashed);

        if ($insert->execute()) {
            header("Location: ../index.php");
            exit;
        } else {
            $errors[] = "Terjadi kesalahan saat registrasi.";
        }
    }

    echo implode("<br>", $errors);
    exit;
}
?>
