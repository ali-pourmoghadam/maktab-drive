<?php
if(isset($_GET['token']) || isset($data['token'])){
    require_once(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."database.php");
    require_once(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."validation.php");
    require_once(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."fileManager.php");



    if(isset($data['token'])){
        $token = $data['token'];
    }else{
        $token = $_GET['token'];
    }


    if(!validationToken($token)){
        header('Location:'.ADDRESS.'/?Login=true');

    }


    function showUploadFiles(&$directory ){
        
        $uploadedFile = [];

        foreach($directory as $index=>&$file){
            $key = key($file);
           
            if(is_array($file[$key]) == false){
                $uploadedFile[] = $key;
                unset($directory[$index]);
            }
        }
       return $uploadedFile;

    }
  
    $email =validationToken($token);
    $path = md5($email);
    
    
    
    $files = [];
    $uploadedFile = [];
    $currentFile =  $path;
    $subDirectory = [];
    $DIRECTORY_TRACK = "";




    if(isset($_POST['subDirectory'])){

            $files = [];
            $subDirectory =  getUsersFile($_POST['email'] , $_POST['name'])[0];
            $uploadedFile =  showUploadFiles($subDirectory);
            $currentFile = $_POST['name'];
            if(isset($_POST['track'])){

                $tarckRes = explode("/" , $DIRECTORY_TRACK);
                
                if(strlen($_POST['track']) == 0){

                    $DIRECTORY_TRACK = $path."/".$_POST['name']."/";
                }else{

                    $DIRECTORY_TRACK .= $_POST['track'].$_POST['name']."/";
                }

            }
     
           
    
    }elseif(isset($data['page'])){

        $files = [];
        $subDirectory =  getUsersFile($email, $data['page'])[0];
        $uploadedFile =  showUploadFiles($subDirectory);
        $currentFile = $data['page'];
        $DIRECTORY_TRACK = $data['track'];  
        set_url(ADDRESS."\?main=ture&token=$token");

    }else{
        $files = getUsersFile($email);
        $uploadedFile = showUploadFiles( $files);
    }
   


    // if(isset($data['uploadPage'])){
    //   $res =   getUserUploads($email , $currentFile);
    //   $currentFile = $data['uploadPage'];
    //   set_url(ADDRESS."\?main=ture&token=$token");
    // }




}else{
    header('Location:'.ADDRESS.'/?Login=true');
}

?>




<nav class="navbar navbar-light  navbar-custom fixed-top">
  <div class="container-fluid">
    <div class="navbar-brand mb-0 h1">
        <img class="img-fluid drive-logo-custom" src="<?php echo ADDRESS?>/public/img/Drive-logo.png" alt="">
        <span>Maktab Drive</span>
    </div>
  </div>
</nav>


<div class="right-click-contex pagedeActive">
 
        <span  data-bs-toggle="modal" data-bs-target="#removeModal">Remove</span>
        <span  data-bs-toggle="modal" data-bs-target="#renameModal">Rename</span>
 
</div>


<?php 
if(isset($data['error'])){
?>

<div class="error">
    <span class="close-err">&#10006</span>
    <p><?php echo $data['error']; ?></p>
</div>


<?php } ?>

<div class="main-wrapper row d-flex flex-row">
    <div class="main-sideBar">
        <div class="mian-sideBar-btn">
            <img class="plus" src="<?php echo ADDRESS ?>/public/img/plus.png" alt="">
            <span>New</span>
        </div>
    
        <div class="main-action-warpers pagedeActive">
          
                    <p class="action-text text-action-wrapper"  data-bs-toggle="modal" data-bs-target="#exampleModal">create folder</p>
                    <p class="action-text text-action-wrapper">


                        <form method="POST" enctype="multipart/form-data" action="<?php echo ADDRESS ?>app/fileManager.php">
                            <label for="inputFile" id="inputFileLabel">Upload</label>
                            <input type="file" id="inputFile"  name="upload">
                            <input name="email" type="hidden" value="<?php echo $email?>">
                            <input name="root"  value="<?php echo $currentFile; ?>" type="hidden" >
                            <input name="token" value="<?php echo $token; ?>" type="hidden">
                            <input name="track" value="<?php echo $DIRECTORY_TRACK; ?>" type="hidden">
                            <div class="upload-modal-wrapper pagedeActive">
                                <div class="upload-modal">
                                    <p class="upload-modal-txt"></p>
                                    <div class="upload-modal-btn-wrapper">
                                        <button name="uploadBtn" class="btn btn-success">yes</button>
                                        <button type="button" class="btn btn-danger btn-dismiss-upload">no</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </p>
        </div>
    </div>



    <div class="main-body">

        <h1 class="mian-body-heading">Files: </h1>
        <div class="file-wraper ">
            <?php
            if(!empty($files)){
               
                foreach($files as $file){
                  
                    ?>
         <form method="POST">
          
            <input name="name" type="hidden" value="<?php echo  key($file)?>">
            <input name="email" type="hidden" value="<?php echo $email?>">
            <input name="track" value="<?php echo $DIRECTORY_TRACK; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
            <button name="subDirectory" class="files"> 
                <img class="image-file-icon" src="<?php echo ADDRESS ?>/public/img/folder.png" alt="<?php echo  key($file)?>">
                <span><?php echo key($file)?></span>
            </button>
        </form>
        
            <?php
                }}
         if($subDirectory){
            foreach($subDirectory as $subfoler){
                    ?>
                    <form method="POST">
                        <input name="name" type="hidden" value="<?php echo  key($subfoler)?>">
                         <input name="email" type="hidden" value="<?php echo $email?>">
                         <input name="track" value="<?php echo $DIRECTORY_TRACK; ?>" type="hidden">
                        <button name="subDirectory" class="files"> 
                            <img class="image-file-icon" src="<?php echo ADDRESS ?>/public/img/folder.png" alt="<?php echo  key($subfoler)?>">
                            <span><?php echo key($subfoler)?></span>
                        </button>
                    </form>
    
            <?php
                }}
                
            ?>
        </div>

        <h1 class="mian-body-heading">Uploads: </h1>

        <div class="file-wraper ">
        <?php
            
         if($uploadedFile){
            foreach($uploadedFile as $upload){
                
                    ?>
                                    
                        <input name="track" value="<?php echo $DIRECTORY_TRACK; ?>" type="hidden">
                        <button name="subDirectory" type="button" class="files"> 
                            <img class="image-file-icon" src="<?php echo ADDRESS ?>/public/img/uploadIcon.png" alt="">
                            <span><?php echo  $upload?></span>
                        </button>
                    
    
            <?php
                }}
                
            ?>

        </div>

        
    </div>
</div>





<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Enter File Name</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
          <form  action="<?php echo ADDRESS ?>app/fileManager.php" method="post">

            <div class="modal-body">
         
                    <input name="FileName" type="text" id="inputPassword5" class="form-control" aria-describedby="passwordHelpBlock">
                    <input name="Email" value="<?php echo $email; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
                    <input name="path"  value="<?php echo $path; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
                    <input name="root"  value="<?php echo $currentFile; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
                    <input name="token" value="<?php echo $token; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
                    <input name="track" value="<?php echo $DIRECTORY_TRACK; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
                  
                  
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button name="fileRegister" type="submit" class="btn btn-primary">Create</button>
            </div>

            </form>

    </div>
  </div>
</div>







<div class="modal fade" id="renameModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Rename</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="<?php echo ADDRESS ?>app/database.php">
            <input name="name" type="text" class="form-control" aria-describedby="passwordHelpBlock">
            <input name="Email" value="<?php echo $email; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
            <input name="root"  value="<?php echo $currentFile; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
                    <input name="token" value="<?php echo $token; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
                    <input name="track" value="<?php echo $DIRECTORY_TRACK; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
            <input name="fileRename" type="hidden" class="form-control fileRename " aria-describedby="passwordHelpBlock">
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="btnUpload"  class="btn btn-primary">Save changes</button>
        </form>   
      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="removeModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Choose Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <form method="POST" action="<?php echo ADDRESS ?>app/database.php">   
                <select class="form-select" name="type" aria-label="Default select example">
                    <option value="1">remove all files</option>
                    <option value="2">remove file and moves content to parent folder</option>
                </select>     
                <input name="Email" value="<?php echo $email; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
                <input name="root"  value="<?php echo $currentFile; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
                <input name="token" value="<?php echo $token; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
                <input name="track" value="<?php echo $DIRECTORY_TRACK; ?>" type="hidden"  class="form-control" aria-describedby="passwordHelpBlock">
                <input name="deleteFile" type="hidden" class="form-control deleteFile" aria-describedby="passwordHelpBlock">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button name="btnDelete" type="submit" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo ADDRESS ?>/public/js/main.js"></script>




