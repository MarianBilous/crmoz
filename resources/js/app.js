import './bootstrap';
import {createApp} from 'vue';

import CreateDealAndAccount from "./components/CreateDealAndAccount.vue";

const app = createApp({});

app.component('create-deal-to-account', CreateDealAndAccount);


import Oruga from '@oruga-ui/oruga-next';
import {bulmaConfig} from '@oruga-ui/theme-bulma'


app.use(Oruga, bulmaConfig)
app.mount('#app');
