require("./bootstrap");
import { createApp } from "vue";
import OrderForm from "./components/OrderForm";
const app = createApp({});
app.component("OrderForm", OrderForm);
app.mount("#app");
