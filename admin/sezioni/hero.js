const HERO = {
  id: 'hero',
  titolo: 'Hero',

  schema: {
    title: 'Hero',
    type: 'object',
    // "badge" è facoltativo: hero.json attuale non lo contiene
    required: ['titolo', 'sottotitolo', 'cta'],
    options: {
      titleHidden: false,
      disable_collapse: true,
      remove_empty_properties: true   // un badge vuoto non viene scritto nel JSON
    },

    definitions: {
      pulsante: {
        type: 'object',
        title: 'Pulsante',
        headerTemplate: '{{ i1 }}. {{ self.testo }}',
        required: ['testo', 'link'],
        properties: {
          testo: { type: 'string', title: 'Testo', maxLength: 60, propertyOrder: 1 },
          link: { type: 'string', title: 'Link', maxLength: 300, propertyOrder: 2 }
        }
      }
    },

    properties: {
      badge: { type: 'string', title: 'Badge', maxLength: 100, propertyOrder: 1, required: false },
      titolo: { type: 'string', title: 'Titolo', maxLength: 200, propertyOrder: 2 },
      sottotitolo: {
        type: 'string',
        title: 'Sottotitolo',
        format: 'textarea',
        maxLength: 600,
        propertyOrder: 3
      },
      cta: {
        type: 'array',
        title: 'Pulsanti',
        propertyOrder: 4,
        minItems: 1,
        items: { $ref: '#/definitions/pulsante' },
        options: {
          disable_array_delete_all_rows: true,
          disable_array_delete_last_row: true,
          array_controls_top: true
        }
      }
    }
  }
};