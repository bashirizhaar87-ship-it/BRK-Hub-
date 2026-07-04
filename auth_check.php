<?php
session_start();

// User must be logged in
if (
    !isset($_SESSION['admin']) ||
    $_SESSION['admin'] !== true
) {
    header("Location: login.php");
    exit;
}

// Session timeout (30 minutes)
$timeout = 1800;

if (
    isset($_SESSION['login_time']) &&
    (time() - $_SESSION['login_time']) > $timeout
) {

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();

    header("Location: login.php?expired=1");
    exit;
}

// Refresh activity time
$_SESSION['login_time'] = time();