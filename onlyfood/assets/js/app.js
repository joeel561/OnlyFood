import Vue from "vue";
import axios from "axios";
import App from './components/App';
import Alert from "./components/layout/AlertComponent";
import Multiselect from 'vue-multiselect';

import router from './routes';

Vue.component('alert', Alert);
Vue.component('multiselect', Multiselect);
Vue.prototype.$axios = axios;

const app = new Vue({
  el: '#app',
  router,
  components: {Alert, Multiselect},
  render: h => h(App)
});

export default app;