<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>BRK Hub</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-black shadow">

<div class="container">

<a class="navbar-brand fw-bold" href="../index.php">

🟦 BRK Hub

</a>

<button class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#menu">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse"
id="menu">

<ul class="navbar-nav ms-auto">

<li class="nav-item">
<a class="nav-link" href="../index.php">
🏠 Home
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">
📱 Apps
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">
🎵 Songs
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="#">
🎥 Videos
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="../admin/login.php">
🔐 Admin
</a>
</li>

</ul>

</div>

</div>

</nav>

<div class="container mt-4">