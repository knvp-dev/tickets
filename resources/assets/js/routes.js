import VueRouter from 'vue-router';

let routes = [
	{
		path: '/',
		component: require('./views/Tickets')
	},
	{
		path: '/my-tickets',
		component: require('./views/MyTickets')
	},
	{
		path: '/archive',
		component: require('./views/Archive')
	},
	{
		path: '/ticket/:id',
		component: require('./views/TicketDetail')
	}
];

export default new VueRouter({
	routes
})