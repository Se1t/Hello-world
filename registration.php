<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 25.02.2017
 * Time: 22:05
 */
require ('CRUD.php');
require_once('views/header.html');
require_once('views/login.html');
require_once('views/content.html');

if (isset($_GET['msg'])){
    echo '<div class="log">'.$_GET['msg'].'</div>';
}

$login = @$_POST['login'];
$email = @$_POST['email'];
require_once('views/registrationForm.html');


if (isset($_POST['register'])){
    unset($_POST['register']);

    if (!loginValid($_POST['login'])){
        echo 'invalid login';
    }elseif(!passValid($_POST['password'])){
        echo 'invalid password';
    }elseif (!emailValid($_POST['email'])){
        echo 'invalid email';
    }else{
        if (($msg = reg()) == "1 пользователь зарегестрирован.\n"){
            header("Location: index.php?msg=$msg");
        }
        echo $msg;
    }



}

require_once('views/footer.html');
