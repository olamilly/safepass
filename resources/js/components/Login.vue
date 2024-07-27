<script setup>
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();
import { useToastr } from '../toastr';
import Swal from 'sweetalert2';
const toastr = useToastr();

const form = reactive({
    vaultname: '',
    password: '',
});

const errorMessage = ref('');
const vaultName = ref('');
const loading = ref(false);
const emit = defineEmits(['userLoggedIn', 'userLoggedOut'])
const bootState=ref(0)
const handleSubmit = () => {
    errorMessage.value = '';
    loading.value = true;
    axios.post('/login', form)
        .then(() => {
            toastr.success("Login Successful")
            axios.get('/api/user').then((response) =>{               
                localStorage.setItem('userName', response.data.name);
                emit('userLoggedIn', response.data.name)
            })
            form.vaultname= ''
            form.password=''
        })
        .catch((error) => {
            errorMessage.value = error.response.data.message;
        })
        .finally(() => {
            loading.value = false;
        });
};

function toggle(e){
    const type = e.previousElementSibling
        .getAttribute('type') === 'password' ?
        'text' : 'password';
        
        e.previousElementSibling.setAttribute('type', type);
    
        e.classList.toggle('fa-eye-slash')
        e.classList.toggle('fa-eye')
}

onMounted(()=>{
    axios.get('/api/user').then((response) => {
      if(response.data){
        vaultName.value=response.data.vaultname
        Swal.fire({
        title: 'Already Logged in.',
        text: `You're already logged into "${vaultName.value}".\nDo you want to log out of the current vault?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes',
        cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('/logout')
                    .then((response) => {
                        localStorage.setItem('loggedInUser', '');
                        Swal.fire(
                            'Logged Out!',
                            `You have been logged out of "${vaultName.value}".`,
                            'success'
                        )
                        .then((response) =>{
                            localStorage.setItem('userName', '');
                            emit('userLoggedOut','')
                            bootState.value=1
                        })
                    });
            }
            else{
                router.push('/');
            }
        })
      }
      else{
        bootState.value=1;
      }
    })
})
</script>

<template>
        <div v-if="bootState==1" style="width:100%;display:flex;align-items: center;justify-content: center;">
        <div class="login-box" style="width:90%; max-width: 400px;">
            <div class="card card-outline card-primary" >
                <div class="card-header text-center">
                    <a  class="h1">Sign-in</a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Sign in to your SafePass<small>&#174;</small> Vault</p>
                    <div v-if="errorMessage" class="alert alert-danger" role="alert">
                        {{ errorMessage }}
                    </div>
                    <form @submit.prevent="handleSubmit" class="text-center">
                        <div class="input-group mb-3">
                            <input v-model="form.vaultname" required  class="form-control" placeholder="Vault Name">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-save"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input v-model="form.password" required type="password" class="form-control" placeholder="Password" style="width: 100;">
                            <i class="far fa-eye togglePassword" @click="toggle($event.target)" style=" padding-right: .1rem;"></i>
                            <div class="input-group-append" style="padding-left: -30px;">
                                <div class="input-group-text" style="padding-left: -30px;z-index: 260;">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <!-- <div class="icheck-primary">
                                    <input type="checkbox" id="remember">
                                    <label for="remember">
                                        Remember Me
                                    </label>
                                </div> -->
                            </div>

                            <div class="col-6">
                                <button type="submit" class="btn btn-primary btn-block" :disabled="loading">
                                    
                                    <div v-if="loading" class="spinner-border spinner-border-sm" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div> 
                                    
                                    <span v-else>Sign In</span>
                                </button>
                            </div>

                        </div>
                        <small class="text-center">Don't have a SafePass<small>&#174;</small> vault? <RouterLink to="/newvault">Create one.</RouterLink></small>    
                    </form>
                    
                    <!-- <p class="mb-1">
                        <a href="forgot-password.html">I forgot my password</a>
                    </p> -->
                </div>
                
            </div>
        </div>
        
    </div>
    <div class="overlay" v-if="bootState==0">
        <img class="animation__shake" src="../../views/layouts/shield6.png" alt="AdminLTELogo" height="60" width="60">
    </div>

    
</template>
<style scoped>
.togglePassword{
    margin-left: -30px;
    cursor: pointer;
    z-index: 200;
    margin-top: .7rem;
    padding-right: .5rem;
    font-size: 1rem;
}

</style>