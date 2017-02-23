<template>
	<div>
		<div class="container">
			<section class="section">
				<div class="ticket-detail-wrapper">
					<h1 class="title">
					<i v-if="!ticket.completed" class="fa fa-circle-o pr-10 is-small-icon"></i>
					<i v-else class="fa fa-check pr-10 is-small-icon is-green"></i>
						{{ ticket.title }} <br> <span class="tag">{{ category.name }}</span>
						<button v-if="!ticket.completed" class="button button-green pull-right mr-10" @click="completeTicket">Close ticket</button>
						<button v-else class="button is-default pull-right mr-10" @click="completeTicket">Reopen ticket</button>
						<button class="button button-red pull-right mr-10" @click="showConfirmation">Remove this ticket</button>
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
					<todos :ticketid="this.$route.params.id"></todos>
				</div>
				<div class="column">
					<messages :ticketid="this.$route.params.id"></messages>
				</div>
			</div>
		</div>
	</template>
	<script>
		export default{
			mounted(){
				this.ticketid = this.$route.params.id;
				this.fetchTicketDetails(this.ticketid);
			},
			data(){
				return{
					ticket: [],
					category: [],
					priority: [],
					ticketid: null,
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
					axios.get('/ticket/'+id).then((response) => {
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
				},
				showConfirmation(){
					axios.get('/ticket/'+this.ticket.id+'/delete').then((response) => {
						this.$router.push('/');
					});
				},
				completeTicket(){
					if(!this.ticket.completed){
						this.ticket.completed = 1;
						axios.get('/ticket/'+this.ticket.id+'/complete');
					}else{
						this.ticket.completed = 0;
						axios.get('/ticket/'+this.ticket.id+'/uncomplete');
					}
				}
			}
		}
	</script>