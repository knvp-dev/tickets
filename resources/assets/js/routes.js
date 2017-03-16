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
	}
];

export default new VueRouter({
	routes
})