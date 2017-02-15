<template>
    <div>

        <div class="loading-overlay">
            <section class="ctnr">
                <div class="ldr">
                    <div class="ldr-blk"></div>
                    <div class="ldr-blk an_delay"></div>
                    <div class="ldr-blk an_delay"></div>
                    <div class="ldr-blk"></div>
                </div>
            </section><br><br>
            <div class="loading-message">
                Collecting data...
            </div>
        </div>

        <div class="full-width-component dark-gradient">
            <!-- <video autoplay loop muted id="header-video">
                <source src="http://vjs.zencdn.net/v/oceans.mp4" type="video/mp4">
                </video> -->

                <div class="loader container" v-if="isLoading"></div>

                <div class="new-ticket container" v-if="!isLoading">
                    <div class="form-section control is-grouped">

                        <selectbox type="category" :choices="categories"></selectbox>

                        <p class="control">
                            <i class="fa fa-ticket is-white is-big"></i>
                        </p>

                        <p class="control is-expanded">
                            <input type="text" class="is-underline-input input has-big-text" id="ticket-title" placeholder="New ticket title..." v-model="ticketTitle">
                        </p>

                        <selectbox type="priority" :choices="priorities"></selectbox>

                        <p class="control">
                            <transition name="fade">
                                <button class="ticket-submit add-button animate" @click="saveTicket"><i class="fa fa-plus"></i></button>
                            </transition>
                        </p>

                    </div>
                </div>
            </div>
            <div class="container">

                <!-- MODAL -->

                <assign-modal v-if="showModal" :ticket="selectedTicket"></assign-modal>

                <section class="section">

                    <h1 class="title">Open tickets</h1>

                    <h2 v-show="tickets.length == 0" class="has-text-centered">Good job! There are no open tickets</h2>

                    <div class="ticket-list">
                        <div class="ticket-list-item slideDown animated" v-for="ticket in tickets">
                            <div class="list-item-icon">
                                <i class="fa fa-ticket" :class="(ticket.completed) ? 'is-green' : ''"></i>
                            </div>
                            <div class="list-item-left">
                                <p v-diff-for-humans="ticket.created_at"></p>
                                <p>{{ ticket.category.name }}</p>
                            </div>
                            <div class="list-item-center">
                                <div class="list-item-center-top">
                                    {{ ticket.title }}
                                </div>
                                <div class="list-item-center-bottom">
                                    {{ ticket.status.name }}
                                </div>
                            </div>
                            <div class="list-item-right">
                                <p v-if="ticket.users.length > 0"><span v-for="user in ticket.users" class="user-avatar" @click="setSelectedTicket(ticket)"><div class="tooltip">{{ user.name }}</div><img :src="user.avatar" class="img-circle" alt=""></span></p>
                                <a v-if="!ticket.completed" class="button action-button animate" @click="setSelectedTicket(ticket)"><i class="fa fa-user-plus icon is-small"></i></a>
                                <!-- <a class="button assign-button animate" @click="completeTicket(ticket)"><i class="fa fa-check icon is-small"></i></a> -->
                                <a :href="'/ticket/'+ticket.id" class="button action-button animate"><i class="fa fa-arrow-right icon is-small"></i></a>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="section">

                    <h1 class="title">Completed tickets</h1>

                    <h2 v-show="closedTickets.length == 0" class="has-text-centered">There are no completed tickets, get to work!</h2>

                    <div class="ticket-list">
                        <div class="ticket-list-item slideDown animated" v-for="ticket in closedTickets">
                            <div class="list-item-icon">
                                <i class="fa fa-check is-green"></i>
                            </div>
                            <div class="list-item-left">
                                <p v-diff-for-humans="ticket.created_at"></p>
                                <p>{{ ticket.category.name }}</p>
                            </div>
                            <div class="list-item-center">
                                <div class="list-item-center-top">
                                    {{ ticket.title }}
                                </div>
                                <div class="list-item-center-bottom">
                                    {{ ticket.status.name }}
                                </div>
                            </div>
                            <div class="list-item-right">
                                <p v-if="ticket.users.length > 0"><img v-for="user in ticket.users" :src="user.avatar" class="img-circle" alt=""></p>
                                <a class="button action-button animate" @click="uncompleteTicket(ticket)"><i class="fa fa-arrow-up icon is-small"></i></a>
                                <a class="button action-button animate" @click="archiveTicket(ticket)"><i class="fa fa-archive icon is-small"></i></a>
                            </div>
                        </div>
                    </div>
                </section>

            </div>


        </div>
    </template>

    <script>
        export default{
            mounted(){
                this.fetchTickets();
                this.fetchCategories();
                this.fetchPriorities();

                Event.$on('selection-changed', (response) => {
                    if(response.type == 'category'){
                        this.category = response.choice;
                    }else if(response.type == 'priority'){
                        this.priority = response.choice;
                    }
                });

                Event.$on('users-assigned', () => {
                    this.showModal = false;
                    this.fetchTickets();
                });

                Event.$on('users-assign-cancel', () => {
                    this.showModal = false;
                });
            },
            props: ['type'],
            data(){
                return{
                    ticketTitle: '',
                    category: {},
                    priority: {},
                    isLoading: false,
                    tickets: [],
                    priorities: [],
                    categories: [],
                    closedTickets: [],
                    showModal: false,
                    showDetail: false,
                    selectedTicket: {}
                }
            },
            methods:{
                fetchTickets(){
                    axios.get('/tickets').then( (response) => {
                        this.tickets = response.data;
                        this.closedTickets = _.filter(this.tickets, function(value){
                            return value.completed;
                        });
                        this.tickets = _.filter(this.tickets, function(value){
                            return !value.completed;
                        });

                        $('.loading-overlay').css('z-index','999').addClass('fadeOut animated');

                        let t2 = setTimeout(function(){
                            $('.loading-overlay').css('z-index','-1');
                        },1000);

                    });
                },
                fetchCategories(){
                    axios.get('/categories').then((response) => {
                        this.categories = response.data;
                    });
                },
                fetchPriorities(){
                    axios.get('/priorities').then((response) => {
                        this.priorities = response.data;
                    });
                },
                setSelectedTicket(ticket){
                    this.selectedTicket = ticket;
                    this.showModal = true;
                },
                completeTicket(ticket){
                    axios.get('/ticket/complete/'+ticket.id).then((response) => {
                        this.fetchTickets();
                    });
                },
                showTicketDetail(ticket){
                    Event.$emit('show-detail', ticket);
                },
                saveTicket(){
                    let errors = [];

                    let data = [{
                        title: this.ticketTitle,
                        category_id: this.category.id,
                        priority_id: this.priority.id
                    }];

                    this.selectedTicket = data[0];

                    if(this.validate()){
                        axios.post('/ticket/save', data).then((response) => {
                            this.tickets.push(response.data);
                            this.selectedTicket = response.data;
                            this.ticketTitle = '';
                            Event.$emit('form-submitted');
                            this.showModal = true;
                        });
                    }
                },
                validate(){
                    if(this.category.name == "category" || this.priority.name == "priority" || this.ticketTitle == ""){
                        return false;
                    }
                    return true;
                },
                uncompleteTicket(ticket){
                    axios.get('/ticket/uncomplete/'+ticket.id).then( (response) => {
                        this.fetchTickets();
                    });
                },
                archiveTicket(ticket){
                    axios.get('/ticket/archive/'+ticket.id).then( (response) => {
                        this.fetchTickets();
                    });
                }
            }
        }
    </script>

    <style>
        .fade-enter-active, .fade-leave-active {
            transition: all .2s
        }
        .fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
            left: -50px;
            opacity:0;
        }

        .ticket-list{
            width:100%;
            display: flex;
            flex-direction: column-reverse;
            transition:all 2s;
            position: relative;
        }

        .ticket-list-item{
            width:100%;
            display: flex;
            flex:1;
            justify-content: space-between;
            align-items: center;
            border-bottom:1px dashed #eee;
            transition:all 2s;
        }

        .list-item-icon{
            display:flex;
            justify-content: center;
            align-items: center;
            color:#e6e6e6;
            padding:40px;
        }

        .list-item-left{
            display: flex;
            flex-direction: column;
            width:200px;
        }
        .list-item-right{
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: flex-start;
        }
        .list-item-center{
            display: flex;
            flex-direction: column;
            margin-right:auto;
        }
        .list-item-center-top{
            display: flex;
            flex-direction: row;
        }
        .list-item-center-bottom{
            display: flex;
            flex-direction: row;
        }

        .action-button{
            width: 30px;
            height: 30px;
            margin-right:5px;
            padding: 0!important;
            border-radius: 30px;
            border: none;
            border:1px dashed #b7b7b7;
            background: none;
            color:#d2d2d2;
            cursor:pointer;
            transform: rotate(0deg);
        }

        .action-button:hover{
            /*transform: rotate(360deg);*/
        }

        .action-button>.fa{
            font-size:16px!important;
        }

        .action-button:focus{
            outline:0;
        }

        .slideDown {
            -webkit-animation-duration: 1s;
            animation-duration: 1s;
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both;
            -webkit-animation-name: slideDown;
            animation-name: slideDown;
        }

        @-webkit-keyframes slideDown {
            from {
                -webkit-transform: translate3d(0, 0,-100%);
                transform: translate3d(0, 0,-100%);
                opacity: 0;
            }

            to {
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0);
                opacity: 1;
            }
        }

        .user-select-list{
            display:flex;
            flex-direction: column;
            width:80%;
            margin:0 auto;
        }

        .user-select-list-item{
            display:flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding:10px;
        }

        .user-select-list-item span{
            text-transform: uppercase;
            margin-right:auto;
            padding-left:20px;
        }

        .tooltip{
            background-color:#a7a7a7;
            color:white;
            padding:0px 10px;
            position:absolute;
            border-radius:5px;
            top:5px;
            opacity: 0;
            z-index:1;
            display:inline-table;
            transition: all .5s;
        }

        .user-avatar{
            cursor:pointer;
        }

        .user-avatar:hover
        > .tooltip{
            opacity: 1;
        }

        @keyframes pulse {
          0%   { opacity: 1; }
          100% { opacity: 0; }
      }

      body{
        margin:0;
    }

    .ctnr {
      display: flex;
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
  }

  .ldr {
      display: flex;
      flex-direction: row;
      flex-wrap: wrap;
      justify-content: space-around;
      align-items: center;
      margin: auto;
      width: 2.5em;
      height: 2.5em;
  }

  .ldr-blk {
      height: 35%;
      width: 35%;
      animation: pulse 0.75s ease-in infinite alternate;
      background-color: #E6E6E6;
  }

  .an_delay {
      animation-delay: 0.75s;
  }


</style>