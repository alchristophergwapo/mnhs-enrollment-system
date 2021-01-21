// import Vue from 'vue'
// import '@mdi/font/css/materialdesignicons.min.css'
// import Vuetify, {
//   VBtn, VIcon, VAppBar, VCard, VNavigationDrawer, VList, VListItem, VListItemIcon, VListItemContent, VListItemTitle,
//   VAvatar, VSystemBar, VToolbarTitle, VAppBarNavIcon, VSpacer, VListItemGroup, VCardText
// } from 'vuetify/lib'

// Vue.use(Vuetify, {
//   components: {
//     VBtn, VIcon, VAppBar, VCard, VNavigationDrawer, VList, VListItem,VListItem, VListItemIcon,VListItemContent,
//     VListItemTitle,VAvatar, VSystemBar, VToolbarTitle, VAppBarNavIcon, VSpacer, VListItemGroup, VCardText

//   }
// })

// export default new Vuetify({
//   icons: {
//     iconfont: 'mdi',
//   },
//   theme: {
//     primary: '#b71c1c',
//     secondary: '#b0bec5',
//     accent: '#8c9eff',
//     error: '#b71c1c'
//   }
// })

import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import '@mdi/font/css/materialdesignicons.min.css'

Vue.use(Vuetify)

export default new Vuetify({
    icons: {
      iconfont: 'mdi',
    }
  })