<template>
	<div>

		<div v-if="$root.AuthUser.id == ticket.owner.id">

			<assign-modal v-if="userModalActive" :ticket="ticket"></assign-modal>

			<div class="right-sidebar-toggle" :class="(sidebarActive) ? 'expanded' : 'collapsed'" @click="sidebarActive = !sidebarActive"><i class="fa fa-cog"></i></div>

			<div class="right-sidebar owner-menu" :class="(sidebarActive) ? 'expanded' : 'collapsed'">
				<ul class="sidebar-menu">
					<li class="has-text-centered mb-20" v-if="editDeadline">
						<i class="fa fa-clock-o is-small-icon"></i> 
						<span>Set deadline:</span>
						<div class="deadline-form is-flex">
							<input class="input mr-10" type="date" placeholder="deadline" 
							v-model="ticket.deadline">
							<button class="button action-button" @click="updateDeadline"><i class="fa fa-check is-small-icon"></i></button>
						</div>
					</li>
					<li v-else>
						<i class="fa fa-clock-o is-small-icon"></i> 
						deadline: 
						<span v-if="ticket.deadline != null" class="tag is-danger">{{ ticket.deadline }}</span>
						<span v-else>No deadline</span>
						<button class="button action-button pull-right" @click="editDeadline = !editDeadline"><i class="fa fa-cog is-small-icon"></i></button>
					</li>
					<hr>
					<li class="sidebar-menu-item" @click="userModalActive = !userModalActive">
						<i class="fa fa-users is-small-icon"></i> 
						Edit users
					</li>
					<li class="sidebar-menu-item" v-if="!ticket.completed" @click="completeTicket">
						<i class="fa fa-check is-small-icon"></i> 
						Complete ticket
					</li>
					<li v-else class="sidebar-menu-item" @click="unCompleteTicket">
						<i class="fa fa-refresh is-small-icon"></i> 
						Reopen ticket
					</li>
					<li class="sidebar-menu-item">
						<i class="fa fa-remove is-small-icon" @click="showConfirmationModal"></i> 
						Remove ticket
					</li>
				</ul>
			</div>

		</div>

		<confirmation-modal :isActive="showConfirmation"></confirmation-modal>
		<div class="full-width-component dark-gradient h-50">
			<div class="container">
				<h1 class="title is-white">
					<i v-if="!ticket.completed" class="fa fa-circle-o pr-10 is-small-icon"></i>
					<i v-else class="fa fa-check pr-10 is-small-icon is-green"></i>
					{{ ticket.title }} <!-- <span class="tag">{{ category.name }}</span> -->
				</h1>
			</div>
		</div>
		<div class="container">
			<section class="section">
				<div class="ticket-detail-wrapper">
					<div class="assigned-user">
						<p v-if="ticket.users.length > 0"><span v-for="user in ticket.users" class="user-avatar user-avatar-detail"><div class="ticketdetailuser"><img :src="'/images/'+user.avatar" class="img-circle" alt="">{{ user.name }}</div></span></p>
						<p v-else>No users were assigned to this ticket</p>
					</div>

					<hr>

					<div>Deadline: <span v-if="ticket.deadline != null" class="tag is-danger">{{ ticket.deadline }}</span>
						<span v-else>No deadline</span>
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
					<todos :ticketid="ticket.id" :isAuthorized="userIsAuthorized"></todos>
				</div>
				<div class="column">
					<messages :ticketid="ticket.id" :isAuthorized="userIsAuthorized"></messages>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default{
		mounted(){
			//this.fetchTicketDetails();

			Event.$on('modal-confirmed', () => {
				this.removeTicket();
			});

			Event.$on('modal-declined', () => {
				this.showConfirmation = false;
			});

			Event.$on('user-assigned', (data) => {
				this.ticket.users.push(data.user);
			});

			Event.$on('user-unassigned', (data) => {
				this.ticket.users = _.reject(this.ticket.users, u => u.id == data.user.id);
			});

			Event.$on('users-assigned', () => {
				this.userModalActive = false;
			});

		},
		props: ['data'],
		data(){
			return{
				ticket: JSON.parse(this.data),
				category: [],
				owner: [],
				users: [],
				deadline: null,
				editDeadline: false,
				showDescriptionInput: false,
				showConfirmation: false,
				sidebarActive: false,
				userModalActive: false
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
				return _.some(this.ticket.users, this.$root.AuthUser);
			}
		},
		methods:{
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
				axios.get('/ticket/'+this.ticket.id+'/complete');
			},
			unCompleteTicket(){
				this.ticket.completed = 0;
				axios.get('/ticket/'+this.ticket.id+'/uncomplete');
			},
			removeTicket(){
				axios.get('/ticket/'+this.ticket.id+'/delete').then((response) => {
					this.$router.push('/');
				});
			},
			updateDeadline(){
				axios.post('/ticket/update', this.ticket).then((response) => {
					this.editDeadline = false;
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

	/* SIDEBAR */

	.right-sidebar{
		width: 280px;
		background-color: white;
		padding: 20px;
		position: absolute;
		z-index: 999;
		height: 100%;
		top: 0px;
		-webkit-box-shadow: -4px 0px 9px -5px rgba(0,0,0,0.38);
		-moz-box-shadow: -4px 0px 9px -5px rgba(0,0,0,0.38);
		box-shadow: -4px 0px 9px -5px rgba(0,0,0,0.38);
	}

	.sidebar-menu-item{
		padding-left:0px;
		padding: 10px 0;
		border-bottom: 1px solid #eee;
		transition: all .2s ease-in-out;
	}

	.sidebar-menu-item:hover {
		cursor:pointer;
		padding-left:10px;
		transition: all .2s ease-in-out;
	}

	.right-sidebar-toggle{
		position:absolute;
		background-color: #1ba59e;
		color:white;
		padding: 10px 20px;
		right:0;
		top: 100px;
		z-index:999;
		margin-right:280px;
		border-top-left-radius: 45px;
		border-bottom-left-radius: 45px;
	}

	.collapsed{
		right:-280px;
		transition: all .5s ease-in-out;
	}

	.expanded{
		right:0;
		transition: all .5s ease-in-out;
	}
</style>