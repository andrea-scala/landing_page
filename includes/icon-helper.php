<?php
declare(strict_types=1);

/**
 * Icon Helper — caricamento e sanificazione di icone SVG inline.
 *
 * Helper GENERICO: non conosce card, sezioni o layout.
 * Le icone stanno in assets/img/icons/<cartella>/<slug>.svg
 * (es. cartella "services" -> assets/img/icons/services/antenna.svg).
 *
 * Il rendering delle card che usano le icone vive in card-helper.php.
 */

// Cartella radice di tutte le icone SVG (con slash finale).
if (!defined('CITYTEL_ICONS_DIR')) {
    define('CITYTEL_ICONS_DIR', __DIR__ . '/../assets/img/icons/');
}

if (!function_exists('citytel_load_icon')) {
    /**
     * Legge un'icona SVG e la prepara per il ricoloramento dinamico via CSS
     * (gli SVG usano stroke="currentColor", quindi ereditano il colore del contenitore).
     *
     * @param string $iconSlug Nome file senza estensione (es. "antenna").
     * @param string $cssClass Classe CSS da iniettare sul tag <svg> (es. "icon-service").
     * @param string $section  Sottocartella sotto assets/img/icons/ (default "services").
     *                         NON serve lo slash: lo aggiunge la funzione.
     * @return string Markup <svg> pronto per l'echo, oppure '' se il file non esiste.
     */
    function citytel_load_icon(string $iconSlug, string $cssClass, string $section = 'services'): string
    {
        // basename() blocca path traversal (es. "../../etc/passwd") su slug e cartella.
        $iconPath = CITYTEL_ICONS_DIR . basename($section) . '/' . basename($iconSlug) . '.svg';

        if (!is_readable($iconPath)) {
            return '';
        }

        $svg = file_get_contents($iconPath);

        if ($svg === false) {
            return '';
        }

        // Inietta la classe per il ricoloramento CSS e gli attributi ARIA:
        // l'icona e' puramente decorativa, il testo accanto la descrive gia'.
        // Limite 1: sostituisce solo il primo <svg (radice), mai eventuali SVG annidati.
        $injected = preg_replace(
            '/<svg/',
            '<svg class="' . htmlspecialchars($cssClass, ENT_QUOTES) . '" aria-hidden="true" focusable="false"',
            $svg,
            1
        );

        return $injected ?? '';
    }
}