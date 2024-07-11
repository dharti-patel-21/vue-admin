<script setup>
import { reactive,ref } from 'vue';

const form = reactive({
    email:'',
    password:''
});

const errorMsg = ref('');
const loading = ref(false);

const handleSubmit = () => {
    loading.value = true;
    errorMsg.value = '';
    axios.post('/login', form)
    .then(() => {
        window.location.href = '/admin/dashboard';
    })
    .catch((error) => {
        errorMsg.value = error.response.data.message;
    })
    .finally(() => {
        loading.value = false;
    });
}
</script>

<template>
    <div class="login-box">

        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="#" class="h1"><b>Admin</b>Login</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <div v-if="errorMsg" class="alert alert-danger" role="alert">
                    {{ errorMsg }}
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="input-group mb-3">
                        <input v-model="form.email" type="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input v-model="form.password" type="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>

                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                <div v-if="loading" class="spinner-boarder spinner-boarder-sm" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <span v-else>Sign In</span>
                            </button>
                        </div>

                    </div>
                </form>

                <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p>
            </div>

        </div>
    </div>  
</template>