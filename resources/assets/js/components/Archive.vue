<template>
	<div class="container">
		<section class="section">

			<h1 class="title">Archived tickets</h1>

			<h2 v-show="tickets.length == 0" class="has-text-centered">The archive is empty.</h2>

			<div class="ticket-list">
				<div class="ticket-list-item slideDown animated" v-for="ticket in tickets">
					<div class="list-item-icon">
						<i class="fa fa-ticket" :class="(ticket.completed) ? 'is-green' : ''"></i>
					</div>
					<div class="list-item-left">
						<p v-diff-for-humans="ticket.created_at"></p>
						<p>{{ ticket.category.name }}</p>
					</div>
					<div class="list-item-center">
						<div class="list-item-center-top">
							{{ ticket.title }}
						</div>
						<div class="list-item-center-bottom">
							{{ ticket.status.name }}
						</div>
					</div>
					<div class="list-item-right">
						<p v-if="ticket.users.length > 0"><span v-for="user in ticket.users" class="user-avatar"><div class="tooltip">{{ user.name }}</div><img :src="user.avatar" class="img-circle" alt=""></span></p>
						<a class="button action-button animate" @click="unarchiveTicket(ticket)"><i class="fa fa-refresh icon is-small"></i></a>
					</div>
				</div>
			</div>
		</section>
	</div>
</template>

<script>
	export default{
		mounted(){
			this.fetchArchivedTickets();
		},
		data(){
			return{
				tickets: []
			}
		},
		methods:{
			fetchArchivedTickets(){
				axios.get('/archive/tickets').then((response) => {
					this.tickets = response.data;
				});
			},
			unarchiveTicket(ticket){
				axios.get('/ticket/unarchive/'+ticket.id).then((response) => {
					this.fetchArchivedTickets();
				});
			}
		}
	}
</script>