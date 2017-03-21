<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 25.02.2017
 * Time: 22:58
 */
session_start();
require ('CRUD.php');
if (loginValid($_POST['login'])){
    if (passValid($_POST['password'])){
        if (is_bool($msg = login())){
            header('Location: index.php');
        }else{
            header('Location: index.php?msg='.$msg);
        }
    }else{
        $msg = 'invalid pass it mast be 5-20 chars!';
        header('Location: index.php?msg='.$msg);
    }

}else{
    $msg = 'invalid login unsupported chars or lenth!';
    header('Location: index.php?msg='.$msg);
}
