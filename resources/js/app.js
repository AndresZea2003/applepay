import './bootstrap';
import {createApp} from "vue/dist/vue.esm-bundler.js";
import CButton from "./components/CButton.vue";

createApp({
    components: {
        'c-button': CButton,
    }
}).mount("#app");
