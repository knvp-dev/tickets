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

                <assign-modal v-if="showAssignUsersModal" :ticket="selectedTicket"></assign-modal>

                <section class="section">

                    <div class="columns">
                        <div class="column">
                            <h1 class="title">Open tickets - {{ filter }}</h1>
                        </div>
                        <div class="column">
                            <div class="ticket-filters">
                                <ul>
                                    <li class="tag filter-tag" :class="(filter == 'All') ? 'filter-active' : ''" @click="filterTickets('All')"><a>All</a></li>
                                    <li class="tag filter-tag" :class="(filter == category.name) ? 'filter-active' : ''" v-for="category in categories" @click="filterTickets(category)"><a>{{ category.name }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <h2 v-show="filteredTickets.length == 0" class="has-text-centered">Good job! There are no open tickets</h2>

                    <div class="ticket-list">
                        <div class="ticket-list-item slideDown animated" v-for="ticket in filteredTickets">
                            <div class="list-item-icon">
                                <i class="fa fa-ticket" :class="ticket.priority.name.toLowerCase()"></i>
                            </div>
                            <div class="list-item-left">
                                <p v-diff-for-humans="ticket.created_at"></p>
                                <p>{{ ticket.category.name }}</p>
                            </div>
                            <div class="list-item-center">
                                <div class="list-item-center-top">
                                    <router-link :to="'/ticket/'+ticket.id">{{ ticket.title }}</router-link>
                                </div>
                                <div class="list-item-center-bottom">
                                    <i class="fa fa-thermometer-full pr-10 is-small-icon"></i> {{ ticket.priority.name }}
                                </div>
                            </div>
                            <div class="list-item-right">
                                <p v-if="ticket.users.length > 0"><span v-for="user in ticket.users" class="user-avatar" @click="assignUsers(ticket)"><div class="tooltip">{{ user.name }}</div><img :src="user.avatar" class="img-circle" alt=""></span></p>
                                <a v-if="ticket.owner_id == $root.AuthUser.id" class="button action-button animate" @click="assignUsers(ticket)"><i class="fa fa-user-plus icon is-small"></i></a>
                                <!-- <a class="button assign-button animate" @click="completeTicket(ticket)"><i class="fa fa-check icon is-small"></i></a> -->
                                <router-link :to="'/ticket/'+ticket.id" class="button action-button animate"><i class="fa fa-arrow-right icon is-small"></i></router-link>
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
                                    <router-link :to="'/ticket/'+ticket.id">{{ ticket.title }}</router-link>
                                </div>
                                <div class="list-item-center-bottom">
                                    Priority: {{ ticket.priority.name }}
                                </div>
                            </div>
                            <div class="list-item-right">
                                <!-- <p v-if="ticket.users.length > 0"><img v-for="user in ticket.users" :src="user.avatar" class="img-circle" alt=""></p> -->
                                <a class="button action-button animate" @click="uncompleteTicket(ticket)"><i class="fa fa-refresh icon is-small"></i></a>
                                <a class="button action-button animate" @click="archiveTicket(ticket)"><i class="fa fa-archive icon is-small"></i></a>
                                <router-link :to="'/ticket/'+ticket.id" class="button action-button animate"><i class="fa fa-arrow-right icon is-small"></i></router-link>
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
                    this.showAssignUsersModal = false;
                    this.fetchTickets();
                });

                Event.$on('users-assign-cancel', () => {
                    this.showAssignUsersModal = false;
                });
            },
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
                    filteredTickets: [],
                    filter: 'All',
                    showAssignUsersModal: false,
                    showDetail: false,
                    selectedTicket: {}
                }
            },
            methods:{
                fetchTickets(){
                    axios.get('/tickets').then( (response) => {
                        // populate tickets array
                        this.tickets = response.data;

                        // Filter tickets array and return the completed tickets
                        this.closedTickets = _.filter(this.tickets, function(value){
                            return value.completed;
                        });

                        // Filter tickets array and return the uncompleted tickets
                        this.tickets = _.filter(this.tickets, function(value){
                            return !value.completed;
                        });

                        this.filterTickets(this.filter);

                        // animate loading screen
                        $('.loading-overlay').css('z-index','999').addClass('fadeOut animated');
                        let t2 = setTimeout(function(){
                            $('.loading-overlay').css('z-index','-1');
                        },1000);

                    });
                },
                fetchCategories(){
                    // Fetch all categories
                    axios.get('/categories').then((response) => {
                        this.categories = response.data;
                    });
                },
                fetchPriorities(){
                    // Fetch all priorities
                    axios.get('/priorities').then((response) => {
                        this.priorities = response.data;
                    });
                },
                assignUsers(ticket){
                    // open the modal for assigning users to a ticket
                    this.selectedTicket = ticket;
                    this.showAssignUsersModal = true;
                },
                showTicketDetail(ticket){
                    Event.$emit('show-detail', ticket);
                },
                filterTickets(category){
                    // Filter open tickets by category
                    this.filteredTickets = this.tickets;
                    if(category != "All"){
                        this.filter = category.name;
                        this.filteredTickets = _.filter(this.tickets, (ticket) => {
                            return ticket.category.id === category.id;
                        });
                    }else{
                        this.filter = "All";
                    }
                },
                saveTicket(){
                    // Build up ticket data object
                    let newTicket = {
                        title: this.ticketTitle,
                        category_id: this.category.id,
                        priority_id: this.priority.id,
                        owner_id: this.$root.AuthUser.id
                    };

                    // Validate the new ticket data, create the ticket an add the new ticket to tickets array
                    if(this.validate()){
                        axios.post('/ticket/save', {'ticket': newTicket}).then((response) => {
                            this.tickets.push(response.data);
                            this.selectedTicket = response.data;
                            this.ticketTitle = '';
                            Event.$emit('form-submitted');
                            this.showAssignUsersModal = true;
                        });
                    }
                },
                validate(){
                    if(this.category.name == "category" || this.priority.name == "priority" || this.ticketTitle == ""){
                        return false;
                    }
                    return true;
                },
                completeTicket(ticket){
                    ticket.completed = 1;
                    axios.get('/ticket/'+ticket.id+'/complete');
                },
                uncompleteTicket(ticket){
                    ticket.completed = 0;
                    axios.get('/ticket/'+ticket.id+'/uncomplete');
                },
                archiveTicket(ticket){
                    ticket.archived = 1;
                    axios.get('/ticket/'+ticket.id+'/archive');
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
            border-bottom:1px dashed #d4d4d4;
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
            text-transform: uppercase;
            font-weight: bold;
            color: #ae6379;
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

  .low{

  }

  .normal{

  }

  .high{
    color:#ec7474;
}


</style>