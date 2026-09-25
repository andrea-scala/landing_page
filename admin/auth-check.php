<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$isLoggedIn = !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

if ($isLoggedIn && isset($_SESSION['admin_last_activity'])) {
    if ((time() - (int) $_SESSION['admin_last_activity']) > ADMIN_SESSION_IDLE_TIMEOUT) {
        $_SESSION = [];
        session_destroy();
        $isLoggedIn = false;
    }
}

if (!$isLoggedIn) {
    header('Location: login.php');
    exit;
}

$_SESSION['admin_last_activity'] = time();