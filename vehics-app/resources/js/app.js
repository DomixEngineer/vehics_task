import './bootstrap';

import { createApp } from 'vue';
import HelloVue from '../vueApp/components/HelloVue.vue';

createApp({
    components: {
        HelloVue
    }
}).mount('#app');