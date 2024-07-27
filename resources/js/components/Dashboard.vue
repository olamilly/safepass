<template>

    <div class="content" style="display:block; text-align:center">
    <h1 style="font-size: 3rem; font-weight: 700;">Take control of your digital security.</h1>
    <img src="../../views/layouts/password-manager.png" alt="SafePass Logo" height="200rem" width="200rem" >
    <h5>Welcome to SafePass<small>&#174;</small>, your trusted and secure password manager.</h5>
    <button type="button" data-widget="pushmenu" class="btn btn-primary btn-lg">Get Started</button>
    <!-- <div style="display:flex;width:100%;justify-content: space-evenly;">
        
        <RouterLink to="/newvault" >
            Create a password vault
        </RouterLink>

        <RouterLink to="/signin" >
            Sign in to an existing password vault
        </RouterLink>

        <RouterLink to="/newrecord" >
            Add password record to a vault
        </RouterLink>

        <RouterLink to="/records" >
            Retrieve a password from a vault
        </RouterLink>
    </div> -->
</div>


</template>
<style scoped>
@media only screen and (max-width: 600px) {
    h1{
        font-size: 3rem; 
    }
}

    a{
        padding: 1rem;
        text-decoration: none;
    }
</style>