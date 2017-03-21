<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 27.02.2017
 * Time: 18:50
 */

require ('CRUD.php');

$id = $_GET['id'];
$page = $_GET['page'];
$msg = deleteComment($id);

header("Location: blogPage.php?id=$page&msg=$msg");

