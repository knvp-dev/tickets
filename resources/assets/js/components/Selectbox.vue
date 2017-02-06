<template>
<div>
	<p class="control">
		<div class="select-box-wrapper">
			<span class="select-box-choice" @click="showOptions = !showOptions">{{ activeOption }}</span>
			<transition name="fade">
				<ul class="select-box" v-if="showOptions">
					<li class="select-box-item" v-for="choice in choices" :class="(activeOption == choice.name ) ? 'is-selected' : ''" @click="setActiveOption(choice)">{{ choice.name }}</li>
				</ul>
			</transition>
		</div>
	</p>
	</div>
</template>

<script>
        export default{
            mounted(){
                Event.$on('form-submitted', () => {
                	this.activeOption = this.type;
                	this.showOptions = false;
                });
            },
            props: ['type','choices'],
            data(){
                return{
                	activeOption: this.type,
                	showOptions: false
                }
            },
            methods:{
                setActiveOption(choice){
                    this.activeOption = choice.name;
                    this.id = choice.id;
                    this.showOptions = false;
                    Event.$emit('selection-changed', {'type':this.type, 'choice':choice});
                }
            }
        }
    </script>