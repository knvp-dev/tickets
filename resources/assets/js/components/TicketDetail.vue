<template>
	<div>
		<div class="container">
			<section class="section">
				<div class="ticket-detail-wrapper">
					<h1 class="title">
					<i v-if="!ticket.completed" class="fa fa-circle-o pr-10 is-small-icon"></i>
					<i v-else class="fa fa-check pr-10 is-small-icon is-green"></i>
						{{ ticket.title }} <br> <span class="tag">{{ category.name }}</span>
					</h1>

					<div class="assigned-user">
						<p v-if="users.length > 0"><span v-for="user in users" class="user-avatar"><!-- <div class="tooltip">{{ user.name }}</div> --><img :src="user.avatar" class="img-circle" alt=""></span></p>
					</div>

					<hr>

					<a v-if="hasNoDescription" @click="showDescriptionInput = true">Add description</a>
					<div v-if="hasDescription && !showDescriptionInput">
						<a @click="showDescriptionInput = true">Edit description</a>
						<p v-if="hasDescription && !showDescriptionInput">{{ ticket.description }}</p>
					</div>
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
				<div class="column has-border-right">
					<todo :todos="this.ticket.todos" :ticket="this.ticket"></todo>
				</div>
				<div class="column">
					
				</div>
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
					category: [],
					priority: [],
					users: [],
					showDescriptionInput: false
				}
			},
			computed:{
				hasNoDescription(){
					return (this.ticket.description == null) ? true : false;
				},
				hasDescription(){
					return (this.ticket.description != null) ? true : false;
				}
			},
			methods:{
				fetchTicketDetails(id){
					axios.get('/ticket/detail/'+id).then((response) => {
						this.ticket = response.data;
						this.category = this.ticket.category;
						this.priority = this.ticket.priority;
						this.users = this.ticket.users;
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