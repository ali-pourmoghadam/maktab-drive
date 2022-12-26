<?php

function checkPostReq($exception){

    $err =[];

    foreach($_POST as $key=>$item){

        if(empty($item) && $key != $exception){

            $err[$key] = $key." Can't be empty!";

        }
    }
    return $err;
}


function checkEmail($email){

            if(!filter_var($email ,  FILTER_VALIDATE_EMAIL)){

                return "Email format its not correct";

            }else{

                return true;

            }
}



function checkPasswordRegisterLenght($password){

            if(strlen($password) < 6){

                return "Password Atleast should be 6 chars";

            }else{

                return true;

            }
}



function checkPasswordRepeatRegister($password , $passwordRepeat){
    
      if($password != $passwordRepeat){

        return "Passwrods Not Match!";

      }else{

        return true;

      }
}

function checkEmailExists($Email){
    
    $data = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."database".DIRECTORY_SEPARATOR."database.json" );
    $data = json_decode($data,true);

    foreach($data['usersAccounts'] as $value){

       if(key($value) == $Email){

        return "Email alreadyExists , try another Email!";

        break;
       }
    }
    return true;
}

function authentication($Email , $userPassword){

    $data = ReadAll();

    foreach($data['usersAccounts'] as $value){

        if( in_array($Email , array_keys($value))){
            
            $password = $value[$Email]["password"];

            if($password == $userPassword){
                return true;
            }else{
                return false;
            }
        }
     }
     return false;

}

function validationToken($token){

    $data = ReadAll();

    foreach($data['usersAccounts'] as $value){

        if(sha1(md5(key($value))) == $token){

            return key($value);
        }
    }

    return false;
}


function ErrAdder($key , $errorText){

    global $err;

    if(isset($err[$key])){

        $prevErr = $err[$key];

        $err[$key] = [];

        $err[$key][] = $prevErr;

        $err[$key][] = $errorText;

    }else{
        
        $err[$key] = $errorText;
        
    }
}