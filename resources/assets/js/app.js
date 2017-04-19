
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

// import router from './routes';

// Vue.component('selectbox', require('./components/Selectbox.vue'));
// Vue.component('assign-modal', require('./components/AssignModal.vue'));
// Vue.component('confirmation-modal', require('./components/ConfirmationModal.vue'));
// Vue.component('todos', require('./components/Todos.vue'));
// Vue.component('messages', require('./components/Messages.vue'));
// Vue.component('ticket-detail', require('./views/TicketDetail.vue'));
// Vue.component('tickets', require('./views/Tickets.vue'));
Vue.component('CheckoutForm', require('./components/CheckoutForm.vue'));
window.Event = new Vue({});

const app = new Vue({
    el: '#app',
    data(){
    	return{
    		AuthUser: {},
            activeTeam: ''
    	}
    },
    created(){
        for(i = 0; i < $('.ticket-item').length ; i++){
            let count = $('.ticket-item').eq(i).find('ul li').length;
            for (j = 0; j < count; j++) {
                $('.ticket-item').eq(i).find('.ticket-member').eq(j).css('left','calc('+j+' * -20px)');
            }
        }
    }
});

Vue.directive('diff-for-humans', function(el, binding){
	el.innerHTML = moment(binding.value).fromNow();
});
