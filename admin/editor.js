/* ==========================================================================
   EDITOR DEI CONTENUTI · logica principale
   ==========================================================================
   Cosa fa, in breve:
   1. All'avvio chiede al server l'elenco delle sezioni (sezioni.php) e
      riempie il menu a tendina nella navbar.
   2. Quando si sceglie una sezione, legge il suo JSON (leggi.php) e crea
      JSON Editor con lo SCHEMA di quella sezione (sezioni/<id>.js).
   3. Il pulsante "Salva sul server" invia il JSON a salva.php.

   Regole importanti:
   - La fonte dei contenuti è SEMPRE il server (i file in content/).
     Se la lettura fallisce, l'editor non si apre e Salva resta disabilitato,
     per non sovrascrivere un file vero con dati sbagliati.
   - Gli schemi (cioè i form) vivono nei file sezioni/*.js, caricati da
     index.html PRIMA di questo file.
   ========================================================================== */

// --------------------------------------------------------------------------
// Elementi della pagina
// --------------------------------------------------------------------------
const contenitore = document.getElementById('editor_holder');
const output = document.getElementById('output');
const stato = document.getElementById('check');
const selettore = document.getElementById('sezione-select');
const pulsanteCopia = document.getElementById('btn-copy');
const pulsanteReset = document.getElementById('btn-reset');
const pulsanteSalva = document.getElementById('btn-save');
// Token CSRF: il server lo scrive nella pagina (meta "csrf-token") e lo
// vuole indietro, nell'intestazione X-CSRF-Token, a ogni salvataggio.
const tokenCsrf = document.querySelector('meta[name="csrf-token"]').content;

// --------------------------------------------------------------------------
// Messaggi di validazione in italiano
// --------------------------------------------------------------------------
// JSON Editor mostra i messaggi in inglese. Qui li sostituiamo; le chiavi
// non definite restano in inglese (fallback automatico).
// "error_notset" = campo obbligatorio vuoto o mancante (era "Property must be set.").
JSONEditor.defaults.languages.it = {
  error_notset: 'Campo obbligatorio.',
  error_minLength: 'Inserisci almeno {{0}} caratteri.',
  error_maxLength: 'Massimo {{0}} caratteri.',
  error_minItems: 'Servono almeno {{0}} elementi.',
  error_maxItems: 'Massimo {{0}} elementi.'
};
JSONEditor.defaults.language = 'it';


// --------------------------------------------------------------------------
// Registro degli schemi
// --------------------------------------------------------------------------
// Ogni file sezioni/<id>.js definisce una costante { id, titolo, schema }.
// L'ORDINE di questa lista è l'ordine in cui compaiono nel menu (= ordine
// delle sezioni nel sito). Per aggiungere una sezione: creare il suo file
// in sezioni/, includerlo in index.html e aggiungere qui la costante.
const SEZIONI = [HERO, SERVIZI, CHISIAMO, CLIENTI, LAVORI, FOOTER];

// Per ritrovare uno schema dal suo id: sezioniPerId.get('hero')
const sezioniPerId = new Map(SEZIONI.map(sezione => [sezione.id, sezione]));

// --------------------------------------------------------------------------
// Stato dell'editor
// --------------------------------------------------------------------------
let editor = null;          // istanza corrente di JSON Editor (null se non c'è)
let editorPronto = false;   // true quando JSON Editor ha finito di costruire il form
let sezioneAttiva = null;   // id della sezione mostrata (es. 'hero')
let datiSalvati = null;     // ultimo JSON "salvato" (base del confronto Salvato/Modificato)

// --------------------------------------------------------------------------
// Funzioni di utilità
// --------------------------------------------------------------------------

// Copia indipendente di un valore JSON (evita che due variabili condividano
// lo stesso oggetto).
function clona(valore) {
  return JSON.parse(JSON.stringify(valore));
}

// Aggiorna il badge di stato nella navbar.
// variante: 'secondary' | 'success' | 'warning' | 'danger'
function impostaStato(testo, variante) {
  stato.textContent = testo;
  stato.className = 'badge text-bg-' + variante;
}

// Abilita o disabilita i pulsanti che hanno senso solo con un editor aperto.
function abilitaAzioni(abilitate) {
  pulsanteCopia.disabled = !abilitate;
  pulsanteReset.disabled = !abilitate;
  pulsanteSalva.disabled = !abilitate;
}

// Mostra un messaggio d'errore al posto del form.
// Si usa textContent (non innerHTML) per non interpretare il testo come HTML.
function mostraErrore(testo) {
  const avviso = document.createElement('div');
  avviso.className = 'alert alert-danger mb-0';
  avviso.setAttribute('role', 'alert');
  avviso.textContent = testo;
  contenitore.replaceChildren(avviso);
}

// Chiama un endpoint PHP e restituisce la risposta già decodificata.
// Lancia un Error (con un messaggio leggibile) se:
//   - la risposta non è JSON (es. errore PHP, pagina 500 del server);
//   - il server risponde con success:false o con uno status di errore.
async function chiediJson(url, opzioni = {}) {
  const risposta = await fetch(url, opzioni);

  // 401 = sessione scaduta. Non si rimanda al login da qui: chi sta salvando
  // perderebbe le modifiche. Si avvisa, e si lascia il tempo di copiare il JSON.
  if (risposta.status === 401) {
    throw new Error(
      'Sessione scaduta. Usa «Copia JSON» per non perdere le modifiche, ' +
      'poi accedi di nuovo da /admin/login.php e ricarica la pagina.'
    );
  }

  let risultato;
  try {
    risultato = await risposta.json();
  } catch (errore) {
    throw new Error(`Risposta non valida dal server (HTTP ${risposta.status}).`);
  }

  if (!risposta.ok || !risultato.success) {
    throw new Error(risultato.messaggio || `Errore del server (HTTP ${risposta.status}).`);
  }

  return risultato;
}

// True se nell'editor aperto ci sono modifiche non ancora salvate.
function ciSonoModifiche() {
  if (!editor || !editorPronto) return false;
  return JSON.stringify(editor.getValue()) !== JSON.stringify(datiSalvati);
}

// --------------------------------------------------------------------------
// Pannello JSON e badge Salvato/Modificato
// --------------------------------------------------------------------------
function aggiornaOutput() {
  if (!editor || !editorPronto) return;

  const dati = editor.getValue();

  // Pannello a destra: il JSON risultante, formattato.
  output.textContent = JSON.stringify(dati, null, 4);

  const invariato = JSON.stringify(dati) === JSON.stringify(datiSalvati);
  impostaStato(invariato ? 'Salvato' : 'Modificato', invariato ? 'success' : 'warning');
}

// --------------------------------------------------------------------------
// Creazione e distruzione dell'editor
// --------------------------------------------------------------------------

// Elimina l'editor corrente (se c'è) e svuota la pagina.
function distruggiEditor() {
  if (editor) {
    editor.destroy();
  }
  editor = null;
  editorPronto = false;
  datiSalvati = null;
  contenitore.replaceChildren();
  output.textContent = '';
}

// Crea JSON Editor per una sezione, partendo dai dati letti dal server.
function creaEditor(sezione, dati) {
  const nuovo = new JSONEditor(contenitore, {
    schema: sezione.schema,
    startval: dati,
    theme: 'bootstrap5',
    iconlib: 'bootstrap',
    remove_button_labels: false,
    no_additional_properties: true,   // chiavi non previste dallo schema vengono scartate
    disable_properties: true,
    disable_edit_json: true,
    required_by_default: true,
    prompt_before_delete: false,
    show_errors: 'change'
  });

  editor = nuovo;

  // JSON Editor costruisce il form in modo asincrono: si aspetta "ready".
  nuovo.on('ready', () => {
    // Se nel frattempo è stata scelta un'altra sezione, questo editor è già
    // stato distrutto: si ignora.
    if (editor !== nuovo) return;

    editorPronto = true;

    // La base del confronto è il valore che l'editor ha appena costruito
    // (non il JSON grezzo del file): JSON Editor può aggiungere valori di
    // default o riordinare le chiavi, e non vogliamo che compaia
    // "Modificato" prima ancora che l'utente tocchi qualcosa.
    datiSalvati = clona(nuovo.getValue());

    abilitaAzioni(true);
    nuovo.on('change', aggiornaOutput);
    aggiornaOutput();
  });
}

// --------------------------------------------------------------------------
// Caricamento di una sezione
// --------------------------------------------------------------------------
async function caricaSezione(id) {
  const sezione = sezioniPerId.get(id);

  distruggiEditor();
  abilitaAzioni(false);
  impostaStato('Caricamento…', 'secondary');

  // Durante il caricamento il menu è bloccato (evita richieste che si accavallano).
  selettore.disabled = true;
  sezioneAttiva = id;

  try {
    if (!sezione) {
      throw new Error(`Nessuno schema definito per la sezione "${id}".`);
    }

    const risultato = await chiediJson(
      'leggi.php?sezione=' + encodeURIComponent(id),
      { cache: 'no-store' }
    );

    creaEditor(sezione, risultato.dati);
  } catch (errore) {
    console.error('Errore nel caricamento della sezione:', errore);
    impostaStato('Errore', 'danger');
    mostraErrore('Impossibile aprire la sezione: ' + errore.message);
  } finally {
    selettore.disabled = false;
  }
}

// --------------------------------------------------------------------------
// Menu a tendina delle sezioni
// --------------------------------------------------------------------------
selettore.addEventListener('change', () => {
  const nuovoId = selettore.value;

  // Se ci sono modifiche non salvate, chiede conferma; se l'utente rinuncia
  // il menu torna sulla sezione che era aperta.
  if (ciSonoModifiche() &&
    !confirm('Ci sono modifiche non salvate. Cambiando sezione andranno perse. Continuare?')) {
    selettore.value = sezioneAttiva;
    return;
  }

  caricaSezione(nuovoId);
});

// --------------------------------------------------------------------------
// Pulsante "Copia JSON"
// --------------------------------------------------------------------------
pulsanteCopia.addEventListener('click', async () => {
  try {
    await navigator.clipboard.writeText(output.textContent);

    const testoOriginale = pulsanteCopia.innerHTML;
    pulsanteCopia.innerHTML = '<i class="bi bi-check2"></i> Copiato';

    setTimeout(() => {
      pulsanteCopia.innerHTML = testoOriginale;
    }, 1200);
  } catch (errore) {
    alert('Impossibile copiare il JSON. Selezionalo e copialo manualmente.');
  }
});

// --------------------------------------------------------------------------
// Pulsante "Ripristina": annulla le modifiche e torna all'ultima versione salvata
// --------------------------------------------------------------------------
pulsanteReset.addEventListener('click', () => {
  if (!editor || !editorPronto) return;

  const conferma = confirm(
    'Vuoi annullare le modifiche non salvate e recuperare l’ultima versione salvata?'
  );

  if (!conferma) return;

  editor.setValue(clona(datiSalvati));
});

// --------------------------------------------------------------------------
// Pulsante "Salva sul server"
// --------------------------------------------------------------------------
pulsanteSalva.addEventListener('click', async () => {
  if (!editor || !editorPronto) return;
  // Se ci sono campi non validi, mostra gli errori e NON salva.
  const errori = editor.validate();
  if (errori.length > 0) {
    editor.showValidationErrors(errori);
    alert(`Impossibile salvare: ci sono ${errori.length} campi da correggere (evidenziati nel form).`);
    return;
  }
  const testoOriginale = pulsanteSalva.innerHTML;

  try {
    pulsanteSalva.disabled = true;
    pulsanteSalva.innerHTML =
      '<span class="spinner-border spinner-border-sm"></span> Salvataggio...';

    // Il valore viene letto UNA volta: quello inviato è quello che poi
    // diventa il nuovo "salvato".
    const dati = editor.getValue();

    const risultato = await chiediJson(
      'salva.php?sezione=' + encodeURIComponent(sezioneAttiva),
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': tokenCsrf
        },
        body: JSON.stringify(dati)
      }
    );

    datiSalvati = clona(dati);
    aggiornaOutput();
    alert(risultato.messaggio);
  } catch (errore) {
    alert('Salvataggio non riuscito: ' + errore.message);
  } finally {
    pulsanteSalva.disabled = false;
    pulsanteSalva.innerHTML = testoOriginale;
  }
});

// --------------------------------------------------------------------------
// Avvio
// --------------------------------------------------------------------------
async function avvia() {
  abilitaAzioni(false);

  try {
    const { sezioni: idServer } = await chiediJson('sezioni.php', { cache: 'no-store' });

    // Sezioni modificabili: hanno sia il file sul server sia lo schema.
    // Si mantiene l'ordine del registro SEZIONI.
    const disponibili = SEZIONI.filter(sezione => idServer.includes(sezione.id));

    // File presenti sul server ma senza schema: compaiono nel menu, disabilitati.
    const senzaSchema = idServer.filter(id => !sezioniPerId.has(id));

    // Schemi senza file sul server: non compaiono, ma lo segnaliamo in console.
    SEZIONI
      .filter(sezione => !idServer.includes(sezione.id))
      .forEach(sezione =>
        console.warn(`Schema "${sezione.id}" definito ma file content/${sezione.id}.json non trovato.`)
      );

    if (disponibili.length === 0) {
      throw new Error('Nessuna sezione modificabile trovata sul server.');
    }

    // Costruisce il menu.
    selettore.replaceChildren();

    disponibili.forEach(sezione =>
      selettore.add(new Option(sezione.titolo, sezione.id))
    );

    senzaSchema.forEach(id => {
      const voce = new Option(`${id} (schema mancante)`, id);
      voce.disabled = true;
      selettore.add(voce);
    });

    // Apre la prima sezione.
    selettore.value = disponibili[0].id;
    await caricaSezione(disponibili[0].id);
  } catch (errore) {
    console.error('Errore all’avvio:', errore);
    impostaStato('Errore', 'danger');
    mostraErrore('Impossibile avviare l’editor: ' + errore.message);
  }
}

avvia();