<template>
    <div class="container">

        <!-- CARD -->
        <div class="card border-primary">
            <div class="card-header border-primary">Task List</div>
            <div class="card-body pb-0">

                <!-- DATATABLE -->
                <data-table ref="taskTable" :url="url" :per-page="perPage" :columns="columns">
                    <div slot="filters" slot-scope="{ tableFilters, perPage }">
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input
                                    name="name"
                                    class="form-control"
                                    v-model="tableFilters.search"
                                    placeholder="Search Table">
                            </div>
                            <div class="offset-4 col-md-2">
                                <select class="form-control" v-model="tableFilters.length">
                                    <option :key="page" v-for="page in perPage">{{ page }}</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <a class="btn btn-primary btn-block" @click="addTask()">Add</a>
                            </div>
                        </div>
                    </div>
                    <thead slot="header" slot-scope="{ tableProps }">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </thead>
                    <tbody slot="body" slot-scope="{ data }">
                        <tr :key="item.id" v-for="item in data">
                            <td :key="column.name" v-for="column in columns">
                                <data-table-cell
                                    :value="item"
                                    :name="column.name"
                                    :meta="column.meta"
                                    :comp="column.component"
                                    :classes="column.classes">
                                </data-table-cell>
                            </td>
                            <td>
                                <a class="btn btn-warning" @click="editTask(item.id)"> Edit </a>
                                <a class="btn btn-danger" @click="deleteTask(item.id)"> Delete </a>
                            </td>
                        </tr>
                    </tbody>
                </data-table>
                <!-- DATATABLE -->

            </div>
        </div>
        <!-- CARD -->

        <!-- MODAL -->
        <div id="task_modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Task Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="task_form">
                            <input type="hidden" id="task_id" name="task_id">
                            <div class="form-group">
                                <label for="task_name">Name:</label>
                                <input type="text" class="form-control" id="task_name" name="task_name" aria-describedby="task_name" placeholder="Enter task name">
                            </div>
                            <div class="form-group">
                                <label for="task_description">Description:</label>
                                <textarea class="form-control" id="task_description" name="task_description" placeholder="Enter task desription"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="saveTask()">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL /-->

    </div>
</template>

<script>
    export default {
        data(){
            return {
                url: '/task/data',
                perPage: ['10', '25', '50'],
                columns: [
                    {
                        label: 'ID',
                        name: 'id',
                        orderable: true,
                    },
                    {
                        label: 'Name',
                        name: 'name',
                        orderable: true,
                    },
                    {
                        label: 'Description',
                        name: 'description',
                        orderable: true,
                    }
                ]
            }
        },
        mounted()
        {
            // event on modal close
            $('#task_modal').on('hidden.bs.modal', function (e) {
                $('#task_id').val('');
                $('#task_name').val('');
                $('#task_description').val('');
            });
        },
        methods: {
            // add task
            addTask() { $('#task_modal').modal({ keyboard: false }); },

            // edit task
            editTask(id) {
                axios.get('/task/'+id)
                .then(function(res){
                    var task = res.data.task;
                    $('#task_id').val(task.id);
                    $('#task_name').val(task.name);
                    $('#task_description').val(task.description);
                    $('#task_modal').modal({ keyboard: false });
                })
                .catch(function(err){ console.log(err); });
            },

            // save task
            saveTask(){
                var __this = this;
                var data = {
                    name: $('#task_name').val(),
                    description: $('#task_description').val()
                };
                var id = $('#task_id').val();
                if(id == '' || id == null || id == undefined){
                    axios.post('/task', data)
                    .then(function(res){
                        __this.$refs.taskTable.getData();
                        alert(res.data.message);
                        $('#task_modal').modal('hide');
                    })
                    .catch(function(err){ console.log(err); });
                }
                else{
                    axios.put('/task/'+id, data)
                    .then(function(res){
                        __this.$refs.taskTable.getData();
                        alert(res.data.message);
                        $('#task_modal').modal('hide');
                    })
                    .catch(function(err){ console.log(err); });
                }
            },

            // delete task
            deleteTask(id) {
                var answer = confirm('Do you want to remove this task?');
                if(answer)
                {
                    var __this = this;
                    axios.delete('/task/'+id)
                    .then(function(res){
                        __this.$refs.taskTable.getData();
                        alert(res.data.message);
                    })
                    .catch(function(err){ console.log(err); });
                }
            }
        }
    }
</script>