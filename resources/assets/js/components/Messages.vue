<template>
	<div class="container">
		<section class="section">
			<h1 class="title">Messages</h1>

			<div class="message-list">
				<div class="message-box slideInTop animated" v-for="message in messages">
					<div class="message-author">
						<img :src="'/images/'+message.user.avatar" class="img-circle" alt="">
						<p>{{ message.user.name }}</p>
					</div>
					<div class="message-box">
						<div class="message">
							<p>{{ message.body }}</p>
							<div class="message-date" v-diff-for-humans="message.created_at"></div>
						</div>
					</div>
				</div>
			</div>

			<hr>

			<div class="message-form" v-if="isAuthorized">
				<p class="control">
					<textarea type="text" class="textarea mr-10" placeholder="Enter message" v-model="body"></textarea>
				</p>
				<p class="control">
					<button class="button" @click="sendMessage">Send</button>
				</p>
			</div>

		</section>
	</div>
</template>
<script>
	export default{
		mounted(){
			this.listen();
			this.fetchMessages();
		},
		props: ['ticketid','isAuthorized'],
		data(){
			return{
				newMessage: {},
				body: '',
				messages: []
			}
		},
		watch:{
			messages(){
				this.animateMessageList();
			}
		},
		methods:{
			listen(){
				Echo.channel('ticket.'+this.ticketid+'.messages')
					.listen('MessageSent', (event) => {
						event.message.user = event.user;
						this.addMessageToData(event.message);
					});
			},
			fetchMessages(){
				axios.get('/ticket/'+this.ticketid+'/messages').then((response) => {
					this.messages = response.data;
				});
			},
			sendMessage(){
				this.buildUpMessage();
				axios.post('/ticket/'+this.ticketid+'/messages/create', this.newMessage)
				.then((response) => {
					this.addMessageToData(response.data);
					this.clearInputField();
				});
			},
			buildUpMessage(){
				this.newMessage = {
					user_id: this.$root.AuthUser.id,
					body: this.body
				};
			},
			addMessageToData(message){
				message.user = this.$root.AuthUser;
				this.messages.push(message);
			},
			clearInputField(){
				this.body = '';
			},
			animateMessageList(){
				$(".message-list").animate({ scrollTop: $('.message-list')[0].scrollHeight}, 500);
			}
		}
	}
</script>

<style>
	.message-list{
		max-height:500px;
		overflow-y:scroll;
	}

	.message-author{
		display:flex;
		justify-content: flex-start;
		align-items: center;
		margin-bottom:5px;
	}

	.message-author img{
		margin-right:10px;
	}

	.message{
		padding:10px;
		border-radius:5px;
		margin-left:35px;
		margin-right:20px;
		position:relative;
	}

	.message-date{
		position:absolute;
		right:5px;
		bottom: -20px;
		color: #b5b5b5;
	}

	.message-box{
		margin-bottom:50px;
	}
</style>