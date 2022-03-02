require('./bootstrap');

import Vue from 'vue';

// register VUE_EVENT
window.VUE_EVENT = new class {
    constructor() { this.vue = new Vue(); }
    dispatch(event, data = null) { this.vue.$emit(event, data); }
    listen(event, callback) { this.vue.$on(event, callback); }
    destroy(event, callback) { this.vue.$off(event, callback); }
};

// import laravel vue datatables
import DataTable from 'laravel-vue-datatable';
Vue.use(DataTable);

// load component
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('my-task', require('./components/TaskComponent.vue').default);

const app = new Vue({
    el: '#app',
});
