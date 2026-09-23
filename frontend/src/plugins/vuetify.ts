import 'vuetify/styles'
import '@mdi/font/css/materialdesignicons.css'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

export default createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'rruEduTheme',
    themes: {
      rruEduTheme: {
        dark: false,
        colors: {
          primary: '#0F5132',
          'primary-darken-1': '#0A3622',
          'primary-lighten-1': '#198754',
          secondary: '#157347',
          accent: '#E5A823',
          surface: '#FFFFFF',
          background: '#F8FAFC',
          error: '#DC2626',
          info: '#0284C7',
          success: '#16A34A',
          warning: '#D97706',
        },
      },
    },
  },
  defaults: {
    VBtn: {
      style: 'letter-spacing: 0; text-transform: none; font-weight: 500;',
      rounded: 'lg',
    },
    VCard: {
      rounded: 'xl',
      elevation: 1,
    },
    VChip: {
      rounded: 'lg',
    },
  },
})
