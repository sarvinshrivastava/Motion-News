<?php
session_start();
$_SESSION['status'] = 'out';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link
        href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
        rel="stylesheet" />
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 max-w-sm w-full">
        <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>
        <form action="login.php" method="POST" class="space-y-4">
            <div>
                <label for="username" class="block mb-1 font-semibold">Username:</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    required
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring" />
            </div>
            <div>
                <label for="password" class="block mb-1 font-semibold">Password:</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring" />
            </div>
            <button
                type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded">
                Login
            </button>
        </form>
    </div>
</body>

</html>

<?php

use Dotenv\Dotenv;

require "vendor/autoload.php";
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$admin_user = $_ENV['ADMIN_USER'];
$admin_pass = $_ENV['ADMIN_PASS'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (strcmp($username, $admin_user) == 0 && strcmp($password, $admin_pass) == 0) {
        $_SESSION['status'] = 'in';
        header('Location: dashboard.php');
        exit();
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
}
