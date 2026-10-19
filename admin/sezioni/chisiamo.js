const CHISIAMO = {
  id: 'chisiamo',
  titolo: 'Chi siamo',

  schema: {
    title: 'Chi siamo',
    type: 'object',
    required: ['badge', 'titolo', 'paragrafo', 'img_alt', 'timeline'],
    options: {
      titleHidden: false,
      disable_collapse: true
    },

    definitions: {
      tappa: {
        type: 'object',
        title: 'Tappa',
        headerTemplate: '{{ i1 }}. {{ self.anno }}',
        required: ['icon', 'anno', 'testo'],
        properties: {
          icon: {
            type: 'string',
            title: 'Icona',
            format: 'iconpicker',
            options: { iconFolder: 'timeline' },  // letta dal picker nel passo "iconpicker per cartella"
            propertyOrder: 1
          },
          anno: { type: 'string', title: 'Anno', maxLength: 10, propertyOrder: 2 },
          testo: { type: 'string', title: 'Testo', maxLength: 200, propertyOrder: 3 }
        }
      }
    },

    properties: {
      badge: { type: 'string', title: 'Badge', maxLength: 60, propertyOrder: 1 },
      titolo: { type: 'string', title: 'Titolo', maxLength: 200, propertyOrder: 2 },
      paragrafo: {
        type: 'string',
        title: 'Paragrafo',
        format: 'textarea',
        maxLength: 2000,
        propertyOrder: 3
      },
      img_alt: {
        type: 'string',
        title: 'Testo alternativo immagine',
        maxLength: 200,
        propertyOrder: 4
      },
      timeline: {
        type: 'array',
        title: 'Timeline',
        propertyOrder: 5,
        minItems: 3,
        maxItems: 3,   // il layout del sito è a 3 colonne (col-4)
        items: { $ref: '#/definitions/tappa' },
        options: {
          disable_array_delete_all_rows: true,
          disable_array_delete_last_row: true,
          array_controls_top: true
        }
      }
    }
  }
};