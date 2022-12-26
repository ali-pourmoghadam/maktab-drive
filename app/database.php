<?php
require_once(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."pages".DIRECTORY_SEPARATOR."config.php");
require_once(__DIR__.DIRECTORY_SEPARATOR."validation.php");


$treeResalt= [];

$treeUploadResalt= [];

$newFileAdd = null;

$upload = null;

$uploadType = "";


if(isset($_POST['Register'])){
    $err=[];
   

    if(!empty(checkPostReq('Register'))){
        $err = checkPostReq('Register');
    }


    if(!checkEmail($_POST['Email'])){
        ErrAdder("Email" ,checkEmail($_POST['Email']));
    }

    
    if(checkEmailExists($_POST['Email']) !== true){
        ErrAdder("Email" , checkEmailExists($_POST['Email']) );
    }
 
    if(checkPasswordRegisterLenght($_POST['Password']) !== true){
        ErrAdder("Password" ,checkPasswordRegisterLenght($_POST['Password']));

    }

    if(checkPasswordRepeatRegister($_POST['Password'] , $_POST['Password-Reapeat']) !== true){

        ErrAdder("Password-Reapeat" ,checkPasswordRepeatRegister($_POST['Password'] , $_POST['Password-Reapeat']));
    }

    if(empty($err)){

        $token = sha1(md5($_POST['Email']));

        InsertUser($_POST['Email'] , $_POST['Username'] , $_POST['Password']);

        header('Location:'.ADDRESS."?main=true&token=$token");
 
    }else{
        pageReqHandler("Register" , ["error" => $err]);

        set_url(ADDRESS."?Register=true");
    }
  
}






if(isset($_POST['Login'])){
    $err=[];
   

    if(!empty(checkPostReq('Login'))){

        $err = checkPostReq('Login');

    }


    if(!checkEmail($_POST['LoginEmail'])){

        ErrAdder("Email" ,checkEmail($_POST['LoginEmail']));
    }


   if(authentication($_POST['LoginEmail'] ,$_POST['LoginPassword'] )){

    $token = sha1(md5($_POST['LoginEmail']));

    header('Location:'.ADDRESS."?main=true&token=$token");


   }
   else{

    ErrAdder("Email" , "Email or Passowrd are Wrong!");

    pageReqHandler("Login" , ["error" => $err]);

    set_url(ADDRESS."?Login=true");
   
   }
 
}


if(isset($_POST['btnUpload'])){

    $email = $_POST['Email'];

    $orginalFile = $_POST['fileRename'];

    $update = $_POST['name'];

    $absolutePath = $_POST['track'];

    $root = $_POST['root'];

    $token = $_POST['token'];


    if(strlen($absolutePath) == 0){

        $absolutePath = $root.DIRECTORY_SEPARATOR;

    }

    updateFileName($email , $orginalFile , $update );

    renameFiles($absolutePath , $orginalFile , $update);

    pageReqHandler('main',['page' => $root , 'token' => $token  , 'track' => $absolutePath]);
 
 
}



if(isset($_POST['btnDelete'])){

    $email = $_POST['Email'];

    $target = $_POST['deleteFile'];

    $absolutePath = $_POST['track'];

    $root = $_POST['root'];

    $token = $_POST['token'];

    $type = $_POST['type'];

    if(strlen($absolutePath) == 0){
        
        $absolutePath = $root.DIRECTORY_SEPARATOR;
    }


   $newPageItem =  newPageItemGnrator($absolutePath);
  
  
    deleteFile($email , $root ,$target  , $type);

    if($type == 1 ){

        deletePhysicalFile($absolutePath , $target);

        pageReqHandler('main',['page' => $root , 'token' => $token  , 'track' => $absolutePath]);

    }else{

        deleteUnrecursiveFile($absolutePath ,$target);

        pageReqHandler('main',['page' => $newPageItem[0] , 'token' => $token  , 'track' => $newPageItem[0]]);
    }

    
}


function ReadAll($Isarray= true){

    $data = file_get_contents(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."database".DIRECTORY_SEPARATOR."database.json" );

    return json_decode($data,$Isarray);
}



function InsertUser($email , $username , $password){

    $data = ReadAll();

    $fileName = AssignPathToUser($email);

    $data['usersAccounts'][]=  [$email=>["username" => $username , "password" => $password , "path" =>[ $fileName =>[]]  ]] ;

    file_put_contents(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."database".DIRECTORY_SEPARATOR."database.json" ,json_encode($data,JSON_PRETTY_PRINT));

    return true;
}

function AssignPathToUser($email){
    $fileName = md5($email);
    mkdir(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."storage".DIRECTORY_SEPARATOR.$fileName);
    return $fileName;
}




function getUsersFile($Email , $name=null){

    $data = ReadAll();

    global $treeResalt;

    foreach($data['usersAccounts'] as $key=>$value){

        if( in_array($Email , array_keys($value))){
            
            $path = $value[$Email]["path"];
            
            if($name ==null){
                return $path[key($path)];
            }


            traversTree($data['usersAccounts'][$key][$Email]['path'] , $name );

            return $treeResalt;
         

        }}
}   


function getUserUploads($Email , $name){

    global $treeUploadResalt;

    $data = ReadAll();

    foreach($data['usersAccounts'] as $key=>$value){

        if( in_array($Email , array_keys($value))){

            traversUploadTress($data['usersAccounts'][$key][$Email]['path'] , $name);

            return $treeUploadResalt;
     
        }}

}



function traversTree(&$tree , $file ){

    global $treeResalt;

    global $newFileAdd;
    
    foreach($tree as $branch=>&$leaf){
    
         
            if(is_array($leaf)){
             
                if($branch == $file){

                    if($newFileAdd != null){
                         $leaf[] = [$newFileAdd=> []];
                    }
        
                    $treeResalt[] = $leaf;
                }

                traversTree($leaf , $file);
            }
            
    }

    return false;
}



function traversUploadTress(&$tree , $root){

    global $treeUploadResalt;

    if(is_array($tree)){
        foreach($tree as $branch => &$leaf){

                if(is_array($leaf)){
                
                    if($branch == $root){

                        foreach($leaf as $layerOne){
                            
                            foreach($layerOne as $key=>$layerTwo){
                                if(is_array($layerTwo) == false){

                                     $treeUploadResalt[] = [$key => $layerTwo];
                                    
                                }
                            }
                            
                            
                        }
                        
                    }
                   
                    traversUploadTress($leaf , $root);
                }
            }
    }
   
   
    return false;
}


function updateFileName($Email , $orginalFile , $update){

    $data = ReadAll();
  
    foreach($data['usersAccounts'] as $key=>$value){

        if( in_array($Email , array_keys($value))){
            
             updateTree($data['usersAccounts'][$key][$Email]['path'], $orginalFile , $update);

             file_put_contents( __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."database".DIRECTORY_SEPARATOR."database.json",json_encode($data , JSON_PRETTY_PRINT));
        }}
        
}


function updateTree(&$tree , $orginalFile , $update){

    foreach($tree as $branch => &$leaf){

        if(is_array($leaf)){
            
            if($branch == $orginalFile){

                $updateArr = [];

                $updateArr[$update] = $leaf;

                $tree = $updateArr;
            }

            updateTree($leaf , $orginalFile , $update);    
                  

        }
    }
}


function renameFiles($absolutePath  , $orginalFile , $uploaded){

    $path =__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."storage";

    $absolutePath = explode("/" ,$absolutePath);

    foreach($absolutePath as $folder){

     $path .= DIRECTORY_SEPARATOR.$folder;

    }

    $destination = $path.$uploaded;

    $path .= $orginalFile;

    rename($path ,  $destination);
}



function deleteFile($Email ,$root , $target , $action){

    $data = ReadAll();
  
    foreach($data['usersAccounts'] as $key=>$value){

        if( in_array($Email , array_keys($value))){

            deleteFilleTree($data['usersAccounts'][$key][$Email]['path'], $root , $target , $action);

            file_put_contents( __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."database".DIRECTORY_SEPARATOR."database.json",json_encode($data , JSON_PRETTY_PRINT));
        }}

}


function deleteFilleTree(&$tree ,$root ,$target , $action){


    foreach($tree as $branch => &$leaf){

        if(is_array($leaf)){
            if($root == $branch){


                  if($action == "1"){

                    foreach($tree[$branch] as $key=>$layerOne){

                            $possibleTarget = $tree[$branch][$key];

                            if(key($possibleTarget) == $target){

                                unset($tree[$branch][$key]);

                            }
                      }
                  }

                  if($action == "2"){

                    foreach($tree[$branch] as $key=>$layerOne){

                            $possibleTarget = $tree[$branch][$key];

                            if(key($possibleTarget) == $target){

                                $savedData = $possibleTarget[$target];

                                unset($tree[$branch][$key]);

                                foreach($savedData as $item){

                                    $tree[$branch][] = $item;
                                }
                                
                            }
                      }
                  }


            }

            deleteFilleTree($leaf  , $root,$target , $action);
        }

    }

}

function deletePhysicalFile($absolutePath , $target){

    $path =__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."storage";

    $absolutePath = explode("/" ,$absolutePath);

    foreach($absolutePath as $folder){

     $path .= DIRECTORY_SEPARATOR.$folder;
     
    }

    $path .=$target;

    if(file_exists($path)){

        removeFiles($path);

        return true;

    }else{

        return false;

    }
}



function removeFiles($target) {
  

        if(is_dir($target)){

            $files = glob( $target . '*', GLOB_MARK );
        
            foreach( $files as $file ){
                
                removeFiles( $file );      
    
            }
    
            if(file_exists($target)){

                rmdir( $target );
            }
    
        } elseif(is_file($target)) {
    
            unlink( $target );  
    
        }  

}


function deleteUnrecursiveFile($absolutePath , $target){

    $path =__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."storage";

    $absolutePath = explode("/" ,$absolutePath);

    foreach($absolutePath as $folder){
        
     $path .= DIRECTORY_SEPARATOR.$folder;

    }

    $targetPath = $path.$target;

    $contex = scandir($targetPath);

    foreach($contex as $file){

        if($file !== "." && $file  != ".."){

        $destination = $path.$file;    

        rename( $targetPath.DIRECTORY_SEPARATOR.$file ,  $destination );

        }
    }

    rmdir($targetPath);
}


function newPageItemGnrator($track){

$trackItems = explode("/" , $track);

$trackItems = array_slice($trackItems ,0 , count($trackItems)-1);

unset($trackItems[count($trackItems)-1]);

$root =end($trackItems);

$newTrack = "";

foreach($trackItems as $item){

    $newTrack .= $item."/";

}

return [$root , $newTrack];
}



