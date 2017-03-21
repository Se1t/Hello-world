<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 02.03.2017
 * Time: 10:57
 */

session_start();
include_once ('CRUD.php');

if (isset($_POST['limit'])){

    $lastLimit = json_decode($_POST['limit']);
    $newLimit = $lastLimit+5;

    $res_arr = show($lastLimit, $newLimit);
    $htags_arr = showHtags();
    $htags ='';
    
    if (!empty($res_arr)){
        
        foreach ($res_arr as $one){
            foreach ($htags_arr as $two){
                if ($two[0] == $one[0]){
                    $two[1] = trim($two[1]);
                    $htags = $htags.' <a href="find.php?htag='.ltrim ($two[1],'#').'">'.$two[1].'</a>';
                }
            }
            //$top = include('views/recordForm.html');
            include('views/recordForm.html');

            $htags ='';
            if ($_SESSION['role'] == 0){
                //$bottom = include('views/recordFormMenuAdmin.html');
                include('views/recordFormMenuAdmin.html');
                //header('Content-type:application/json;charset=utf-8');
                //$i++;
                //$data = '{"record"'.$i.': "'.$top.$bottom.'"}';
                //echo json_encode($data);
            }else{
                //$bottom = include('views/recordFormMenuDefault.html');
                include('views/recordFormMenuDefault.html');
                //header('Content-type:application/json;charset=utf-8');
                //$i++;
                //$data = '{"record"'.$i.': "'.$top.$bottom.'"}';;
                //echo json_encode($data);
            }
        }
        
    }else{
        //$dbmsg = include_once ('views/dbmsg.html');
        include_once ('views/dbmsg.html');
        //header('Content-type:application/json;charset=utf-8');
        //$i++;
        //$data = '{"record"'.$i.': "'.$dbmsg.'"}';
        //echo json_encode($data);

    }


}

