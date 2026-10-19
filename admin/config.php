<?php
declare(strict_types=1);

/**
 * CONFIGURAZIONE DELL'EDITOR (zona admin)
 * =======================================
 * Qui sta l'unica informazione "di ambiente" dell'editor: dove si trovano
 * i file JSON dei contenuti.
 *
 * L'editor sta in admin/, dentro il sito. I JSON veri del sito stanno in
 * content/, che è la cartella "sorella" di admin/. Da admin/ ci si arriva
 * con '..' (la cartella sopra), quindi: admin/../content = content/.
 *
 * __DIR__ è la cartella che contiene QUESTO file (cioè admin/), quindi il
 * percorso non dipende da dove viene avviato PHP.
 *
 * ATTENZIONE: da ora l'editor scrive direttamente sui file che il sito
 * mostra al pubblico. Un salvataggio dall'editor cambia subito il sito.
 *
 * Quando passeremo a MySQL questa costante non servirà più (vedi storage.php).
 */

// Percorso della cartella dei JSON, SENZA slash finale.
const CARTELLA_CONTENUTI = __DIR__ . '/../content';