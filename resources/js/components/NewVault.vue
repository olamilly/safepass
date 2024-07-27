<script setup>
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';
import { useToastr } from '../toastr';
import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';
const router = useRouter();

const toastr = useToastr();
const form = reactive({
    vaultname:'',
    email: '',
    password: '',
    password_confirmation: '',
    firstname:'',
    lastname:'',
    name: '',
    filename:''
    
});
const loading = ref(false);
const emit = defineEmits(['userRegistered', 'userLoggedOut'])
const bootState=ref(0)

const errorMessage = ref('');
const handleSubmit = () => {
    loading.value = true;
    form.name=form.firstname+" "+form.lastname; 
    errorMessage.value = '';
    const tempfilename = form.vaultname.replace(/\s+/g, '-'); 
    form.filename = tempfilename.replace(/[^a-zA-Z0-9._-]/g, '').toLowerCase();
    axios.post('/register', form)
        .then((response) => {
            toastr.success("Vault Created Successfully")
            axios.get('/api/user').then((response) =>{               
                localStorage.setItem('userName', response.data.name);
                emit('userRegistered', response.data.name)
            })
        })
        .catch((error) => {
            form.password=''
            form.password_confirmation=''
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
    // // Toggle the eye and bi-eye icon
    e.classList.toggle('fa-eye-slash')
    e.classList.toggle('fa-eye');
}
onMounted(()=>{
    axios.get('/api/user').then((response) => {
      if(response.data){
        const vaultName=response.data.vaultname
        Swal.fire({
        title: 'Already Logged in.',
        text: `You're already logged into "${vaultName}".\nDo you want to log out of the current vault?`,
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
                            `You have been logged out of "${vaultName}."`,
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
        <a  class="h1">New Vault</a>
    </div>
    <div class="card-body">
        <p class="login-box-msg">Create a new SafePass<small>&#174;</small> Vault</p>
        <div v-if="errorMessage" class="alert alert-danger" role="alert">
            {{ errorMessage }}
        </div>
        <form @submit.prevent="handleSubmit" class="text-center">
            <div class="input-group mb-3">
                <input v-model="form.firstname" required  class="form-control mr-1" placeholder="First Name">
                <input v-model="form.lastname" required  class="form-control" placeholder="Last Name">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input v-model="form.email" type="email" required class="form-control" placeholder="Email">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input v-model="form.vaultname"  class="form-control" placeholder="Vault Name">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-save"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input v-model="form.password" required type="password" class="form-control" placeholder="Vault Password">
                <i class="far fa-eye togglePassword" @click="toggle($event.target)" style=" padding-right: .1rem;"></i>
                <div class="input-group-append" style="padding-left: -30px;">
                    <div class="input-group-text" style="padding-left: -30px;z-index: 260;">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input v-model="form.password_confirmation" required type="password" class="form-control" placeholder="Confirm Vault Password">
               
                <i class="far fa-eye togglePassword" @click="toggle($event.target)" style=" padding-right: .1rem;"></i>
                
                <div class="input-group-append">
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
                      
                        <span v-else>Register</span>
                    </button>
                </div>

            </div>
            
            <small class="text-center">Already have a SafePass<small>&#174;</small> vault? <RouterLink to="/signin">Sign-in.</RouterLink></small>    
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
.input-group-text>span{
    
    font-size: 20px;
}
.togglePassword{
    margin-left: -30px;
    cursor: pointer;
    z-index: 200;

    margin-top: .7rem;
    padding-right: .5rem;
    font-size: 1rem;
}

</style>