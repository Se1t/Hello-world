<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 27.02.2017
 * Time: 22:33
 */

session_start();

require_once('views/header.html');
if (!isset($_SESSION['logined'])){
    $msg = 'Только зарегистрированые пользователи могу заходить в личный кабинет';
    header("Location: registration.php?msg=$msg");
}else{
    require_once('views/logoutForm.html');
}
require_once('views/content.html');
require ('CRUD.php');

if (isset($_GET['msg'])){
    echo '<div class="log">'.$_GET['msg'].'</div>';
}

$res_arr = showLobby($_SESSION['userId']);
$htags_arr = showHtags();
$htags ='';
foreach ($res_arr as $one){
    foreach ($htags_arr as $two){
        if ($two[0] == $one[0]){
            $htags = $htags.' <a href="../find.php?htag='.$two[1].'">'.$two[1].'</a>';
        }
    }
    include('views/recordForm.html');
    $htags ='';
    include('views/recordFormMenuAdmin.html');
}

require_once('views/footer.html');
