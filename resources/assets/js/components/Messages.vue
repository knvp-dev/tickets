<template>
	<div class="container">
		<section class="section">
			<h1 class="title">Messages</h1>

			<div class="todo-form ">
				<p class="control">
					<input type="text" class="input mr-10" placeholder="Enter message" v-model="body">
					<button class="button" @click="sendMessage">Send</button>
				</p>
				<hr>
			</div>
		</section>
	</div>
</template>
<script>
	export default{
		mounted(){
			this.fetchAuthenticatedUser();
			this.fetchMessages();
		},
		props: ['ticketid'],
		data(){
			return{
				newMessage: '',
				body: '',
				user: '',
				messages: []
			}
		},
		methods:{
			fetchAuthenticatedUser(){
				axios.get('/user').then((response) => {
					this.user = response.data;
				});
			},
			fetchMessages(){
				axios.get('/ticket/'+this.ticketid+'/messages').then((response) => {
					this.messages = response.data;
				});
			},
			sendMessage(){
				this.newMessage = {
					user_id: this.user.id,
					body: this.body
				};
				axios.post('/ticket/'+this.ticketid+'/messages/create', {'message': this.newMessage}).then((response) => {
					this.fetchMessages();
				});
				this.body = '';
			}
		}
	}
</script>