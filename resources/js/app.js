require('./bootstrap');

import Vue from 'vue'
import App from './vue/main'

const app = new Vue({
     el: '#app',
     components: { App }
});
