import { createPinia } from "pinia";
// feature 2
require("./bootstrap");
import { createApp } from "vue";
import OrderForm from "./components/OrderForm";
const app = createApp({});
app.component("OrderForm", OrderForm);
app.use(createPinia());
app.mount("#app");
