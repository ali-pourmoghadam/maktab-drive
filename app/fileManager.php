<?php
require_once(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."pages".DIRECTORY_SEPARATOR."config.php");
require_once(__DIR__.DIRECTORY_SEPARATOR."database.php");



if(isset($_POST['fileRegister']) ){
      
        $FileName = $_POST['FileName'];

        $Email = $_POST['Email'];

        $root =  $_POST['root'];

        $token =  $_POST['token'];

        $absolutePath = $_POST['track'];

        if(strlen($absolutePath) == 0){

            $absolutePath = $root.DIRECTORY_SEPARATOR;
        }

        
        if(checkUploadFileExists($absolutePath , $FileName)){

            pageReqHandler('main',['page' => $root , 'token' =>$token , 'track' => $absolutePath , 'error' => 'file already exists']);
        }
     
        createFile($Email , $FileName , $root , $absolutePath);

        $addResualt =  pageReqHandler('main',['page' => $root , 'track' => $absolutePath,  'token' =>$token]);

    
}




if(isset($_POST['uploadBtn'])){
    
   if(isset($_FILES['upload'])){

        $fileName = $_FILES['upload']['name'];

        $fileSize = $_FILES['upload']['size'];

        $fileSize = $fileSize / pow(2,20);

        $fileSize = number_format( $fileSize, 2, '.', '');

        $token = $_POST['token'];

        $root =  $_POST['root'];
        
        $email = $_POST['email'];

        $absolutePath = $_POST['track'];

        if(strlen($absolutePath) == 0){

            $absolutePath = $root.DIRECTORY_SEPARATOR;

        }

        $fileType = $_FILES['upload']['type'];


        if($fileSize > 5){

             pageReqHandler('main',['page' => $root , 'token' =>$token , 'error' => 'max of upload size is 5 MB']);

        }
        

        if(checkUploadFileExists($absolutePath , $fileName)){
            pageReqHandler('main',['page' => $root , 'token' =>$token , 'error' => 'file already exists']);
        }
    
        $uploadPath =  handleUpload($email , $fileName, $root ,$fileType , $absolutePath );


        move_uploaded_file($_FILES['upload']['tmp_name'] , $uploadPath);
    
        $treeResalt = [];
        pageReqHandler('main',['page' => $root , 'token' => $token  , 'track' => $absolutePath]);
     

   }else{
      
       pageReqHandler('main',['page' => $root , 'token' =>$token , 'error' => 'no uploadfile exists']);
   }
}




function createFile($Email , $FileName , $root , $absolutePath){

      global $newFileAdd;

      
      $data = ReadAll();

      $target =$data['usersAccounts'] ;

      foreach($data['usersAccounts'] as $key=>$value){

        if( in_array($Email , array_keys($value))){

     

                 $path = $value[$Email]['path'];

                 if($root == key($path)){

                     $path[key($path)][] = [$FileName => []];

                     $data['usersAccounts'][$key][$Email]['path'] = $path;
                
                 }else{

                     $newFileAdd = $FileName;

                     traversTree($data['usersAccounts'][$key][$Email]['path'] ,$root);
    
                 }
                            
                 $resCraeteFile = PathGnrator($absolutePath , $FileName);

                 if($resCraeteFile)
                 {
                     file_put_contents( __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."database".DIRECTORY_SEPARATOR."database.json",json_encode($data , JSON_PRETTY_PRINT));
                 }
            
             
        }}

}


function PathGnrator($absolutePath ,$fileName , $isUpload = false){

   $path =__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."storage";

   $absolutePath = explode("/" ,$absolutePath);

   foreach($absolutePath as $folder){

    $path .= DIRECTORY_SEPARATOR.$folder;

   }
   $path .= $fileName;

   if(!file_exists($path)){

    if(!$isUpload){
        
        mkdir($path);

    }else{

        return $path;

    }

       return true;

   }else{

    return false;

   }
}



function handleUpload($Email , $fileName , $root , $fileType , $absolutePath){

    global $upload;
    global $uploadType;

    $uploadType = $fileType;

    $data = ReadAll();

    $target =$data['usersAccounts'] ;

    foreach( $target as $key=>$value){

        if( in_array($Email , array_keys($value))){

          
            $upload = $fileName;

            uploadToTree($data['usersAccounts'][$key][$Email]['path'] , $root); 

            file_put_contents( __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."database".DIRECTORY_SEPARATOR."database.json",json_encode($data , JSON_PRETTY_PRINT));

            return PathGnrator($absolutePath , $fileName , true);
        }
}
}


function uploadToTree(&$tree , $root ){

    global $upload;
    global $uploadType;
    global $treeResalt;

        foreach($tree as $branch => &$leaf){
            if(is_array($leaf)){
                  
                if($branch == $root && $upload != null){
                    $leaf[] = [$upload => $uploadType]; 
                }

                $treeResalt[] = $leaf;

                uploadToTree($leaf , $root);
            }
        }
        return false;
}


function checkUploadFileExists($absolutePath , $FileName){

   $path =__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."storage";

   $absolutePath = explode("/" ,$absolutePath);
   
   foreach($absolutePath as $folder){

    $path .= DIRECTORY_SEPARATOR.$folder;

   }

   $filesInsideDiretory = scandir($path);

   if(in_array($FileName , $filesInsideDiretory)){

    return true;

   }else{

    return false;

   }
}



