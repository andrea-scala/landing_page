const FOOTER = (() => {
  // Funzioni (e non oggetti condivisi) per dare a ogni array le sue opzioni
  const opzioniArray = () => ({
    disable_array_delete_all_rows: true,
    disable_array_delete_last_row: true,
    array_controls_top: true
  });

  const voci = (ref) => ({
    type: 'array',
    title: 'Voci',
    format: 'table',
    propertyOrder: 2,
    minItems: 1,
    items: { $ref: '#/definitions/' + ref },
    options: opzioniArray()
  });

  return {
    id: 'footer',
    titolo: 'Footer',

    schema: {
      title: 'Footer',
      type: 'object',
      required: ['info', 'mappa', 'sezioni', 'contatti', 'social', 'legale'],
      options: {
        titleHidden: false,
        disable_collapse: true
      },

      definitions: {
        // Voce dell'indirizzo: "titolo" e "href" sono facoltativi
        infoVoce: {
          type: 'object',
          title: 'Voce',
          required: ['testo'],
          options: { remove_empty_properties: true },
          properties: {
            titolo: { type: 'string', title: 'Etichetta', maxLength: 60, propertyOrder: 1 },
            testo: { type: 'string', title: 'Testo', maxLength: 200, propertyOrder: 2 },
            // href: { type: 'string', title: 'Link', maxLength: 300, propertyOrder: 3 }
          }
        },
        link: {
          type: 'object',
          title: 'Link',
          required: ['titolo', 'href'],
          properties: {
            titolo: { type: 'string', title: 'Titolo', maxLength: 80, propertyOrder: 1 },
            href: { type: 'string', title: 'Link', maxLength: 300, propertyOrder: 2 }
          }
        },
        contatto: {
          type: 'object',
          title: 'Contatto',
          required: ['titolo', 'href', 'testo'],
          properties: {
            titolo: { type: 'string', title: 'Etichetta', maxLength: 60, propertyOrder: 1 },
            href: { type: 'string', title: 'Link', maxLength: 300, propertyOrder: 2 },
            testo: { type: 'string', title: 'Testo', maxLength: 200, propertyOrder: 3 }
          }
        },
        social: {
          type: 'object',
          title: 'Social',
          required: ['titolo', 'href', 'icona', 'ariaLabel'],
          properties: {
            titolo: { type: 'string', title: 'Titolo', maxLength: 60, propertyOrder: 1 },
            href: { type: 'string', title: 'Link', maxLength: 600, propertyOrder: 2 },
            // Valori = nomi dei file in assets/img/icons/social/
            icona: {
              type: 'string',
              title: 'Icona',
              enum: ['fb', 'x', 'linkedin', 'wapp', 'mail'],
              propertyOrder: 3
            },
            ariaLabel: { type: 'string', title: 'Etichetta accessibile', maxLength: 120, propertyOrder: 4 }
          }
        }
      },

      properties: {
        info: {
          type: 'object',
          title: 'Dati azienda',
          propertyOrder: 1,
          required: ['titolo', 'voci'],
          properties: {
            titolo: { type: 'string', title: 'Titolo', maxLength: 100, propertyOrder: 1 },
            voci: voci('infoVoce')
          }
        },
        mappa: {
          type: 'object',
          title: 'Mappa',
          propertyOrder: 2,
          required: ['src', 'titolo'],
          properties: {
            src: { type: 'string', title: 'URL di incorporamento', maxLength: 600, propertyOrder: 1 },
            titolo: { type: 'string', title: 'Titolo accessibile', maxLength: 200, propertyOrder: 2 }
          }
        },
        sezioni: {
          type: 'object',
          title: 'Sezioni',
          propertyOrder: 3,
          required: ['titolo', 'voci'],
          properties: {
            titolo: { type: 'string', title: 'Titolo', maxLength: 100, propertyOrder: 1 },
            voci: voci('link')
          }
        },
        contatti: {
          type: 'object',
          title: 'Contatti',
          propertyOrder: 4,
          required: ['titolo', 'voci'],
          properties: {
            titolo: { type: 'string', title: 'Titolo', maxLength: 100, propertyOrder: 1 },
            voci: voci('contatto')
          }
        },
        social: {
          type: 'object',
          title: 'Social',
          propertyOrder: 5,
          required: ['titolo', 'voci'],
          properties: {
            titolo: { type: 'string', title: 'Titolo', maxLength: 100, propertyOrder: 1 },
            voci: voci('social')
          }
        },
        legale: {
          type: 'object',
          title: 'Note legali',
          propertyOrder: 6,
          required: ['copyright', 'voci'],
          properties: {
            copyright: { type: 'string', title: 'Copyright', maxLength: 200, propertyOrder: 1 },
            voci: voci('link')
          }
        }
      }
    }
  };
})();