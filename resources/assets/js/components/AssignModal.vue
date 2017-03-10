<template>
	<div class="modal is-active">
		<div class="modal-background"></div>
		<div class="modal-content">
			<div class="box">
				<h1 class="title has-text-centered">{{ ticket.title }}</h1>
				<h2 class="subtitle has-text-centered">Assign ticket</h2>
				<hr>
				<ul class="user-select-list">
					<h2 v-if="selectedUsers.length == 0" class="has-text-centered">Select users</h2>
					<h2 v-else class="has-text-centered">{{ selectedUsers.length }} users selected</h2><br>
					<li v-for="user in users" class="user-select-list-item">
						<img :src="user.avatar" alt="" class="img-circle" /> 
						<span>{{ user.name }}</span>
						<a v-show="!user.selected" class="button assign-button animate" @click="assignUser(user)"><i class="fa fa-plus icon is-small"></i></a>
						<a v-show="user.selected" class="button success-button assign-button animate is-green" @click="unAssignUser(user)"><i class="fa fa-check icon is-small is-green"></i></a>
					</li>
				</ul>
				<div class="button-controls">
					<button class="button is-success button-centered" @click="save">Save</button>
				</div>
			</div>
		</div>
		<button class="modal-close" @click="cancel"></button>
	</div>
</template>

<script>
	export default{
		mounted(){
			this.fetchUsers();
		},
		props: ['ticket'],
		data(){
			return {
				users: [],
				selectedUsers: []
			}
		},
		methods:{
			fetchUsers(){
				axios.get('/users').then((response) => {
					this.users = response.data;

					axios.get('/ticket/'+this.ticket.id+'/users').then((response) => {
						this.selectedUsers = response.data;
						let that = this;
						_.forEach(this.selectedUsers, (val1) => {
							_.forEach(that.users, (val2) => {
								if(val1.id == val2.id){
									val2.selected = true;
								}
							});
						});
					});
				});
			},
			save(){
				Event.$emit('users-assigned');
			},
			assignUser(user){
				user.selected = 1;
				this.selectedUsers.push(user);
				axios.get('/ticket/'+this.ticket.id+'/assign/'+user.id);
				Event.$emit('user-assigned', {'user': user});
			},
			unAssignUser(user){
				user.selected = 0;
				this.selectedUsers = _.reject(this.selectedUsers, function (value){
					return value.id == user.id;
				});
				axios.get('/ticket/'+this.ticket.id+'/unassign/'+user.id);
				Event.$emit('user-unassigned', {'user': user});
			},
			cancel(){
				Event.$emit('users-assign-cancel');
			}
		}
	}
</script>