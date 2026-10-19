<?php
declare(strict_types=1);

/**
 * STRATO DI ARCHIVIAZIONE DEI CONTENUTI
 * =====================================
 * È l'UNICO file che sa DOVE e COME sono salvati i contenuti delle sezioni.
 * Gli endpoint (sezioni.php, leggi.php, salva.php) usano solo le funzioni
 * qui sotto e non toccano mai i file direttamente.
 *
 * Funzioni pubbliche:
 *   - elencoSezioni()            -> ["chisiamo", "clienti", ...]
 *   - leggiSezione($id)          -> oggetto JSON decodificato
 *   - salvaSezione($id, $dati)   -> scrive il JSON della sezione
 *
 * PERCHÉ ESISTE: in futuro i contenuti andranno in MySQL (tabella con una riga
 * per sezione: sezione + JSON). Quel giorno basterà riscrivere QUESTO file
 * con PDO, mantenendo gli stessi nomi e le stesse firme delle funzioni:
 * editor.js, schemi, leggi.php, salva.php e sezioni.php non cambiano.
 *
 * WHITELIST: una sezione esiste solo se esiste il suo file in content/.
 * Non si possono creare sezioni nuove dall'editor: i JSON del sito sono la base
 * e non possono mancare. L'id viene sempre validato prima di costruire un percorso,
 * così non è possibile uscire dalla cartella (path traversal).
 *
 * ERRORI: le funzioni lanciano RuntimeException; il codice dell'eccezione è
 * lo status HTTP da restituire (400, 404, 500).
 */

require_once __DIR__ . '/config.php';

/**
 * Un id di sezione è valido solo se composto da lettere, numeri, trattino
 * e underscore (es. "chisiamo"). Blocca "../", slash e simili.
 */
function idSezioneValido(string $id): bool
{
    return preg_match('/^[a-z0-9_-]+$/i', $id) === 1;
}

/**
 * Percorso del file di una sezione.
 * ATTENZIONE: da usare solo dopo aver validato l'id (vedi verificaSezione).
 */
function percorsoSezione(string $id): string
{
    return CARTELLA_CONTENUTI . '/' . $id . '.json';
}

/**
 * Elenco degli id delle sezioni presenti, in ordine alfabetico.
 * Oggi: i file *.json della cartella dei contenuti.
 * Domani (MySQL): SELECT sezione FROM contenuti.
 *
 * @return string[]
 */
function elencoSezioni(): array
{
    $percorsi = glob(CARTELLA_CONTENUTI . '/*.json');

    if ($percorsi === false) {
        throw new RuntimeException('Impossibile leggere la cartella dei contenuti.', 500);
    }

    // Dal percorso completo al solo nome senza estensione ("…/hero.json" -> "hero").
    $id = array_map(static fn(string $p): string => basename($p, '.json'), $percorsi);

    // Scarta i file con nomi non ammessi, poi ordina.
    $id = array_filter($id, 'idSezioneValido');
    sort($id);

    return array_values($id);
}

/**
 * Controlla che l'id sia ben formato E presente nell'elenco delle sezioni.
 * Va chiamata prima di qualunque lettura o scrittura.
 */
function verificaSezione(string $id): void
{
    if (!idSezioneValido($id) || !in_array($id, elencoSezioni(), true)) {
        throw new RuntimeException('Sezione non valida o inesistente.', 404);
    }
}

/**
 * Legge una sezione e restituisce il JSON decodificato come OGGETTO
 * (non come array associativo): così un "{}" vuoto resta un oggetto
 * e non diventa "[]" quando viene riscritto.
 */
function leggiSezione(string $id): object
{
    verificaSezione($id);

    $percorso = percorsoSezione($id);

    if (!is_readable($percorso)) {
        throw new RuntimeException('Il file della sezione non è leggibile.', 500);
    }

    $contenuto = file_get_contents($percorso);

    if ($contenuto === false) {
        throw new RuntimeException('Impossibile leggere il file della sezione.', 500);
    }

    $dati = json_decode($contenuto);

    if (json_last_error() !== JSON_ERROR_NONE || !is_object($dati)) {
        throw new RuntimeException('Il file della sezione non contiene un JSON valido.', 500);
    }

    return $dati;
}

/**
 * Salva i dati di una sezione, sovrascrivendo il file esistente.
 *
 * SCRITTURA "ATOMICA": si scrive prima in un file temporaneo nella stessa
 * cartella e poi lo si rinomina sul file vero. Così, se il processo si
 * interrompe a metà, il file del sito non resta mai mezzo scritto.
 * (Il temporaneo si chiama ".salvataggio-XXXX", senza estensione .json,
 * quindi elencoSezioni() non lo scambia per una sezione.)
 *
 * FORMATO: JSON indentato con 4 spazi, caratteri accentati e slash non
 * escapati: i file restano leggibili e i diff in Git puliti.
 */
function salvaSezione(string $id, object $dati): void
{
    verificaSezione($id);

    // Per rinominare il file serve poter scrivere nella cartella.
    if (!is_writable(CARTELLA_CONTENUTI)) {
        throw new RuntimeException('La cartella dei contenuti non è scrivibile. Verifica i permessi.', 500);
    }

    $json = json_encode($dati, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if ($json === false) {
        throw new RuntimeException('Errore nella conversione del JSON.', 500);
    }

    $temporaneo = tempnam(CARTELLA_CONTENUTI, '.salvataggio-');

    if ($temporaneo === false) {
        throw new RuntimeException('Impossibile creare il file temporaneo.', 500);
    }

    if (file_put_contents($temporaneo, $json . PHP_EOL, LOCK_EX) === false) {
        unlink($temporaneo);
        throw new RuntimeException('Impossibile scrivere il file. Verifica i permessi.', 500);
    }

    // tempnam() crea il file con permessi 0600: li allarghiamo a 0644 così
    // anche il sito (che potrebbe girare con un altro utente) può leggerlo.
    chmod($temporaneo, 0644);

    if (!rename($temporaneo, percorsoSezione($id))) {
        unlink($temporaneo);
        throw new RuntimeException('Impossibile sostituire il file della sezione.', 500);
    }
}