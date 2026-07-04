<?php
// Path: BRK-Hub/includes/session.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Secures admin pages from unauthenticated access
 */
function checkAdminLogin() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: /admin/login.php");
        exit;
    }
    
    // Session Timeout Check (30 Minutes)
    $timeout = 1800;
    if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        header("Location: /admin/login.php?expired=1");
        exit;
    }
    
    $_SESSION['login_time'] = time();
}
?>
