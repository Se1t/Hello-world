<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 24.02.2017
 * Time: 16:07
 */
session_start();

if (!isset($_SESSION['role'])){
    $_SESSION['role'] = 2;
}

require_once('views/header.html');
if (!isset($_SESSION['logined'])){
    require_once('views/login.html');
}else{
    require_once('views/logoutForm.html');
}

require_once('views/content.html');
require ('CRUD.php');

$id = $_GET['id'];
$oldData = showOne($id);
$htags_arr = showHtags($id);
$htags = '';
$one[0] = $oldData[0];

foreach ($htags_arr as $two){
    if ($two[0] == $oldData[0]){
        $htags = $htags.' <a href="../find.php?htag='.ltrim ($two[1],'#').'">'.$two[1].'</a>';
    }
}

require_once('views/blogRecord.html');

if ($_SESSION['role'] == 0 || @$_SESSION['login'] == $oldData[6]){
    include('views/recordFormOneAdmin.html');
}else{
    include('views/recordFormOneDefault.html');
}

if (isset($_GET['msg'])){
    echo '<div class="log">'.$_GET['msg'].'</div>';
}

$comm = showComments($id);

foreach ($comm as $one){
    include ('views/commentForm.html');

    if ( @$_SESSION['userId'] == $one[1] ||  @$_SESSION['role'] == 0){
        include('views/commentFormMenuOwner.html');
    }else{
        include('views/commentFormMenuDefault.html');
    }
}

if ($_SESSION['role'] == 1 || $_SESSION['role'] == 0){
    require_once ('views/addComment.html');
}

require_once('views/footer.html');

