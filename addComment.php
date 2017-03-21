<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 27.02.2017
 * Time: 11:23
 */

session_start();
require ('CRUD.php');
$idRec = $_GET['id'];
$msg = addComment($idRec);

header("Location: blogPage.php?id=$idRec&msg=$msg");
