<script setup>
import axios from 'axios';
import { onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();
import { useToastr } from '../toastr';
import Swal from 'sweetalert2';
const toastr = useToastr();

const form = reactive({
    vaultname:'',
    recordname: '',
    recordusername: '',
    password: '',
    url:''
});

const errorMessage = ref('');
const bootState=ref(0)
const loading = ref(false);

const handleSubmit = () => {
    errorMessage.value = '';
    loading.value = true;
    axios.post('/addnewrecord',form)
        .then(() => {
            toastr.success("Record added Successfuly.")           
            router.push(`/records`);
        })
        .catch((error) => {
            errorMessage.value = 'Check your Internet connection and try again.';
            console.log(error.response.data.message);
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
      if(!response.data){
        Swal.fire({
        title: 'Not Logged in.',
        text: `You have to log into your vault to add password records.`,
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Sign-in',
        }).then((result) => {
            if (result.isConfirmed) {
                router.push('/signin') 
            }
            else{
                router.push('/');
            }
        })
      }
      else{
        form.vaultname=response.data.vaultname;
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
                    <a  class="h1">New Record</a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Add a new password record to your SafePass<small>&#174;</small> vault.</p>
                    <div v-if="errorMessage" class="alert alert-danger" role="alert">
                        {{ errorMessage }}
                    </div>
                    <form @submit.prevent="handleSubmit">
                        <div class="input-group mb-3">
                            <input v-model="form.vaultname" class="form-control" readonly >
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-save"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input v-model="form.recordname" required class="form-control" placeholder="Record Name or Identifier">
                            <div class="input-group-append" >
                                <div class="input-group-text">
                                    <span class="fas fa-id-card"></span>
                                </div>
                            </div>
                            <span id="exampleInputEmail1-error" class="error invalid-feedback">Please enter a valid email address</span>
                        </div>
                        <div class="input-group mb-3">
                            <input v-model="form.url"  class="form-control" placeholder="URL or link (optional)">
                            <div class="input-group-append" >
                                <div class="input-group-text">
                                    <span class="fas fa-globe"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input v-model="form.recordusername" class="form-control" placeholder="Record Username / e-mail">
                            <div class="input-group-append" >
                                <div class="input-group-text">
                                    <span class="fas fa-user-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input v-model="form.password" required type="password" class="form-control" placeholder="Record Password">
                            <i class="far fa-eye togglePassword" @click="toggle($event.target)" style=" padding-right: .3rem;"></i>
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
                                    
                                    <span v-else>Add Record</span>
                                </button>
                            </div>

                        </div>
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