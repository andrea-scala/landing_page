const SERVIZI = {
  id: 'servizi',
  titolo: 'Servizi',
  file: 'servizi.json',

  // dati: {
  //   badge: 'Servizi',
  //   titolo: 'Reti, impianti, sicurezza.',
  //   sottotitolo: 'Massima qualità e affidabilità in ogni progetto: soluzioni su misura per ogni sfida tecnologica, dalla progettazione alla messa in servizio.',

  //   evidenza: [
  //     {
  //       icon: 'route',
  //       title: 'Progettazione FTTH',
  //       items: [
  //         'Fibra ottica fino a casa del cliente',
  //         'Piani di Numerazione Internet TIM e Open Fiber',
  //         'Progettazione su misura'
  //       ]
  //     },
  //     {
  //       icon: 'terminal',
  //       title: 'Sviluppo software',
  //       items: [
  //         'Applicazioni personalizzate',
  //         "Gestionali su misura per le esigenze operative dell'azienda"
  //       ]
  //     },
  //     {
  //       icon: 'tools',
  //       title: 'Impianti',
  //       items: [
  //         'Progettazione',
  //         'Installazione',
  //         'Messa in servizio',
  //         'Collaudo'
  //       ]
  //     },
  //     {
  //       icon: 'sitemap',
  //       title: 'Cablaggi e reti LAN/WAN',
  //       items: [
  //         'Reti dati robuste e scalabili',
  //         'Manutenzione apparati attivi'
  //       ]
  //     },
  //     {
  //       icon: 'bolt',
  //       title: 'Posa e collaudo',
  //       items: [
  //         'Posa e giunzione',
  //         'Terminazione e collaudo',
  //         'Bassa latenza, alta affidabilità'
  //       ]
  //     },
  //     {
  //       icon: 'video',
  //       title: 'Videosorveglianza',
  //       items: [
  //         'Progettazione sistemi',
  //         'Controllo continuo degli ambienti'
  //       ]
  //     }
  //   ],

  //   altri: [
  //     {
  //       icon: 'sun-electricity',
  //       title: 'Impianti fotovoltaici',
  //       items: [
  //         'Installazione',
  //         'Manutenzione',
  //         'Alta efficienza energetica'
  //       ]
  //     },
  //     {
  //       icon: 'flame',
  //       title: 'Sicurezza e antincendio',
  //       items: [
  //         'Impianti antincendio',
  //         'Antintrusione',
  //         'Controllo accessi'
  //       ]
  //     },
  //     {
  //       icon: 'cone',
  //       title: 'Infr. stradali e ripristini',
  //       items: [
  //         'Opere stradali',
  //         'Ripristini a norma',
  //         'Standard di qualità e sicurezza'
  //       ]
  //     },
  //     {
  //       icon: 'plug',
  //       title: 'Cavi e precablati',
  //       items: [
  //         'Cavi preconnettorizzati',
  //         'Quadristica precablata testata e pronta all\'uso'
  //       ]
  //     }
  //   ]
  // },

  schema: {
    title: 'Servizi',
    type: 'object',
    required: [
      'badge',
      'titolo',
      'sottotitolo',
      'evidenza',
      'altri'
    ],
    options: {
      titleHidden: false,
      disable_collapse: true,
    },

    definitions: {
      card: {
        type: 'object',
        title: 'Servizio',
        headerTemplate: '{{ i1 }}. {{ self.title }}',
        required: ['icon', 'title', 'items'],
        properties: {
          icon: {
            type: 'string',
            title: 'Icona',
            format: 'iconpicker',
            propertyOrder: 1,
          },

          title: {
            type: 'string',
            title: 'Titolo',
            maxLength: 120,
            propertyOrder: 2,
          },

          items: {
            type: 'array',
            title: 'Voci',
            format: 'table',
            minItems: 1,
            propertyOrder: 2,
            options: {
              disable_collapse: true,
              disable_array_delete_all_rows: true,
              disable_array_delete_last_row: true,
              array_controls_top: true,
            },
            items: {
              type: 'string',
              title: 'Voci',
              maxLength: 300
            }
          }
        },
        options: {
          array_controls_top: true
        }
      }
    },

    properties: {
      badge: {
        type: 'string',
        title: 'Badge',
        maxLength: 60,
        propertyOrder: 1,
      },

      titolo: {
        type: 'string',
        title: 'Titolo',
        maxLength: 200,
        propertyOrder: 2
      },

      sottotitolo: {
        type: 'string',
        title: 'Sottotitolo',
        format: 'textarea',
        maxLength: 600,
        propertyOrder: 3
      },

      evidenza: {
        type: 'array',
        title: 'Servizi in evidenza',
        propertyOrder: 4,
        items: {
          $ref: '#/definitions/card'
        },
        options: {
          disable_collapse: false,
          disable_array_delete_all_rows: true,
          disable_array_delete_last_row: true,
          array_controls_top: true,
        }
      },

      altri: {
        type: 'array',
        title: 'Altri servizi',
        propertyOrder: 5,
        items: {
          $ref: '#/definitions/card'
        },
        options: {
          disable_collapse: false,
          disable_array_delete_all_rows: true,
          disable_array_delete_last_row: true,
          array_controls_top: true,
        }
      }
    }
  }
};
