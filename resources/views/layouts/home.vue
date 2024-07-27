<template>
    <body class="hold-transition sidebar-mini sidebar-collapse" :class="theme === 'dark' ? 'dark-mode': ''">
        <div class="wrapper" id="app" >

            <nav class="main-header navbar navbar-expand" :class="theme === 'dark' ? 'navbar-dark': 'navbar-light'">

                <ul class="navbar-nav">
                    <li class="nav-item ">
                        <a class="nav-link" data-widget="pushmenu" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">

                    <li v-if="userName.length>0" class="nav-item dropdown">
                        <a class="nav-link user-panel d-flex"  style="cursor:pointer;" data-toggle="dropdown" >
                            <span style="font-size: 27px;margin-right: 5px;" class="fas fa-user-shield"></span>
                            <p  style="margin:0px "  v-text="userName" ></p>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <a class="dropdown-item" style="cursor:pointer;" @click="logOut()">
                                <h3 class="dropdown-item-title" >
                                Logout
                                </h3>
                            </a>
                        </div>
                    </li>

                </ul>
            </nav>

            <aside class="main-sidebar sidebar-dark-primary elevation-4 ">

            <RouterLink to="/" class="brand-link myLogo "  data-widget="pushmenu">
                <img src="./shield6.png" alt="SafePass Logo" class="brand-image img-circle elevation-3" >
                <span class="brand-text font-weight-light">SafePass</span>
            </RouterLink>
            
            

            <div class="sidebar">


            <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">


            <li class="nav-item">
                <RouterLink to="/newvault" class="nav-link s "  data-widget="pushmenu">
                    <i class='bx bx-lock-alt'></i>
                    <p>
                    Create a password vault
                    </p>
                </RouterLink>
            </li>

            <li class="nav-item">
                <RouterLink to="/signin" class="nav-link s "  data-widget="pushmenu">
                    <i class='bx bxs-log-in' ></i>
                    <p>
                    Sign in to password vault
                    </p>
                </RouterLink>
            </li>

            <li class="nav-item">
                <RouterLink to="/newrecord" class="nav-link s"  data-widget="pushmenu">
                    <i class='bx bxs-save'></i>
                    <p>
                    Add password record to a vault
                    </p>
                </RouterLink>
            </li>

            <li class="nav-item">
                <RouterLink to="/records" class="nav-link s"  data-widget="pushmenu">
                    <i class='bx bx-lock-open-alt'></i>
                    <p>
                    Retrieve a password from a vault
                    </p>
                </RouterLink>
            </li>

            </ul>
            </nav>

            </div>

            <div class="nav-link sidebar">

            <nav class="lowNav" >
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item " @click="changeTheme()">
                    <a id="theme"   class="nav-link s">
                    <!-- <i class='bx bx-moon'></i> -->
                    <i class="fas" :class=" theme === 'dark' ? 'fa-sun' :'fa-moon'"></i>
                        <p id="theme">
                            {{theme === 'dark' ? 'Light Mode' : 'Dark Mode'}}
                        </p>
                    </a>
                </li>
            <li class="nav-item" data-widget="pushmenu" v-if="userName.length>0">
            <a @click="logOut()" class="nav-link s">
            <i class='bx bxs-log-out'></i>
            <p>
            Logout
            </p>
            </a>
            </li>
            </ul>
            </nav>
        </div>

            </aside>

            <div class="content-wrapper" style="overflow: auto;">
                <router-view @userLoggedIn="signIn" @userRegistered="register" @userLoggedOut="updateName"></router-view>
            </div>

            <footer class="main-footer ">
            <div id="socials" >

            <a href="https://github.com/olamilly" target="_blank" class="socialicon"><i class='bx bxl-github' ></i></a>
            <a href="https://www.linkedin.com/in/olamide-mustapha/" target="_blank" class="socialicon"><i class='bx bxl-linkedin-square' ></i></a>
            <a href="mailto:olamyy.m@gmail.com" target="_blank" class="socialicon"><i class='bx bxs-envelope' ></i></a>

            </div>
            <div> &copy; 2024<strong> Olamilly</strong>. All rights reserved.</div>
            </footer>

            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="./shield6.png" alt="AdminLTELogo" height="60" width="60">
            </div>
        </div>
    </body>
</template>
  
  <script>
  import axios from 'axios';
  import { useToastr } from '../../js/toastr';
  const toastr = useToastr();
  

  export default {
    name: 'App',
    data() {
        return {
        userName:'',
        theme:localStorage.getItem('theme')
        }
    },
    methods:{
        updateName(e){
            this.userName=e;
        },
        signIn(e){
            this.userName=e;
            //redirect to showall component show all vault records but hashed and pajinated and welcome username.
            this.$router.push('/records')
        },
        register(e){
            this.userName=e;
            this.$router.push('/')
        },
        logOut(){
            axios.post('/logout').then(()=>{
                toastr.success("Logout Successful")
                this.userName=''
                localStorage.setItem('userName','')
                this.$router.push('/');
            })
        },
        changeTheme(){
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            if(localStorage.getItem('theme')=='dark'){
                localStorage.setItem('theme','light')
            }
            else{
                localStorage.setItem('theme','dark')
            }
        }
    },
    mounted() {
        //console.log(localStorage.getItem('theme'))
        axios.get('/api/user').then((response) =>{  
            //console.log(response)
            if(response.data){
                localStorage.setItem('userName',response.data.name)
                this.userName=response.data.name
            }             
            else{
                localStorage.setItem('userName','')
                this.userName=''
            }
        })
    },
  };
  </script>
  
  <style scoped>
  footer{
        display: flex;
    justify-content: center;
    align-items:center;
    position: relative;
    }
    aside{
        position: relative; 
    }
    #socials{
        position: absolute;
        right: 0;
        display:flex;
        justify-content:space-evenly; 
        width:200px;
    }
    
    @media only screen and (max-width: 600px) {
        footer{
            flex-direction:column;
        }
        #socials{
            margin-bottom:.7rem;
            position:relative;
        }
    }
    .s>i{
        font-size:24px;
        margin-right: .3rem;
    }
    
    .socialicon>i{
    font-size:23px;
    color:gray;
    }
    .socialicon>i:hover{
    cursor:pointer;}
    .s{
        display:flex;
    }
    
    .myLogo{
        cursor:pointer;
    }
    .myLogo>img{
        opacity: .8;
        height:50px;
    }
    .lowNav{
        position:absolute;
        bottom:0;
    }
    .content-wrapper{
        display: flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        position: relative;
    } 
</style>