<?php
declare(strict_types=1);

/**
 * ENDPOINT: SALVATAGGIO DI UNA SEZIONE
 * ====================================
 * POST salva.php?sezione=<id>   (corpo della richiesta: il JSON della sezione)
 *   ->  { "success": true, "messaggio": "Sezione salvata correttamente." }
 *
 * Ricevuto il JSON lo passa a salvaSezione() (storage.php), che valida l'id
 * e scrive il file. La whitelist delle sezioni non è più qui: è in storage.php
 * ed è "una sezione esiste se esiste il suo JSON".
 *
 * NOTA: nessuna autenticazione (da aggiungere quando faremo l'area admin).
 */

require_once __DIR__ . '/storage.php';

header('Content-Type: application/json; charset=utf-8');

// Si salva solo con POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'messaggio' => 'Metodo non consentito.']);
    exit;
}

$id = $_GET['sezione'] ?? '';
if (!is_string($id)) {
    $id = '';
}

try {
    // Decodifica come OGGETTO (non array): vedi nota in leggiSezione().
    $dati = json_decode(file_get_contents('php://input'));

    // Deve essere un JSON valido e il livello principale deve essere un oggetto.
    if (json_last_error() !== JSON_ERROR_NONE || !is_object($dati)) {
        throw new RuntimeException('Il JSON ricevuto non è valido.', 400);
    }

    salvaSezione($id, $dati);

    echo json_encode(['success' => true, 'messaggio' => 'Sezione salvata correttamente.']);
} catch (RuntimeException $e) {
    http_response_code($e->getCode() >= 400 ? $e->getCode() : 500);
    echo json_encode(['success' => false, 'messaggio' => $e->getMessage()]);
}