<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 17.02.2017
 * Time: 11:08
 */
session_start();
if (!isset($_SESSION['role'])){
    $_SESSION['role'] = 2;
}
require_once('views/header.html');
if (!isset($_SESSION['logined'])){
    require_once('views/login.html');
    require_once('views/content.html');
    $msg = 'Только зарегистрированые пользователи могу добавлять записи';
    header("Location: registration.php?msg=$msg");
}else{
    $title = @$_POST['title'];
    $text = @$_POST['record'];
    $tags = @$_POST['tag'];
    $img = @$_POST['img'];
    require_once('views/logoutForm.html');
    require_once('views/content.html');
    require_once('views/add.html');
}

require ('CRUD.php');

if(isset($_POST['add'])){
    unset($_POST['add']);
    

    $uploaddir = 'img/';
    $uploadfile = $uploaddir.basename($_FILES['img']['name']);

    if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile)) {
        $msg2 = "Файл корректен и был успешно загружен.\n";
    } else {
        $msg2 =  "Возможная атака с помощью файловой загрузки!\n";
    }

    $msg = add();
    header("Location: index.php?msg=$msg");
}

require_once('views/footer.html');

