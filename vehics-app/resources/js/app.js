import './bootstrap';

import { createApp } from 'vue';
import mainAppCalculator from '../vueApp/components/mainAppCalculator.vue';

createApp({
    components: {
        mainAppCalculator
    }
}).mount('#app');