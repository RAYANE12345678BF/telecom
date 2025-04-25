<?php
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$remember = isset($_POST['remember']) ? $_POST['remember'] : false;


if (empty($email) || empty($password)) {
    $_SESSION['error'] = "L'email et le mot de passe sont requis";
    redirect_back();
}

header("Location: ../index.php");