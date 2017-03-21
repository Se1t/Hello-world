<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 16.02.2017
 * Time: 23:04
 */

session_start();

if (!isset($_SESSION['role'])){
    $_SESSION['role'] = 2;
}

require_once('views/header.html');

if (!isset($_SESSION['logined'])){
    $msg = 'Только зарегистрированые пользователи могу редактировать записи';
    header("Location: registration.php?msg=$msg");
}else{
    require_once('views/logoutForm.html');
}
require_once('views/content.html');
require ('CRUD.php');

if(isset($_POST['edit'])){
    unset($_POST['edit']);
    $msg = edit($_GET['id']);

    $idRec = $_GET['id'];

    header("Location: blogPage.php?id=$idRec&msg=$msg");
}

$id = $_GET['id'];
$oldData = showOne($id);
$htags_arr = showHtags($id);
$htags = '';

foreach ($htags_arr as $two){
    if ($two[0] == $oldData[0]){
        $htags = $htags.$two[1].', ';
    }
}

require_once('views/edit.html');

require_once('views/footer.html');

