import Vue from "vue";
import Account from "./components/page/AccountOverview";
import Alert from "./components/layout/AlertComponent";


Vue.component('alert', Alert);

const app = new Vue({
  el: "#app",
  components: { Alert, Account },
});

export default app;