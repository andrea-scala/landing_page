<?php
declare(strict_types=1);

/**
 * ENDPOINT: LETTURA DI UNA SEZIONE
 * ================================
 * GET leggi.php?sezione=<id>  ->  { "success": true, "dati": { ...JSON della sezione... } }
 *
 * Se la sezione non esiste o il file non è un JSON valido risponde con un
 * errore (success:false + messaggio): l'editor allora NON si apre e il
 * pulsante Salva resta disabilitato, per non sovrascrivere mai un file
 * reale con dati sbagliati.
 *
 * ACCESSO: solo admin loggati (vedi includes/auth.php).
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/storage.php';

// Solo admin loggati: altrimenti risponde 401 (JSON) ed esce subito.
richiediAdmin('api');

header('Content-Type: application/json; charset=utf-8');

// Se il parametro manca, o è passato come array (?sezione[]=x), diventa stringa vuota
// e viene rifiutato da verificaSezione().
$id = $_GET['sezione'] ?? '';
if (!is_string($id)) {
    $id = '';
}

try {
    echo json_encode(
        ['success' => true, 'dati' => leggiSezione($id)],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
} catch (RuntimeException $e) {
    http_response_code($e->getCode() >= 400 ? $e->getCode() : 500);
    echo json_encode(['success' => false, 'messaggio' => $e->getMessage()]);
}