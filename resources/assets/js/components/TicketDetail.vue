<template>
<div class="container">
	<section class="section">

		<a href="/"><i class="fa fa-arrow-left"></i> Back</a>

		<div class="ticket-detail-wrapper">
			<h1 class="title">{{ ticket.title }}</h1>
			<ol>
				<li v-for="todo in ticket.todos">{{ todo.body }}</li>
			</ol>
		</div>
	</section>
</div>
	
</template>
<script>
	export default{
		mounted(){
			this.fetchTicketDetails(this.ticketid);
		},
		props: ["ticketid"],
		data(){
			return{
				ticket: []
			}
		},
		methods:{
			fetchTicketDetails(id){
				axios.get('/ticket/detail/'+id).then((response) => {
					this.ticket = response.data;
				});
			}
		}
	}
</script>

<style>
	
</style>