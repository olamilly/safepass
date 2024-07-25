<template>

        <div  style="text-align:center; width:100%;position:absolute;top:0;">
           
        <div  >
            <div class="d-flex justify-content-center flex-column align-items-center "  v-if="loggedInState==1">
                <div class="text-center card-header w-100"><h1>{{ vaultName }}</h1></div>
                <div class="card my-2" style="text-align:center; width:80%;" v-for="(record, index) in allRecords" :key="index">
                    <div class="card-body p-2">
                        <p class="mb-0 d-flex justify-content-around align-items-center" style="position:relative; text-align: left;">
                            <div style="position: absolute;left: 0;">
                                <img v-if="record.imgurl" :src="record.imgurl" style="width:40px;height:40px">
                                <img v-else src="../../views/layouts/shield6.png" style="width:40px;height:40px">
                            </div>
                            <div>
                                <!-- <i class="fab fa-youtube"></i> -->
                                <h3>{{record.identifier}}</h3>
                            </div>
                            
                            <div style="position: absolute;right: 0;">
                                <i class="fas fa-ellipsis-h" data-toggle="dropdown" role="button" style="margin-right: 2rem;"></i>
                                <div class="dropdown-menu">

                                    <a role="button" class="dropdown-item"  @click="editMyRecord(index+1, record)">
                                        Edit 
                                    </a>

                                    <a role="button" class="dropdown-item" @click="deleteRecord(index+1)">
                                        Delete 
                                    </a>
                                </div>
                                <i class="fas fa-angle-left " role="button" @click="dropIcon($event.target)"  ></i>
                            </div>
                            
                        </p>

                        <div class="d-none">
                            <a :href="record.url" role="button" target="_blank" v-if="record.url && record.url.substring(0, 3)=='htt'" style="text-decoration: none !important;"> 
                                <div class="d-flex justify-content-center align-items-center">
                                    <i class='bx bx-globe'></i>
                                    <p class="m-0">{{ record.url }}</p>
                                </div>
                            </a>
                            <a v-if="record.url && record.url.substring(0, 3)!='htt'" style="text-decoration: none !important;"> 
                                <div class="d-flex justify-content-center align-items-center">
                                    <i class='bx bx-globe'></i>
                                    <p class="m-0">{{ record.url }}</p>
                                </div>
                            </a>
                            <div class="form-group row">
                                <label  class="col-form-label col-sm-2 " style="text-align: left;">Username</label>
                                <div class="col-sm-8">
                                    <input  class="form-control "readonly v-model="record.username">
                                </div>
                                <div class="input-group-append col-sm-2 d-flex justify-content-end">
                                    <span class="input-group-text" role="button" @click="copy($event.target)"><i class="fas fa-copy"></i></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" style="text-align: left;">Password</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type=password readonly v-model="record.password">
                                </div>
                                <div class="input-group-append col-sm-2 d-flex justify-content-end">
                                    <span class="input-group-text" @click="copy($event.target)" role="button"><i class="fas fa-copy" ></i></span>
                                    <span class="input-group-text" @click="toggle($event.target)" role="button" >
                                        <i class="far fa-eye " :id="index"  ></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>        
                </div>
               
            </div>
            
        </div>
        
    </div>
    <div v-if="loggedInState==2 " style="display: flex;flex-direction: column; align-items: center;justify-content: center;">
        <h1 >No records to show😔...</h1>
        <RouterLink to="/newrecord"><button type="button" class="btn btn-primary btn-block">add new record</button></RouterLink>
    </div>
    <div class="overlay" v-if="loggedInState==0" style="align-self: center;">
                <img class="animation__shake" src="../../views/layouts/shield6.png" alt="AdminLTELogo" height="60" width="60">
            </div>
    
    <button type="button" id="mBn" class="btn btn-default" style="display: none;" data-toggle="modal" data-target="#modal-default">
    Launch Default Modal
    </button>
    <div class="modal fade" id="modal-default" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Password Record</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form >
            <div class="input-group mb-3">
                <input v-model="vaultName" class="form-control" readonly >
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-save"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input v-model="editForm.identifier" required class="form-control" placeholder="Record Name or Identifier">
                <div class="input-group-append" >
                    <div class="input-group-text">
                        <span class="fas fa-id-card"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input v-model="editForm.username" class="form-control" placeholder="Record User Name / e-mail">
                <div class="input-group-append" >
                    <div class="input-group-text">
                        <span class="fas fa-user-lock"></span>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <input v-model="editForm.password" required type="password" class="form-control" placeholder="Record Password">
                <i class="far fa-eye togglePassword" @click="toggle7($event.target)" style=" padding-right: .3rem;"></i>
                <div class="input-group-append" style="padding-left: -30px;">
                    <div class="input-group-text" style="padding-left: -30px;z-index: 260;">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="clBn" class="d-none" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-block" :disabled="loading7||editForm.identifier.length<1||editForm.username.length<1|| editForm.password.length<1" @click="editRecord()">
            <div v-if="loading7" class="spinner-border spinner-border-sm" role="status">
                <span class="sr-only">Loading...</span>
            </div> 
            <span v-else>Save changes</span>
        </button>
      </div>
    </div>
  </div>
</div>
</template>
<script setup>
import axios from 'axios';
import { onMounted, ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();
import Swal from 'sweetalert2';
import { useToastr } from '../toastr';
const toastr = useToastr();

const vaultName=ref('')
const loggedInState=ref(0)
const allRecords = reactive([])
const loading7 = ref(false);
const editForm = reactive({
    identifier: '',
    username: '',
    password: '',
    index:''
});

function dropIcon(e){
    e.classList.toggle('xxx');
    e.parentElement.parentElement.parentElement.childNodes[1].classList.toggle('d-none');
}   
function copy(e) {
    var dummy = document.createElement("textarea");
    document.body.appendChild(dummy);
    if(e.classList.contains('fas')){
        const target=e.parentElement.parentElement.previousElementSibling.firstChild.value;
        dummy.value = target;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);
        e.classList.toggle('fa-copy')
        e.classList.toggle('fa-check')
        e.classList.toggle('mytext-success')
        setTimeout(()=>{
            e.classList.toggle('fa-copy')
            e.classList.toggle('fa-check')
            e.classList.toggle('mytext-success')
        },2000)
    }
    else{
        const target=e.parentElement.previousElementSibling.firstChild.value;
        dummy.value = target;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);
        e.firstChild.classList.toggle('fa-copy')
        e.firstChild.classList.toggle('fa-check')
        e.firstChild.classList.toggle('mytext-success')
        setTimeout(()=>{
            e.firstChild.classList.toggle('fa-copy')
            e.firstChild.classList.toggle('fa-check')
            e.firstChild.classList.toggle('mytext-success')
        },2000)
    }
}

function editMyRecord(i, r){
    editForm.identifier=r.identifier;
    editForm.username=r.username;
    editForm.password=r.password
    editForm.index=i;
    document.getElementById("mBn").click();
}
function editRecord(){
    loading7.value= true;
    axios.post('/editrecord', editForm)
    .then(()=>{
        loading7.value= false;
        document.getElementById("clBn").click();
        toastr.success('Record Edited Successfully');
        loggedInState.value=0;
        allRecords.length=0;
        axios.get('/allrecords').then((response)=>{
            //console.log(response.data);
            if(response.data.length>0){
                loggedInState.value=1;
                allRecords.push(...response.data);
            }
            else{
                loggedInState.value=2;
            }
        })
    }).catch((error)=>{
        alert(error)
    })
    
    
}
function deleteRecord(i){
    var url = '/deleterecord'+'/'+String(i);
    axios.post(url).then(()=>{
        toastr.success('Record Deleted Successfully');
        allRecords.splice(i-1, 1);
        if(allRecords.length==0){
            loggedInState.value=2 ;
        }
    }).catch((error)=>{
        alert(error)
    })
}

function toggle(e){
    if(e.classList.contains('far')){
        const target=e.parentElement.parentElement.previousElementSibling.firstChild;
        const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
        target.setAttribute('type', type);
        e.classList.toggle('fa-eye-slash')
        e.classList.toggle('fa-eye')
    }
    else{
        const target=e.parentElement.previousElementSibling.firstChild;
        const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
        target.setAttribute('type', type);
        e.childNodes[1].classList.toggle('fa-eye-slash')
        e.childNodes[1].classList.toggle('fa-eye');
    }
}

function toggle7(e){
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
        vaultName.value=response.data.vaultname;
        axios.get('/allrecords').then((response)=>{
            if(response.data.length>0){
                //console.log(response.data);
                loggedInState.value=1;
                allRecords.push(...response.data);
            }
            else{
                loggedInState.value=2;
            }
        })
      }
      else{
        Swal.fire({
        title: 'Not Logged in.',
        text: `You have to log into your vault to view your password records.`,
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
    })
})
</script>
<style scoped>
.togglePassword{
    margin-left: -30px;
    cursor: pointer;
    z-index: 200;
    margin-top: .7rem;
    padding-right: .5rem;
    font-size: 1rem;
}
.content{
    padding-top: 2rem;
    height: 100%;
    padding-left:1rem;
    padding-right: 1rem;
}
.xxx{
    transform: rotate(-90deg)
}
.mytext-success{
    color:green;
}
a{
    text-decoration: none !important;
    color:inherit;
}
</style>