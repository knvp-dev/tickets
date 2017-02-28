<template>
	<div class="container">
		<section class="section">
			<h1 class="title">Todo</h1>

			<div class="todo-form ">
				<p class="control">
					<input type="text" class="input mr-10" placeholder="Add new todo item" v-model="body">
					<button class="button" @click="addTodo">Add</button>
				</p>
				<hr>
			</div>

			<div class="ticket-list">
				<div class="ticket-list-item slideDown animated" v-for="todo in todos">
					<div class="list-item-icon has-small-padding">
						<i class="fa fa-circle-o" v-if="todo.completed == 0"></i>
						<i v-else class="fa fa-check is-green"></i>
					</div>
					<div class="list-item-left" style="width:330px;">
						<p>{{ todo.body }}</p>
					</div>
					<div class="list-item-right">
						<a class="button action-button animate" @click="complete(todo)">
							<i v-if="!todo.completed" class="fa fa-check icon is-small"></i>
							<i v-else class="fa fa-undo icon is-small"></i>
						</a>
						<a class="button action-button animate" @click="remove(todo)"><i class="fa fa-remove icon is-small"></i></a>
					</div>
				</div>
			</div>

		</section>
	</div>
</template>
<script>
	export default{
		mounted(){
			this.listen();
			this.fetchTodos();
		},
		props: ["ticketid"],
		data(){
			return{
				todos: [],
				todo: '',
				body: ''
			}
		},
		methods:{
			listen(){
				Echo.private('ticket.'+this.ticketid+'.todos')
					.listen('TodoCreated', event => {
						this.todos.push(event.todo);
					}).listen('TodoStatusChanged', event => {
						let todo = _.find(this.todos, { 'id': event.todo.id } );
						todo.completed = event.todo.completed;
					}).listen('TodoDeleted', event => {
						this.removeTodoFromData(event.todo.id);
					});
			},
			fetchTodos(id){
				this.todos = [];
				axios.get('/ticket/'+this.ticketid+'/todos').then((response) => {
					this.todos = response.data;
				});
			},
			complete(todo){
				if(!todo.completed){
					todo.completed = !todo.completed;
					axios.get('/todo/'+todo.id+'/complete');
				}else{
					todo.completed = !todo.completed;
					axios.get('/todo/'+todo.id+'/uncomplete');
				}
			},
			remove(todo){
				axios.delete('/todo/'+todo.id+'/delete').then((response) => {
					this.removeTodoFromData(todo.id);
				});
			},
			addTodo(){
				if(this.body != ''){
					this.todo = {
						ticket_id: this.ticketid,
						body: this.body,
						completed: 0
					};
					axios.post('/ticket/'+this.ticketid+'/todo/save', { todo: this.todo }).then((response) => {
						this.todos.push(response.data);
					});
					this.body = '';
				}
			},
			removeTodoFromData(todoId){
				this.todos = _.reject(this.todos, t => t.id == todoId);
			}
		}
	}
</script>