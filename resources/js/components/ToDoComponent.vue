<template>
    <div>
        
        <button type="button" class="btn btn-primary" v-on:click="all()">All</button>
        <button type="button" class="btn btn-success" v-on:click="done()">All done</button>
        <button type="button" class="btn btn-secondary" v-on:click="undone()">All undone</button>

        <h2>ToDos</h2>

        <form @submit.prevent="addTask" class="mb-3">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Title of Task" v-model="task.title">
            </div>
            <button type="submit" class="btn btn-light btn-block">Save Task</button>
        </form>

        <div class="card card-body" v-for="task in tasks" v-bind:key="task.id">
            <small>Created at: {{ format_datetime(task.created_at) }}<span v-if="task.created_at != task.updated_at">, updated at: {{ format_datetime(task.updated_at) }}</span></small>
            <h3 :class="{ 'done' : task.is_done == true}">{{ task.title }}</h3>
            <hr>
            <button @click="editTask(task)" class="btn btn-warning mb-2">Edit</button>
            <button v-if="task.is_done" @click="deleteTask(task.id)" class="btn btn-danger">Delete</button>
            <button v-else @click="doneTask(task.id)" class="btn btn-primary">Done</button>
            
        </div>
    </div>
    
</template>

<script>
    export default{
        data(){
            return {
                tasks: [],
                task:{
                    id: '',
                    title: '',
                    is_done: ''
                },
                task_id: '',
                edit: false,
                url: '/api/tasks'
            }
        },
        created(){
            this.fetchTasks();
        },
        methods:{
            fetchTasks(){
                let vm = this;
                fetch(this.url)
                    .then(response => response.json())
                    .then(response => {
                        console.log(response);
                        this.tasks = response;
                    })
                    .catch(error => console.log(error))
            },
            deleteTask(task_id){
                if(confirm("Are You Sure?")){
                    fetch('/api/tasks/' + task_id, {
                        method: 'delete'
                    })
                    .then(response => response.json())
                    .then(response => {
                        this.fetchTasks();
                        if(response.deleted) alert('Task removed!')
                        else alert("Can't remove!");
                    })
                    .catch(error => console.log(error));
                }
            },
            doneTask(task_id){
                if(confirm("Are You Sure?")){
                    fetch('/api/done_task/' + task_id, {
                        method: 'post'
                    })
                    .then(response => response.json())
                    .then(response => {
                        this.fetchTasks();
                        alert('Task was done!');
                    })
                    .catch(error => console.log(error));
                }
            },
            addTask(){
                if(this.edit === false){
                    // Add
                    fetch('/api/tasks', {
                        method: 'post',
                        body: JSON.stringify(this.task),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.task.title = '';
                        this.task.is_done = '';
                        alert("Task Added");
                        this.fetchTasks();
                    })
                    .catch(error => console.log(error));
                }else{
                    // Update
                    fetch('/api/tasks/' + this.task_id, {
                        method: 'put',
                        body: JSON.stringify(this.task),
                        headers: {
                            'content-type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.edit = false;
                        this.task.title = '';
                        this.task.description = '';
                        alert("Task Updated");
                        this.fetchTasks();
                    })
                    .catch(error => console.log(error));
                }
            },
            editTask(task){
                this.edit = true;
                this.task.id = task.id;
                this.task_id = task.id;
                this.task.title = task.title;
                this.task.description = task.description;
            },
            format_datetime(datetime){
                const dtformat = new Intl.DateTimeFormat('UK', { year: 'numeric',
                    month: '2-digit', day: '2-digit', hour: '2-digit',
                    minute: '2-digit', second: '2-digit' });
                return dtformat.format(new Date(datetime));
            },
            all(){
                this.url = '/api/tasks';
                this.fetchTasks();
            },
            done(){
                this.url = '/api/index_done';
                this.fetchTasks();
            },
            undone(){
                this.url = '/api/index_undone';
                this.fetchTasks();
            }
        }
    }
</script>