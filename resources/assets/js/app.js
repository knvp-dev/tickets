
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('tickets', require('./components/Tickets.vue'));
Vue.component('ticket-detail', require('./components/TicketDetail.vue'));
Vue.component('selectbox', require('./components/Selectbox.vue'));
Vue.component('assign-modal', require('./components/AssignModal.vue'));
Vue.component('archive', require('./components/Archive.vue'));

window.Event = new Vue({});

const app = new Vue({
    el: '#app',
    mounted(){
    	
    }
});

Vue.directive('diff-for-humans', function(el, binding){
	el.innerHTML = moment(binding.value).fromNow();
});
