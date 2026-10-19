
const TABLER_VERSION = '3.34.0';
const TABLER_BASE =
  `https://cdn.jsdelivr.net/npm/@tabler/icons@${TABLER_VERSION}/icons/outline/`;
const TABLER_CATALOG =
  `https://cdn.jsdelivr.net/npm/@tabler/icons@${TABLER_VERSION}/icons.json`;

const ICONE_INIZIALI = 60;
const ICONE_PER_CARICAMENTO = 10;

let tablerIconNamesPromise = null;

function caricaNomiIconeTabler() {
  if (!tablerIconNamesPromise) {
    tablerIconNamesPromise = fetch(TABLER_CATALOG)
      .then(risposta => {
        if (!risposta.ok) {
          throw new Error('Impossibile scaricare il catalogo Tabler.');
        }
        return risposta.json();
      })
      .then(catalogo => {
        let nomi;

        if (Array.isArray(catalogo)) {
          nomi = catalogo.map(icona =>
            typeof icona === 'string' ? icona : icona.name
          );
        } else if (catalogo && typeof catalogo === 'object') {
          nomi = Object.keys(catalogo);
        } else {
          throw new Error('Formato del catalogo Tabler non riconosciuto.');
        }

        return [...new Set(nomi.filter(Boolean))].sort();
      })
      .catch(errore => {
        tablerIconNamesPromise = null;
        throw errore;
      });
  }

  return tablerIconNamesPromise;
}

class IconPickerEditor extends JSONEditor.defaults.editors.string {
  build() {
    super.build();

    this.input.style.display = 'none';

    this.nomiIcone = [];
    this.risultatiFiltrati = [];
    this.numeroMostrato = 0;
    this.caricamentoInCorso = false;
    this.ricercaCorrente = '';
    this.versioneGalleria = 0;

    this.wrap = document.createElement('div');

    this.filter = document.createElement('input');
    this.filter.type = 'search';
    this.filter.placeholder = 'Cerca icone Tabler…';
    this.filter.setAttribute('aria-label', 'Cerca icone Tabler');
    this.filter.className = 'form-control form-control-sm mb-2';

    this.filter.addEventListener('input', () => {
      this.ricercaCorrente = this.filter.value.trim().toLowerCase();
      this.filtraIcone();
    });

    this.grid = document.createElement('div');
    this.grid.className = 'iconpicker-grid';

    this.messaggio = document.createElement('div');
    this.messaggio.className = 'small text-muted mb-2';
    this.messaggio.textContent = 'Caricamento catalogo Tabler…';

    this.current = document.createElement('div');
    this.current.className = 'iconpicker-current';

    this.wrap.append(
      this.filter,
      this.messaggio,
      this.grid,
      this.current
    );

    this.input.parentNode.insertBefore(this.wrap, this.input);

    // Carica altre icone quando si raggiunge il fondo della galleria.
    this.grid.addEventListener('scroll', () => {
      const vicinoAlFondo =
        this.grid.scrollTop + this.grid.clientHeight >=
        this.grid.scrollHeight - 30;

      if (vicinoAlFondo) {
        this.mostraAltreIcone();
      }
    });

    this.highlight();
    this.caricaGalleria();
  }

  async caricaGalleria() {
    try {
      this.nomiIcone = await caricaNomiIconeTabler();

      if (!this.grid || !this.grid.isConnected) return;

      this.filtraIcone();
    } catch (errore) {
      console.error('Errore catalogo Tabler:', errore);
      this.messaggio.textContent =
        'Impossibile caricare le icone. Controlla la connessione e ricarica la pagina.';
    }
  }

  filtraIcone() {
    this.versioneGalleria++;

    const versione = this.versioneGalleria;
    const selezionata = this.input.value;

    this.risultatiFiltrati = this.nomiIcone.filter(nome =>
      nome.toLowerCase().includes(this.ricercaCorrente)
    );

    // Senza ricerca, mette per prima l'icona già selezionata.
    if (
      !this.ricercaCorrente &&
      selezionata &&
      this.risultatiFiltrati.includes(selezionata)
    ) {
      this.risultatiFiltrati = [
        selezionata,
        ...this.risultatiFiltrati.filter(nome => nome !== selezionata)
      ];
    }

    this.numeroMostrato = 0;
    this.grid.replaceChildren();

    this.mostraAltreIcone(ICONE_INIZIALI);

    // Se la galleria non è ancora abbastanza alta per scorrere,
    // aggiunge automaticamente altri gruppi da 10.
    this.riempiGalleria(versione);
  }

  riempiGalleria(versione) {
    requestAnimationFrame(() => {
      if (
        versione !== this.versioneGalleria ||
        !this.grid ||
        !this.grid.isConnected ||
        this.grid.clientHeight === 0
      ) {
        return;
      }

      const ciSonoAltreIcone =
        this.numeroMostrato < this.risultatiFiltrati.length;

      const loScrollNonÈAncoraAttivo =
        this.grid.scrollHeight <= this.grid.clientHeight + 1;

      if (ciSonoAltreIcone && loScrollNonÈAncoraAttivo) {
        this.mostraAltreIcone(ICONE_PER_CARICAMENTO);
        this.riempiGalleria(versione);
      }
    });
  }

  mostraAltreIcone(quantita = ICONE_PER_CARICAMENTO) {
    if (
      this.caricamentoInCorso ||
      !this.grid ||
      !this.grid.isConnected
    ) {
      return;
    }

    if (this.numeroMostrato >= this.risultatiFiltrati.length) {
      this.aggiornaStato();
      return;
    }

    this.caricamentoInCorso = true;

    const inizio = this.numeroMostrato;
    const fine = Math.min(
      inizio + quantita,
      this.risultatiFiltrati.length
    );

    const frammento = document.createDocumentFragment();

    for (let indice = inizio; indice < fine; indice++) {
      const nome = this.risultatiFiltrati[indice];

      const pulsante = document.createElement('button');
      pulsante.type = 'button';
      pulsante.className = 'iconpicker-btn';
      pulsante.title = nome;
      pulsante.dataset.icon = nome;
      pulsante.setAttribute('aria-label', nome);

      const icona = document.createElement('span');
      icona.className = 'iconpicker-icon';
      icona.setAttribute('aria-hidden', 'true');

      const url = TABLER_BASE + nome + '.svg';
      icona.style.setProperty('--icon-url', `url("${url}")`);

      pulsante.appendChild(icona);

      pulsante.addEventListener('click', () => {
        this.input.value = nome;
        this.input.dispatchEvent(
          new Event('change', { bubbles: true })
        );
        this.highlight();
      });

      frammento.appendChild(pulsante);
    }

    this.grid.appendChild(frammento);
    this.numeroMostrato = fine;
    this.caricamentoInCorso = false;

    this.aggiornaStato();
    this.highlight();
  }

  aggiornaStato() {
    const totale = this.risultatiFiltrati.length;
    const mostrato = this.numeroMostrato;

    this.messaggio.textContent = this.nomiIcone.length
      ? `Mostrate ${mostrato} di ${totale} icone`
      : 'Caricamento catalogo Tabler…';
  }

  setValue(value, initial, from_template) {
    const risultato = super.setValue(value, initial, from_template);
    this.highlight();
    return risultato;
  }

  highlight() {
    if (!this.grid || !this.current || !this.input) return;

    const selezionata = this.input.value;

    this.grid.querySelectorAll('button').forEach(pulsante => {
      pulsante.classList.toggle(
        'is-active',
        pulsante.dataset.icon === selezionata
      );
    });

    this.current.textContent = selezionata
      ? 'Selezionata: ' + selezionata
      : 'Nessuna icona selezionata';
  }
}

JSONEditor.defaults.editors.iconpicker = IconPickerEditor;

JSONEditor.defaults.resolvers.unshift(schema =>
  schema.type === 'string' && schema.format === 'iconpicker'
    ? 'iconpicker'
    : undefined
);
