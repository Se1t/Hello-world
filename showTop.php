<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 28.02.2017
 * Time: 21:20
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

$blist = showBlogerList();
foreach ($blist as $one){
    $top = countTop($one[0]);
    $topList[$one[0]] = $top;
}

array_multisort($topList, SORT_DESC);

function test_print($item2, $key)
{
    echo '<a href="blogerRec.php?name='.$key.'">'.$key.': ('.$item2.')</a><br><br>';
}

echo '<div class="blogerList">';


array_walk($topList, 'test_print');


echo '</div>';
require_once('views/footer.html');
