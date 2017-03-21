<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 27.02.2017
 * Time: 0:07
 */
session_start();
require ('CRUD.php');

$id=$_GET['id'];

liked($id);

if(isset($_GET['jsReq'])){
    echo countLikes($id);
}else{
    if ($_GET['flag'] == 1){
        $header = "Location: blogPage.php?id=$id";
    }else{
        $header = 'Location: index.php';
    }
    header("$header");
}


