<?php
declare(strict_types=1);

/**
 * Card Helper — rendering delle card (servizi, ecc.).
 *
 * Usa icon-helper.php per le icone e include i partial di layout
 * presenti in sezioni/partials/ (solo markup).
 * Posizione: includes/ (helper di logica).
 */

require_once __DIR__ . '/icon-helper.php';

// Cartella dei partial di markup (con slash finale), relativa a includes/.
if (!defined('CITYTEL_PARTIALS_DIR')) {
    define('CITYTEL_PARTIALS_DIR', __DIR__ . '/../sezioni/partials/');
}

if (!function_exists('citytel_render_partial')) {
    /**
     * Include un partial passandogli le variabili in modo esplicito.
     * Le variabili vivono solo nello scope di questa funzione:
     * niente variabili globali che si "sporcano" tra una card e l'altra.
     *
     * @param string               $file Percorso assoluto del partial.
     * @param array<string, mixed> $vars Variabili disponibili nel partial.
     */
    function citytel_render_partial(string $file, array $vars = []): void
    {
        extract($vars, EXTR_SKIP);
        include $file;
    }
}

if (!function_exists('citytel_render_service_card')) {
    /**
     * Renderizza (echo) una card servizio, in variante standard o compatta.
     *
     * @param array{icon: string, title: string, items: string[]} $service
     * @param 'default'|'small' $variant
     */
    function citytel_render_service_card(array $service, string $variant = 'default'): void
    {
        $isSmall = $variant === 'small';

        // Classe CSS dell'icona in base alla variante (definite in components.css).
        $cssClass = $isSmall ? 'icon-service-sm' : 'icon-service';

        // Le icone dei servizi stanno in assets/img/icons/services/.
        $iconSvg = citytel_load_icon($service['icon'], $cssClass, 'services');

        // Partial di layout in base alla variante.
        $partial = $isSmall
            ? CITYTEL_PARTIALS_DIR . 'service-card-small.php'
            : CITYTEL_PARTIALS_DIR . 'service-card.php';

        // Nomi variabili invariati: i partial usano $icon_svg, $title, $items.
        citytel_render_partial($partial, [
            'icon_svg' => $iconSvg,
            'title'    => $service['title'],
            'items'    => $service['items'],
        ]);
    }
}