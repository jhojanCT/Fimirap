require('./vue-asset');
Vue.component('create-filter', require('./components/filter/CreateFilter.vue'));
Vue.component('view-filter', require('./components/filter/ViewFilter.vue'));

var app = new Vue({
    el: '#inventory'
});