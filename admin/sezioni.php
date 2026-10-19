<?php
declare(strict_types=1);

/**
 * ENDPOINT: ELENCO DELLE SEZIONI
 * ==============================
 * GET sezioni.php  ->  { "success": true, "sezioni": ["chisiamo", "clienti", ...] }
 *
 * L'editor lo chiama all'avvio per sapere quali sezioni esistono sul server
 * e costruire il menu a tendina. Gli schemi (cioè i form) stanno invece
 * nel JavaScript: l'editor mostra come modificabili solo le sezioni di cui
 * ha sia i dati (qui) sia lo schema (sezioni/*.js).
 */

require_once __DIR__ . '/storage.php';

header('Content-Type: application/json; charset=utf-8');

try {
    echo json_encode([
        'success' => true,
        'sezioni' => elencoSezioni(),
    ]);
} catch (RuntimeException $e) {
    // Il codice dell'eccezione è lo status HTTP (se non è un errore HTTP, 500).
    http_response_code($e->getCode() >= 400 ? $e->getCode() : 500);
    echo json_encode(['success' => false, 'messaggio' => $e->getMessage()]);
}