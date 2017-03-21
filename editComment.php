<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 27.02.2017
 * Time: 20:25
 */

session_start();
require ('CRUD.php');

if ($_GET['msg'] != ''){
    $idRec = $_GET['idRec'];
    header("Location: blogPage.php?id=$idRec");
}

require_once('views/header.html');
if (!isset($_SESSION['logined'])){
    require_once('views/login.html');
}else{
    require_once('views/logoutForm.html');
}
require_once('views/content.html');

$id = $_GET['id'];
$comm = preEditCom($id);


if (!isset($_SESSION['role'])){
    $msg = 'Только авторизированые пользователи могу редактировать свои коментарии';
    header("Location: registration.php?msg=$msg");
}else{
    require_once ('views/editComment.html');
}

if (isset($_POST['edit'])){
    $msg = editComment($id);
}

require_once('views/footer.html');

