<script setup>
import { ref,onMounted, reactive } from 'vue';
import axios from 'axios';
import { Form, Field} from 'vee-validate';
import * as yup from 'yup';
import Preloader from '../../components/Preloader.vue';
import { error } from 'toastr';
import {useToastr} from '../../toastr.js';
import UserListItem from './UserListItem.vue';

const toastr = useToastr();
const loading = ref(false);
const users = ref([]);
const editing = ref(false);
const formValues = ref(); 
const form = ref(null);

const getUsers = () => { 
    loading.value = true
    axios.get('/api/users')
    .then((response) => {
        users.value = response.data;
        loading.value = false;
    })
}


const createUserschema = yup.object().shape({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().min(8).required()
});

const editUserschema = yup.object().shape({
    name: yup.string().required(),
    email: yup.string().email().required(),
    password: yup.string().when((password, schema) => {
        return password ? schema.required().min(8) : schema;
    })
});


const createUser = (values, { resetForm, setErrors }) => {
    axios.post('/api/createUser',values)
    .then((response) => {
        users.value.unshift(response.data);
        $("#createUserModal").modal('hide');
        resetForm();
        toastr.success('User Created Succesfully');
    })
    .catch((error) => {
        if(error.response.data.errors){
            setErrors(error.response.data.errors);
        }
        
    })
}

const updateUSer = (values, {setErrors}) => {
    axios.put('/api/user/update', +formValues.value.id,values)
    .then((response) => {
        const index = users.values.findIndex(user=> user.id === response.data.id);
        users[index] = response.data;
        $('#createUserModal').modal('hide');
        toastr.success('User Updated Succesfully');
    }).catch((error) => {
        setErrors(error.response.data.errors);
        console.log(error);
    })
}

const handleSubmit = (values, actions) => {
    if(editing.value){
        editUser(values, actions);
    }else{
        createUser(values, actions);
    }
}

const editUser = (user) => {
    console.log(user);
    editing.value = true;
    form.value.resetForm();
    $('#createUserModal').modal('show');
    formValues.value = {
        id: user.id,
        name: user.name,
        email: user.email
    }; // Clone user object to avoid direct reactivity issues
    
};

const addUser = () => {
    editing.value = false;
    $("#createUserModal").modal('show');
}

const userDeleted = (userId) => {
    users.value = users.value.filter(user => user.id !== userId)
}

onMounted(() => {
    getUsers();
})
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <button type="button" @click="addUser" class="mb-2 btn btn-primary">
                Add New User
            </button>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered Date</th>
                                <th>Role</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <UserListItem v-for="(user,index) in users" 
                            :key="user.id"
                            :user=user 
                            :index=index
                            @edit-user="editUser"
                            @user-deleted="userDeleted" />
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!--modal-->
    <div class="modal fade" id="createUserModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing"> Edit User</span>
                        <span v-else> Add New User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="editing? editUserSchema : createUserschema" v-slot="{ errors }" :initial-values="formValues">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" type="text" class="form-control" :class="{ 'is-invalid': errors.name }" id="name"
                                   aria-describedby="nameHelp" placeholder="Enter full name" />
                            <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field name="email" type="email" :class="{ 'is-invalid': errors.email }"  class="form-control " id="email"
                                aria-describedby="nameHelp" placeholder="Enter full name" />
                            <span class="invalid-feedback">{{ errors.email }}</span>
                        </div>
                        

                        <div class="form-group">
                            <label for="email">Password</label>
                            <Field name="password" type="password" class="form-control " :class="{ 'is-invalid': errors.password }" id="password"
                                aria-describedby="nameHelp" placeholder="Enter password" />
                            <span class="invalid-feedback">{{ errors.password }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </Form>
            </div>
        </div>
    </div>

    

    <Preloader :loading="loading" />
</template>