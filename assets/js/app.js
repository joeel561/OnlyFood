import Vue from "vue";
import App from './components/App';
import Alert from "./components/layout/AlertComponent";
import Navigation from "./components/layout/NavigationComponent";
import router from './routes';

Vue.component('alert', Alert);
Vue.component('navigation', Navigation);

const app = new Vue({
  el: '#app',
  router,
  components: {Alert, Navigation},
  render: h => h(App)
});

export default app;