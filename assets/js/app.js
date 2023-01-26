import Vue from "vue";
import api from "./api.js";
import App from './components/App';
import Alert from "./components/layout/AlertComponent";
import Multiselect from 'vue-multiselect';

import router from './routes';

Vue.component('alert', Alert);
Vue.component('multiselect', Multiselect);
Vue.prototype.$axios = api;

const app = new Vue({
  el: '#app',
  router,
  components: {Alert, Multiselect},
  render: h => h(App)
});

export default app;