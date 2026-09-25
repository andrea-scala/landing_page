<?php
declare(strict_types=1);

/**
 * Configurazione pannello Admin.
 *
 * IMPORTANTE: sostituisci ADMIN_PASSWORD_HASH generando un tuo hash con:
 *   php -r "echo password_hash('la-tua-password-forte', PASSWORD_DEFAULT), PHP_EOL;"
 * Non lasciare mai l'hash di esempio in produzione.
 */

const ADMIN_USERNAME = 'admin';

const ADMIN_PASSWORD_HASH = '$2y$10$e6fCP0lgK2fCOHB4KjzeD.PZbeAan5misN8zaEgws85XHK7K7fmxu';

const ADMIN_SESSION_NAME = 'citytel_admin_sess';
const ADMIN_SESSION_IDLE_TIMEOUT = 1800; // 30 minuti di inattività

if (session_status() === PHP_SESSION_NONE) {
    session_name(ADMIN_SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/admin/',
        'httponly' => true,
        'samesite' => 'Strict',
        'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    ]);
    session_start();
}

require_once __DIR__ . '/../sezioni/partials/content-helper.php';

if (!function_exists('citytel_admin_csrf_token')) {
    /**
     * Restituisce il token CSRF corrente per la sessione, generandolo se assente.
     */
    function citytel_admin_csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('citytel_admin_csrf_validate')) {
    /**
     * Valida un token CSRF ricevuto da form, confrontandolo in modo time-safe.
     * Il token va rigenerato dopo ogni uso (one-shot) per prevenire replay.
     */
    function citytel_admin_csrf_validate(?string $token): bool
    {
        if (empty($_SESSION['csrf_token']) || $token === null || $token === '') {
            return false;
        }

        $valid = hash_equals($_SESSION['csrf_token'], $token);

        unset($_SESSION['csrf_token']);

        return $valid;
    }
}

if (!function_exists('citytel_admin_label_from_key')) {
    /**
     * Converte una chiave JSON (es. "img_alt") in un'etichetta leggibile
     * (es. "Img alt") per il form di editing.
     */
    function citytel_admin_label_from_key(string $key): string
    {
        $label = str_replace(['_', '-'], ' ', $key);
        return ucfirst($label);
    }
}