import Vue from "vue";
import Account from "./components/page/AccountOverview";
import Alert from "./components/layout/AlertComponent";
import Faq from "./components/page/FaqOverview";

Vue.component('alert', Alert);

const app = new Vue({
  el: "#app",
  components: { Alert, Account, Faq },
});

export default app;