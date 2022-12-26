<div class="container register-container">
    <div class="col-xl-7 mx-auto register-custom d-flex flex-row">
        <div>
            <img class="img-fluid custom-register-img" src="<?php echo ADDRESS ?>/public/img/Register.jpg" alt="">
        </div>
        
        <div>
            <h5 class="text-center my-4 fw-bold">Register to maktab drive</h5>
            <form method="POST" action="<?php echo ADDRESS ?>app/database.php">
                <div class="form-group mx-auto form-register-custom">
                    <label class="custom-label" for="formGroupExampleInput">Email</label>
                    <input name="Email" type="Email" class="form-control input-register-custom" id="formGroupExampleInput" placeholder="enter your email">
                </div>
                <div class="form-group mx-auto form-register-custom">
                    <label class="custom-label" for="formGroupExampleInput">Username</label>
                    <input name="Username" type="text" class="form-control input-register-custom" id="formGroupExampleInput" placeholder="choose username">
                </div>
                <div  class="form-group mx-auto form-register-custom">
                    <label class="custom-label" for="formGroupExampleInput2">password</label>
                    <input name="Password" type="password" class="form-control input-register-custom" id="formGroupExampleInput2" placeholder="+6 chacter">
                </div>
                <div  class="form-group mx-auto form-register-custom">
                    <label class="custom-label" for="formGroupExampleInput2">Reapat Password</label>
                    <input name="Password-Reapeat" type="password" class="form-control input-register-custom" id="formGroupExampleInput2" placeholder="reapeat your password">
                </div>
                <button name="Register" class="btn btn-primary d-block mx-auto btn-register-custom" data-toggle="modal" data-target="#exampleModal">Register</button>

            </form>
        </div>
    </div>
    <small class="regsiter-guide-login d-block text-center my-4">already have account? for login click <a href="<?php echo ADDRESS ?>?login=true">here</a></small>
</div>

<?php require_once __DIR__.DIRECTORY_SEPARATOR."partials".DIRECTORY_SEPARATOR."modal.php"  ?>

<script src="<?php echo ADDRESS ?>/public/js/Register.js"></script>

