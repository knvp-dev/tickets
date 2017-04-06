<template>
	<form action="/subscribe" method="post">
		<input type="hidden" name="stripeToken" v-model="stripeToken">
		<input type="hidden" name="stripeEmail" v-model="stripeEmail">
		<div class="field">
		<p class="control button-centered is-expanded">
				<span class="select" style="width:100%;">
					<select name="plans" v-model="plan" style="width:100%;">
						<option v-for="plan in plans" :value="plan.id">
							{{ plan.name }} &mdash; €{{ plan.price / 100 }}/month
						</option>
					</select>
				</span>
			</p>
		</div>
		<button type="submit" class="button blue-button button-centered" @click.prevent="subscribe">Subscribe</button>

		<p v-show="status">{{ status }}</p>
	</form>
</template>

<script>
	export default{
		props: ['plans'],
		data(){
			return {
				stripeEmail: '',
				stripeToken: '',
				plan: 1,
				status: false
			};
		},
		created(){
			this.stripe = StripeCheckout.configure({
				key: Laravel.stripeKey,
				image: "https://stripe.com/img/documentation/checkout/marketplace.png",
				locale: "auto",
				panelLabel: "Subscribe for",
				email: Laravel.user.email,
				currency: "eur",
				token: (token) => {
					this.stripeToken = token.id;
					this.stripeEmail = token.email;
					axios.post('/subscription', this.$data)
					.then((response) => {
						this.status = response.data.status;
					})
					.catch((error) => {
						this.status = error.response.data.status;
					});
				}
			})
		},
		methods:{
			subscribe(){
				let plan = this.findPlanById(this.plan);

				this.stripe.open({
					name: plan.name,
					description: plan.description,
					zipCode: false,
					amount: plan.price
				});
			},
			findPlanById(id){
				return this.plans.find(plan => plan.id == id);
			}
		}
	}
</script>