import Vue from "vue";
import axios from "axios";
import App from './components/App';
import Alert from "./components/layout/AlertComponent";

import router from './routes';

Vue.component('alert', Alert);
Vue.prototype.$axios = axios;

const app = new Vue({
  el: '#app',
  router,
  components: {Alert},
  render: h => h(App)
});

export default app;