const LAVORI = {
  id: 'lavori',
  titolo: 'Lavori',

  schema: {
    title: 'Lavori',
    type: 'object',
    required: ['badge', 'titolo', 'sottotitolo', 'lista'],
    options: {
      titleHidden: false,
      disable_collapse: true
    },

    definitions: {
      lavoro: {
        type: 'object',
        title: 'Lavoro',
        headerTemplate: '{{ i1 }}. {{ self.title }}',
        required: ['img', 'title', 'description', 'url'],
        properties: {
          // Per ora testo libero, con prefisso cartella (es. "loghi/logo-sestante.svg")
          img: { type: 'string', title: 'Logo', maxLength: 200, propertyOrder: 1 },
          title: { type: 'string', title: 'Titolo', maxLength: 120, propertyOrder: 2 },
          description: {
            type: 'string',
            title: 'Descrizione',
            format: 'textarea',
            maxLength: 400,
            propertyOrder: 3
          },
          // Senza format "url": oggi i valori sono segnaposto ("https:")
          url: { type: 'string', title: 'Link al sito', maxLength: 300, propertyOrder: 4 }
        }
      }
    },

    properties: {
      badge: { type: 'string', title: 'Badge', maxLength: 60, propertyOrder: 1 },
      titolo: { type: 'string', title: 'Titolo', maxLength: 200, propertyOrder: 2 },
      sottotitolo: {
        type: 'string',
        title: 'Sottotitolo',
        format: 'textarea',
        maxLength: 600,
        propertyOrder: 3
      },
      lista: {
        type: 'array',
        title: 'Lavori',
        propertyOrder: 4,
        minItems: 1,
        items: { $ref: '#/definitions/lavoro' },
        options: {
          disable_array_delete_all_rows: true,
          disable_array_delete_last_row: true,
          array_controls_top: true
        }
      }
    }
  }
};