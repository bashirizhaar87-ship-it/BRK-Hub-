<?php
/**
 * BRK Hub
 * Authentication Middleware
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireAdmin(): void
{
    requireLogin();

    if (
        !isset($_SESSION['role']) ||
        strtolower($_SESSION['role']) !== 'admin'
    ) {
        http_response_code(403);
        exit('Access Denied');
    }
}

function currentUserId()
{
    return $_SESSION['user_id'] ?? null;
}

function currentUsername()
{
    return $_SESSION['username'] ?? null;
}

function currentUserRole()
{
    return $_SESSION['role'] ?? 'guest';
}

function logoutUser(): void
{
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
}