<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 16.02.2017
 * Time: 22:21
 */

require ('CRUD.php');

$id = $_GET['id'];
$msg = delete($id);
header("Location: index.php?msg=$msg");
