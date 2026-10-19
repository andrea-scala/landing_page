<?php
declare(strict_types=1);

/**
 * CONFIGURAZIONE DELL'EDITOR (zona admin)
 * =======================================
 * Qui stanno le informazioni "di ambiente": dove si trovano i contenuti,
 * dove sta la cartella privata e i parametri di sicurezza del login.
 *
 * __DIR__ è la cartella che contiene QUESTO file (admin/), quindi i percorsi
 * non dipendono da dove viene avviato PHP.
 */

// ---------------------------------------------------------------------------
// CONTENUTI DEL SITO
// ---------------------------------------------------------------------------
// I JSON veri del sito stanno in content/, cartella "sorella" di admin/.
// Da admin/ ci si arriva con '..' (la cartella sopra): admin/../content.
// ATTENZIONE: l'editor scrive direttamente sui file che il sito mostra.
// SENZA slash finale.
const CARTELLA_CONTENUTI = __DIR__ . '/../content';

// ---------------------------------------------------------------------------
// CARTELLA PRIVATA (fuori dalla web root: mai raggiungibile via URL)
// ---------------------------------------------------------------------------
// Da admin/ si sale di DUE livelli e si entra in private/:
//   - sul server: public/admin  -> citytelsistem_sito/private
//   - in locale:  landing_page/admin -> "Progetti CityTel"/private
// Lo stesso percorso relativo funziona in entrambi i casi.
const CARTELLA_PRIVATA = __DIR__ . '/../../private';

// File con l'hash della password admin (creato a mano, mai in git).
const FILE_SEGRETO_ADMIN = CARTELLA_PRIVATA . '/admin-secret.php';

// Dove PHP salva le sessioni dell'admin. Una cartella nostra, e non quella di
// sistema, per due motivi: le sessioni non sono visibili agli altri siti sullo
// stesso server, e non vengono cancellate in anticipo dalla pulizia automatica
// di Ubuntu (che rimuove quelle inattive da ~24 minuti).
const CARTELLA_SESSIONI = CARTELLA_PRIVATA . '/sessioni';

// Contatori dei tentativi di login falliti (un piccolo file per ogni IP).
const CARTELLA_TENTATIVI = CARTELLA_PRIVATA . '/rate-limit';

// ---------------------------------------------------------------------------
// PARAMETRI DI SICUREZZA
// ---------------------------------------------------------------------------
// Dopo MAX_TENTATIVI_LOGIN password sbagliate da uno stesso IP, il login
// resta bloccato per FINESTRA_TENTATIVI_SECONDI (900 = 15 minuti).
const MAX_TENTATIVI_LOGIN = 5;
const FINESTRA_TENTATIVI_SECONDI = 900;

// Sessione: esce dopo 1 ora senza richieste, e comunque dopo 8 ore dal login.
const SESSIONE_INATTIVITA_SECONDI = 3600;
const SESSIONE_DURATA_MASSIMA_SECONDI = 28800;