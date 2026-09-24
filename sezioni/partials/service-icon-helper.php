<?php
/**
 * Helper di rendering per le card servizi.
 * Centralizza il caricamento e la sanificazione degli SVG icona,
 * cosi' la logica non resta duplicata/embedded dentro sezioni/servizi.php.
 */

if (!function_exists('citytel_load_service_icon')) {
    /**
     * Legge un'icona SVG da assets/img/icons/services e la prepara
     * per il ricoloramento dinamico via CSS (currentColor).
     */
    function citytel_load_service_icon(string $iconSlug, string $cssClass): string
    {
        $iconPath = __DIR__ . '/../../assets/img/icons/services/' . basename($iconSlug) . '.svg';

        if (!is_readable($iconPath)) {
            return '';
        }

        $svg = file_get_contents($iconPath);

        if ($svg === false) {
            return '';
        }

        // Inietta la classe per il ricoloramento CSS e gli attributi ARIA:
        // l'icona e' puramente decorativa, il testo della card la descrive gia'.
        return str_replace(
            '<svg',
            '<svg class="' . htmlspecialchars($cssClass, ENT_QUOTES) . ' m-0" aria-hidden="true" focusable="false"',
            $svg
        );
    }
}

if (!function_exists('citytel_render_service_card')) {
    /**
     * Renderizza una card servizio, in variante standard o compatta.
     *
     * @param array{icon: string, title: string, items: string[]} $service
     * @param 'default'|'small' $variant
     */
    function citytel_render_service_card(array $service, string $variant = 'default'): void
    {
        $cssClass = $variant === 'small' ? 'icon-service-sm' : 'icon-service';
        $icon_svg = citytel_load_service_icon($service['icon'], $cssClass);
        $title    = $service['title'];
        $items    = $service['items'];

        $partial = $variant === 'small'
            ? __DIR__ . '/service-card-small.php'
            : __DIR__ . '/service-card.php';

        include $partial;
    }
}