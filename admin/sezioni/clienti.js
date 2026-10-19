const CLIENTI = {
  id: 'clienti',
  titolo: 'Clienti',

  schema: {
    title: 'Clienti',
    type: 'object',
    required: ['badge', 'titolo', 'sottotitolo', 'lista'],
    options: {
      titleHidden: false,
      disable_collapse: true
    },

    definitions: {
      cliente: {
        type: 'object',
        title: 'Cliente',
        required: ['nome', 'logo'],
        properties: {
          nome: { type: 'string', title: 'Nome', maxLength: 150, propertyOrder: 1 },
          // Per ora testo libero (es. "logo-tim.svg"): diventerà un logopicker
          logo: { type: 'string', title: 'File logo', maxLength: 200, propertyOrder: 2 }
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
        title: 'Clienti',
        format: 'table',
        propertyOrder: 4,
        minItems: 1,
        items: { $ref: '#/definitions/cliente' },
        options: {
          disable_array_delete_all_rows: true,
          disable_array_delete_last_row: true,
          array_controls_top: true
        }
      }
    }
  }
};