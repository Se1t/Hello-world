<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 28.02.2017
 * Time: 19:50
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

if (isset($_GET['msg'])){
    echo '<div class="log">'.$_GET['msg'].'</div>';
}
$res_arr = showFinded('#'.$_GET['htag']);
$htags_arr = showHtags();
$htags ='';

foreach ($res_arr as $one){
    foreach ($htags_arr as $two){
        if ($two[0] == $one[0]){
            $two[1] = trim($two[1]);
            $htags = $htags.' <a href="../find.php?htag='.ltrim ($two[1],'#').'">'.$two[1].'</a>';
        }
    }
    include('views/recordForm.html');
    $htags ='';
    $_SESSION['role'] == 0 ? include('views/recordFormMenuAdmin.html') : include('views/recordFormMenuDefault.html');
}

require_once('views/footer.html');

