<div class="container">
    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8 col-10 mx-auto login-container">
            <img class="d-block img-fluid w-25 mx-auto my-4 " src="<?php echo ADDRESS ?>/public/img/Drive-logo.png" alt="#">
            <h5 class="text-center">Sign in</h5>
            <p class="text-center">to continue maktab drive</p>
            <form method="POST" action="<?php echo ADDRESS ?>app/database.php">
                <div class="form-group mx-auto form-custom">
                    <label class="custom-label" for="exampleInputEmail1">Email address</label>
                    <input name="LoginEmail" type="Email" class="form-control my-2" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                
                </div>
                <div class="form-group mx-auto form-custom">
                    <label class="custom-label" for="exampleInputPassword1">Password</label>
                    <input name="LoginPassword" type="password" class="form-control my-2" id="exampleInputPassword1" placeholder="Password">
                    <small id="emailHelp" class="form-text text-muted">if you haven't account click <a href="<?php echo ADDRESS ?>?register=true">here</a></small>
                </div>
           
                <button name="Login" type="submit" class="btn btn-primary d-block mx-auto my-3 custom-btn px-4 py-1">Next</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__.DIRECTORY_SEPARATOR."partials".DIRECTORY_SEPARATOR."modal.php"  ?>

<script src="<?php echo ADDRESS ?>/public/js/login.js"></script>