<template>
	<div>
		<confirmation-modal :isActive="showConfirmation"></confirmation-modal>
		<div class="full-width-component dark-gradient h-50">
			<div class="container">
				<h1 class="title">
					<i v-if="!ticket.completed" class="fa fa-circle-o pr-10 is-small-icon"></i>
					<i v-else class="fa fa-check pr-10 is-small-icon is-green"></i>
					{{ ticket.title }} <span class="tag">{{ category.name }}</span>
					<br>

				</h1>
			</div>
		</div>
		<div class="container">
			<section class="section">
				<div class="ticket-detail-wrapper">

					<div class="assigned-user">
						<p v-if="users.length > 0"><span v-for="user in users" class="user-avatar user-avatar-detail"><div class="ticketdetailuser"><img :src="user.avatar" class="img-circle" alt="">{{ user.name }}</div></span></p>
					</div>

					<hr>

					<a v-if="hasNoDescription && userIsAuthorized" @click="showDescriptionInput = true">Add description</a>
					<div v-if="hasDescription && !showDescriptionInput">
						<a v-if="userIsAuthorized" @click="showDescriptionInput = true">Edit description</a>
						<p v-if="hasDescription && !showDescriptionInput">{{ ticket.description }}</p>
					</div>
					<div class="description-form" v-if="showDescriptionInput && userIsAuthorized">
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
			<div v-if="$root.AuthUser.id == owner.id" class="mb-20">
				<button class="button button-red pull-right mr-10" @click="showConfirmationModal">Remove this ticket</button>
				<button v-if="!ticket.completed" class="button button-green pull-right mr-10" @click="completeTicket">Close ticket</button>
				<button v-else class="button is-default pull-right mr-10" @click="unCompleteTicket">Reopen ticket</button>
			</div>
		</div>
	</div>
</template>
<script>
	export default{
		mounted(){
			this.fetchTicketDetails();

			Event.$on('modal-confirmed', () => {
				this.removeTicket();
			});

			Event.$on('modal-declined', () => {
				this.showConfirmation = false;
			});

		},
		data(){
			return{
				ticket: [],
				ticketid: this.$route.params.id,
				category: [],
				owner: [],
				users: [],
				showDescriptionInput: false,
				showConfirmation: false
			}
		},
		computed:{
			hasNoDescription(){
				return (this.ticket.description == null) ? true : false;
			},
			hasDescription(){
				return (this.ticket.description != null) ? true : false;
			},
			userIsAuthorized(){
				return _.some(this.users, this.$root.AuthUser);
			}
		},
		methods:{
			fetchTicketDetails(){
				axios.get('/ticket/'+this.ticketid).then((response) => {
					this.ticket = response.data;
					this.category = this.ticket.category;
					this.users = this.ticket.users;
					this.owner = this.ticket.owner;
				});
			},
			saveDescription(){
				axios.post('/ticket/update', this.ticket).then((response) => {
					this.showDescriptionInput = false;
				});
			},
			showConfirmationModal(){
				this.showConfirmation = true;
			},
			completeTicket(){
				this.ticket.completed = 1;
				axios.get('/ticket/'+this.ticketid+'/complete');
			},
			unCompleteTicket(){
				this.ticket.completed = 0;
				axios.get('/ticket/'+this.ticketid+'/uncomplete');
			},
			removeTicket(){
				axios.get('/ticket/'+this.ticketid+'/delete').then((response) => {
					this.$router.push('/');
				});
			}
		}
	}
</script>

<style>
	.assigned-user{
		display:flex;
		margin-top: -20px;
	}

	.assigned-user > p{
		display:flex;
	}

	.ticketdetailuser{
		display: flex;
		width: 100%;
		height: 30px;
		align-items: center;
		background-color: #f3f3f3;
		flex-direction: row;
		border-radius: 45px;
		padding-right:10px;
	}

	.user-avatar-detail{
		margin-right: 10px;
	}

	.mb-20{
		margin-bottom:20px;
	}

	.h-50{
		height: 130px;
		min-height:130px;
		padding: 50px;
	}
</style>