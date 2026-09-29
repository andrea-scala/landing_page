<?php
declare(strict_types=1);

/**
 * Content Helper — CMS file-based (JSON) generico, multi-pagina.
 *
 * Usato sia dal frontend pubblico (getContent) sia dal pannello Admin
 * (citytel_load_json_file / citytel_write_json_file), così la logica
 * di lettura/scrittura JSON esiste in un solo posto.
 *
 * Richiede PHP >= 8.1 (usa array_is_list()).
 */

const CITYTEL_CONTENT_DIR = __DIR__ . '/../../content/';

if (!function_exists('citytel_valid_page_name')) {
    /**
     * Whitelist del nome pagina: solo lettere/numeri/trattini/underscore.
     * Blocca path traversal e nomi non attesi.
     */
    function citytel_valid_page_name(string $pageName): bool
    {
        return (bool) preg_match('/^[a-z0-9_-]+$/i', $pageName);
    }
}

if (!function_exists('citytel_load_json_file')) {
    /**
     * Legge e decodifica un file JSON in modo sicuro.
     * Non lancia mai eccezioni: in caso di problema logga e ritorna [].
     */
    function citytel_load_json_file(string $filePath): array
    {
        if (!is_readable($filePath)) {
            error_log(sprintf('content-helper: file non trovato o non leggibile: "%s"', $filePath));
            return [];
        }

        $raw = file_get_contents($filePath);

        if ($raw === false) {
            error_log(sprintf('content-helper: impossibile leggere il file: "%s"', $filePath));
            return [];
        }

        $decoded = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            error_log(sprintf(
                'content-helper: JSON non valido in "%s" — %s',
                $filePath,
                json_last_error_msg()
            ));
            return [];
        }

        return $decoded;
    }
}

if (!function_exists('getContent')) {
    /**
     * Carica i contenuti editabili di una pagina/sezione dal relativo file JSON.
     *
     * Uso generico per qualunque pagina del sito:
     *   getContent('home')
     *   getContent('chisiamo')
     *   getContent('servizi')
     *   getContent('clienti')
     *
     * @param string $pageName Nome logico della pagina == nome file senza estensione.
     * @param array  $defaults Valori di fallback (merge shallow) se il file manca,
     *                         è corrotto, o non contiene una data chiave.
     * @return array<string, mixed>
     */
    function getContent(string $pageName, array $defaults = []): array
    {
        static $cache = [];

        if (!citytel_valid_page_name($pageName)) {
            error_log(sprintf('getContent: nome pagina non valido: "%s"', $pageName));
            return $defaults;
        }

        if (isset($cache[$pageName])) {
            return array_replace($defaults, $cache[$pageName]);
        }

        $filePath = CITYTEL_CONTENT_DIR . $pageName . '.json';
        $data = citytel_load_json_file($filePath);

        $cache[$pageName] = $data;

        return array_replace($defaults, $data);
    }
}

if (!function_exists('citytel_e')) {
    /**
     * Shortcut per output sanificato di una stringa di contenuto proveniente da JSON.
     */
    function citytel_e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}