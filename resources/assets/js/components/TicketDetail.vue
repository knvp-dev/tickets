<template>
	<div>
		<div class="container">
			<section class="section">
				<div class="ticket-detail-wrapper">
					<h1 class="title">{{ ticket.title }}</h1>
					<a v-if="ticket.description == null" @click="showDescriptionInput = true">Add description</a>
					<a v-if="ticket.description != null && !showDescriptionInput" @click="showDescriptionInput = true">Edit description</a>
					<p v-if="ticket.description != null && !showDescriptionInput">{{ ticket.description }}</p>
					<div class="description-form" v-if="showDescriptionInput">
						<p class="control">
							<textarea class="textarea" placeholder="Textarea" v-model="ticket.description"></textarea>
						</p>
						<p>
							<button class="button" @click="saveDescription">Save</button>
						</p>
					</div>
				</div>
			</section>
			
			<div class="columns">
				<div class="column">
					<todo :todos="this.ticket.todos" :ticket="this.ticket"></todo>
				</div>
				<div class="column"></div>
			</div>
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
					ticket: [],
					showDescriptionInput: false
				}
			},
			methods:{
				fetchTicketDetails(id){
					axios.get('/ticket/detail/'+id).then((response) => {
						this.ticket = response.data;
					});
				},
				saveDescription(){
					axios.post('/ticket/update', this.ticket).then((response) => {
						this.showDescriptionInput = false;
					});
				}
			}
		}
	</script>