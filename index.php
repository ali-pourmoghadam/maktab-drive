<?php
require_once(__DIR__.DIRECTORY_SEPARATOR."pages".DIRECTORY_SEPARATOR."config.php");


if(!empty($_GET)){
    pageReqHandler(key($_GET));
}else{
    pageReqHandler();
}
