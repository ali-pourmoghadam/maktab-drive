<?php

// GLOBAL CONSTANT

const ADDRESS  = "http://localhost/maktab-tamrin/w5/";

const STYLE_PATH = ADDRESS."public/style/style.css";

const JS_BOOTSTRAP_PATH = ADDRESS."public/js/bootstrap.bundle.min.js";

const Header_PATH =  __DIR__.DIRECTORY_SEPARATOR."partials".DIRECTORY_SEPARATOR."header.php";

const FOOTER_PATH =  __DIR__.DIRECTORY_SEPARATOR."partials".DIRECTORY_SEPARATOR."footer.php";





// GLOBAL SETTING FUNCTIONS 

function fileLoader($path , $data = [] )
{
        if(file_exists($path)){
            require_once(Header_PATH);
            require_once($path);
            require_once(FOOTER_PATH);
        }else{
            die("page not found");
        }
}


function pageReqHandler($page ="login" , $data=[]){

        fileLoader(__DIR__.DIRECTORY_SEPARATOR.$page.".php" , array_merge(["title" => $page]  , $data));

}


function set_url( $url )
{
    echo("<script>history.replaceState({},'','$url');</script>");
}


