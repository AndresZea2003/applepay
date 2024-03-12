import './bootstrap';
import {createApp} from "vue/dist/vue.esm-bundler.js";
import ApplePay from "./components/ApplePay.vue"

createApp({
    components: {
        'apple-pay': ApplePay,
    }
}).mount("#app");
