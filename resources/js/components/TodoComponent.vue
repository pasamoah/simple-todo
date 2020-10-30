<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <form @submit.prevent="saveTodo">
                        <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="" v-model="form.title">
                        <div class="input-group-append" required>
                            <button class="btn btn-outline-primary" 
                            type="submit">
                            <i class="fa fa-plus" aria-hidden="true"></i> 
                                Add New
                            </button>
                        </div>
                        </div>
                        </form>
                        
                          <div class="text-danger">
                            <small>{{errorMessage[0]}}</small>
                        </div>
                        
                    </div>

<!-- Todo List -->
                    <div class="card-body">
                        <ul class="list-group">
                        <li class="list-group-item"
                            v-for="todo in todoResponse" :key="todo.id"
                        v-bind:class="{'is-complete':todo.completed}">
                            <!-- check Box -->
                            <label class="checkcontainer">
                                {{todo.title}}
                            <input type="checkbox" v-model="todo.completed" 
                            v-on:change="checkCompleted(todo.id)"
                            >
                            <span class="checkmark"></span>
                            </label>
                            
                            <span style="float:right">
                                <button v-on:click="deleteTodo(todo.id)" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>

                                <button class="btn btn-primary btn-sm" v-on:click="editTodo(todo.id)" 
                                title="Edit todo" data-toggle="modal" 
                                data-target="#staticBackdrop" >
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                            </span>
                        </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>


        <!-- Edit Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Edit Todo</h5>
            <button type="button" v-on:click="changeAlert" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <!-- Alert section -->

            <div class="alert alert-success" role="alert" v-if="showAlert === true">
                Changes Made Successfully
            </div>

            <!-- form -->
            <form @submit.prevent="TodoUpdate(editResponse.id)">
                        <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="" v-model="editResponse.title">
                        <div class="input-group-append" required>
                            <button class="btn btn-outline-primary" 
                            type="submit">
                            <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                Save changes
                            </button>
                        </div>
                        </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" v-on:click="changeAlert" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
    </div>
</template>

<script>
    export default {

        data(){
            return{
                form:{
                    title: ''
                },
                todoResponse: {
                    title: '',
                    completed: '' 
                },
                editResponse: {
                    title: ''
                },
                insertCompleted: '',
                showAlert: false,
                errorMessage: '',

            


            }
        },


        methods:{


            getTodo(){
                axios.get('/get-todo').then((res)=>{
                    this.todoResponse = res.data
                })
            },

            saveTodo(){
                axios.post('/add-todo', this.form).then((res)=>{
                    this.getTodo()
                    this.form.title = null
                }).catch(error => {
                   this.errorMessage = Object.values(error.response.data.errors)
                })
            },

            deleteTodo(id){
                axios.get('/delete-todo/' + id).then((res)=>{
                    this.todoResponse = this.todoResponse.filter(todo =>todo.id !== id)
                })
            },

            editTodo(id){
                axios.get('/edit-todo/' + id).then((res)=>{
                    this.editResponse = res.data
                })
                
            },

            TodoUpdate(id){
                axios.post('/update-todo/' + id, this.editResponse).then((res)=>{
                   this.getTodo()
                   this.showAlert = true
                })
            },


            changeAlert(){
                this.showAlert = false
            },
            

            checkCompleted(id){
                this.insertCompleted = !this.todoResponse.completed
                const formdata = new FormData();
                if (this.insertCompleted == true) {
                    this.insertCompleted = 1
                }else{
                    this.insertCompleted = 0
                }
                        formdata.append('completed', this.insertCompleted)
                        axios.post('/change-completed/' + id, formdata).then((res)=>{
                            
                })

            }


        },



        mounted() {
            console.log('Component mounted.')
            this.getTodo()
        }
    }
</script>


<style scoped>


/* The checkcontainer */
.checkcontainer {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;  
  font-family: Arial, Helvetica, sans-serif;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.checkcontainer input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
  position: absolute;
  top: 0;
  left: 0;
  height: 22px;
  width: 22px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.checkcontainer:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.checkcontainer input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.checkcontainer .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}

.is-complete{
    text-decoration: line-through;
}

</style>
