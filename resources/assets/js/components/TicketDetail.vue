<template>
<div class="container">
	<section class="section">

		<a href="/"><i class="fa fa-arrow-left"></i> Back</a>

		<div class="ticket-detail-wrapper">
			<h1 class="title">{{ ticket.title }}</h1>
			<!-- <ul>
				<li v-for="todo in todos">{{ todo.body }}</li>
			</ul> -->
		</div>
	</section>
</div>
	
</template>
<script>
	export default{
		mounted(){
			console.log(this.ticket);
		},
		props: ["data"],
		data(){
			return{
				ticket: JSON.parse(this.data)
			}
		},
		methods:{
			
		}
	}
</script>

<style>
	
</style>