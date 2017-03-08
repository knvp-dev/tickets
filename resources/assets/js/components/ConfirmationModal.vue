<template>
	<div class="modal" :class="(isActive) ? 'is-active' : ''">
		<div class="modal-background"></div>
		<div class="modal-content">
			<div class="box">
				<h1 class="title has-text-centered">Are you sure?</h1>
				<hr>
				<div class="button-controls">
					<button class="button is-success button-centered" @click="confirm">Yes</button>
					<button class="button button-centered" @click="decline">Cancel</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default{
		props: ['isActive'],
		methods:{
			confirm(){
				Event.$emit('modal-confirmed');
			},
			decline(){
				Event.$emit('modal-declined');
			}
		}
	}
</script>