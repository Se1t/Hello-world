<?php
/**
 * Created by PhpStorm.
 * User: Avreliy
 * Date: 16.02.2017
 * Time: 20:02
 */

function add()
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg =  sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'INSERT INTO records(id_u, title, text, img) VALUES (?, ?, ?, ?)')) {

        $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
        $text = htmlspecialchars($_POST['record'], ENT_QUOTES);
        $uploaddir = 'img/';

        if ($_FILES['img']['tmp_name'] == null){
            $img = 'Task5-1/img/no_img.jpg';
        }else{
            $img = $uploaddir.basename($_FILES['img']['name']);
        }

        mysqli_stmt_bind_param($stmt, "isss", $_SESSION['userId'] , $title, $text, $img);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

        }else{
            $msg = sprintf("%d строк вставлено.\n", mysqli_stmt_affected_rows($stmt));
            if (mysqli_stmt_affected_rows($stmt) == 1){
                $msg = htagAdd(mysqli_stmt_insert_id($stmt), $_POST['tag'], $con);
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $msg;

}

function addComment($idRec)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg =  sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'INSERT INTO comments (id_u, id_r, comment) VALUES (?, ?, ?)')) {

        $comment = htmlspecialchars($_POST['comment'], ENT_QUOTES);


        mysqli_stmt_bind_param($stmt, "iis", $_SESSION['userId'], $idRec, $comment);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

        }else{
            $msg = sprintf("%d строк вставлено.\n", mysqli_stmt_affected_rows($stmt));

        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $msg;

}

function htagAdd($recordId, $tags, $con)
{
    $tags = htmlspecialchars($tags);
    $tags = explode(',', $tags);

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'INSERT INTO tags(id_r, id_t) VALUES (?, ?)')) {

        foreach ($tags as $one){

            $one = trim($one);

            $one = htmlspecialchars($one, ENT_QUOTES);

            $id = chkTag($one, $con);

            if (!is_int($id)){
                continue;
            }

            mysqli_stmt_bind_param($stmt, "ii", $recordId, $id);

            if (!mysqli_stmt_execute($stmt)) {
                $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

            }

        }

        $msg = sprintf("%d строк вставлено.\n", mysqli_stmt_affected_rows($stmt));

        mysqli_stmt_close($stmt);
    }

    return $msg;
}

function chkTag($tag, $con)
{
    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT id FROM taglist WHERE tagname=?')) {

            mysqli_stmt_bind_param($stmt, "s", $tag);

            if (!mysqli_stmt_execute($stmt)) {
                $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

            }

        $result = mysqli_stmt_get_result($stmt);
        $id = mysqli_fetch_row($result);
        mysqli_free_result($result);

        mysqli_stmt_close($stmt);

        if ($id == ''){
            $stmt = mysqli_stmt_init($con);
            if (mysqli_stmt_prepare($stmt, 'INSERT INTO taglist(tagname) VALUES (?)')) {

                mysqli_stmt_bind_param($stmt, "s", $tag);

                if (!mysqli_stmt_execute($stmt)) {
                    $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
                    return $msg;

                }

                $id = mysqli_stmt_insert_id($stmt);

                mysqli_stmt_close($stmt);

                return $id;

            }
        }

        return $id[0];

    }

}

function liked($recordId)
{
    $liked = 1;

    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg =  sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT id FROM likes WHERE id_u=? && id_r=? && liked=?')) {



        mysqli_stmt_bind_param($stmt, "iii", $_SESSION['userId'], $recordId, $liked);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

        }

        $result = mysqli_stmt_get_result($stmt);
        $id = mysqli_fetch_row($result);
        mysqli_free_result($result);

        mysqli_stmt_close($stmt);

        if ($id == ''){
            $stmt = mysqli_stmt_init($con);
            if (mysqli_stmt_prepare($stmt, 'INSERT INTO likes(id_u, id_r, liked) VALUES (?,?,?)')) {

                mysqli_stmt_bind_param($stmt, "iii", $_SESSION['userId'], $recordId, $liked);

                if (!mysqli_stmt_execute($stmt)) {
                    $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
                    return $msg;

                }

                mysqli_stmt_close($stmt);

            }
        }else{

            $stmt = mysqli_stmt_init($con);
            if (mysqli_stmt_prepare($stmt, 'DELETE FROM likes WHERE id=?')) {

                mysqli_stmt_bind_param($stmt, "i", $id[0]);

                if (!mysqli_stmt_execute($stmt)) {
                    $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
                    return $msg;

                }

                mysqli_stmt_close($stmt);
            }
        }

    }

    mysqli_close($con);

}

function countLikes($recordId)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg =  sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT COUNT(id) FROM likes WHERE id_r=?')) {


        mysqli_stmt_bind_param($stmt, "i", $recordId);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;

        }

        $result = mysqli_stmt_get_result($stmt);
        $res = mysqli_fetch_row($result);
        mysqli_free_result($result);

        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $res=$res[0];
}

function countTop($login)
{
    $top = 0;
    

    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg =  sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT r.id FROM records r LEFT JOIN users u ON r.id_u=u.id WHERE u.login=?')) {

        mysqli_stmt_bind_param($stmt, "s", $login);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;
        }

        $result = mysqli_stmt_get_result($stmt);
        $idRec = mysqli_fetch_all($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    }
    

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT COUNT(id) FROM likes WHERE id_r=?')) {

        foreach ($idRec as $one){

            mysqli_stmt_bind_param($stmt, "i", $one[0]);

            if (!mysqli_stmt_execute($stmt)) {
                $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
                return $msg;

            }

            $result = mysqli_stmt_get_result($stmt);
            $res = mysqli_fetch_row($result);
            mysqli_free_result($result);
            $top = $top + $res[0];

        }


    }

    mysqli_stmt_close($stmt);

    mysqli_close($con);
    return $top;
}

function countComents($recordId)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg =  sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT COUNT(id) FROM comments WHERE id_r=?')) {


        mysqli_stmt_bind_param($stmt, "i", $recordId);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;

        }

        $result = mysqli_stmt_get_result($stmt);
        $res = mysqli_fetch_row($result);
        mysqli_free_result($result);

        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $res=$res[0];
}

function delete($id)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT img FROM records WHERE id=?')) {

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

        }else{

            $result = mysqli_stmt_get_result($stmt);
            $res = mysqli_fetch_row($result);
            mysqli_free_result($result);

            if ($res[0] != 'Task5-1/img/no_img.jpg'){
                unlink($res[0]);
            }
        }

        mysqli_stmt_close($stmt);
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'DELETE FROM records WHERE id=?')) {

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

        }else{
            $msg = sprintf("%d строк удалено.\n", mysqli_stmt_affected_rows($stmt));
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $msg;

}

function deleteComment($id)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg =  sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'DELETE FROM comments WHERE id=?')) {

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;

        }else{
            $msg = sprintf("%d коментарий удален.\n", mysqli_stmt_affected_rows($stmt));
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $msg;

}

function edit($id)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg =  sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'UPDATE records SET title=?, text=?, img=? WHERE id=?')) {

        $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
        $text = htmlspecialchars($_POST['record'], ENT_QUOTES);

        if ($_POST['img'] == ''){
            $_POST['img'] = 'Task5-1/img/no_img.jpg';
        }

        mysqli_stmt_bind_param($stmt, "sssi", $title, $text, $_POST['img'], $id);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

        }else{
            $msg = sprintf("%d строк вставлено.\n", mysqli_stmt_affected_rows($stmt));
            if (mysqli_stmt_affected_rows($stmt) == 1){
                $msg = htagEdit($id, $_POST['tag'], $con);
            }
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $msg;

}

function htagEdit($recordId, $tags, $con)
{
    $tags = htmlspecialchars($tags);
    $tags = explode(',', $tags);

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'DELETE FROM tags WHERE id_r=?')) {

        mysqli_stmt_bind_param($stmt, "i", $recordId);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

        }

        mysqli_stmt_close($stmt);
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'INSERT INTO tags(id_r, id_t) VALUES (?, ?)')) {

        foreach ($tags as $one){

            $one = trim($one);

            $one = htmlspecialchars($one, ENT_QUOTES);

            $id = chkTag($one, $con);

            if (!is_int($id)){
                    continue;
            }

            mysqli_stmt_bind_param($stmt, "ii", $recordId, $id);

            if (!mysqli_stmt_execute($stmt)) {
                    $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

            }

        }

        $msg = sprintf("%d строк вставлено.\n", mysqli_stmt_affected_rows($stmt));

        mysqli_stmt_close($stmt);
    }

    return $msg;
}

function editComment($id)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'UPDATE comments SET comment=? WHERE id=?')) {

        mysqli_stmt_bind_param($stmt, "si", $_POST['comment'], $id);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

        }else{
            $msg = sprintf("%d строк отредактировано.\n", mysqli_stmt_affected_rows($stmt));
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $msg;

}

function preEditCom($id)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT id, id_r, comment FROM comments WHERE id=?')) {

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

        }else{
            $msg = sprintf("%d строк отредактировано.\n", mysqli_stmt_affected_rows($stmt));
        }

        $result = mysqli_stmt_get_result($stmt);
        $res = mysqli_fetch_row($result);
        mysqli_free_result($result);

        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $res;
}

function show($lastLimit, $newLimit)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT r.*, u.login FROM records r LEFT JOIN users u ON r.id_u=u.id ORDER BY r.date DESC LIMIT ?,?')) {

        mysqli_stmt_bind_param($stmt, "ii", $lastLimit, $newLimit);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;
        }

        $result = mysqli_stmt_get_result($stmt);
        $records = mysqli_fetch_all($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $records;
    
}

function showFinded($htag)
{

    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $records = array();

    $ftag = findHtag($htag);

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT r.*, u.login FROM records r LEFT JOIN users u ON r.id_u=u.id WHERE r.id=?')) {

        foreach ($ftag as $one){

            mysqli_stmt_bind_param($stmt, "i", $one[0]);

            if (!mysqli_stmt_execute($stmt)) {
                $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
                return $msg;
            }

            $result = mysqli_stmt_get_result($stmt);
            $records[] = mysqli_fetch_row($result);
            mysqli_free_result($result);


        }




        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $records;
}

function findHtag($htag)
{

    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $htag = trim($htag);

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT t.id_r FROM tags t LEFT JOIN taglist tl ON t.id_t=tl.id WHERE tl.tagname=?')) {

        mysqli_stmt_bind_param($stmt, "s", $htag);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;
        }

        $result = mysqli_stmt_get_result($stmt);
        $records = mysqli_fetch_all($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $records;
}

function showBlogerList()
{
    $limit = 5;

    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT login FROM users LIMIT ?')) {

        mysqli_stmt_bind_param($stmt, "i", $limit);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;
        }

        $result = mysqli_stmt_get_result($stmt);
        $blogers = mysqli_fetch_all($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $blogers;
    
}

function showBlogerRec($login)
{
    $limit = 5;

    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT r.*, u.login FROM records r LEFT JOIN users u ON r.id_u=u.id WHERE u.login=? ORDER BY r.date DESC LIMIT ?')) {

        mysqli_stmt_bind_param($stmt, "si", $login, $limit);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;
        }

        $result = mysqli_stmt_get_result($stmt);
        $records = mysqli_fetch_all($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $records;

}

function showPop()
{
    $limit = 5;
    $records = array();
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT l.id_r FROM likes l GROUP BY l.id_r ORDER BY COUNT(l.liked) DESC LIMIT ?')) {

        mysqli_stmt_bind_param($stmt, "i", $limit);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;
        }

        $result = mysqli_stmt_get_result($stmt);
        $idList = mysqli_fetch_all($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT r.*, u.login FROM records r LEFT JOIN users u ON r.id_u=u.id WHERE r.id=? LIMIT ?')) {

        foreach ($idList as $one){

            mysqli_stmt_bind_param($stmt, "ii", $one[0], $limit);

            if (!mysqli_stmt_execute($stmt)) {
                $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
                return $msg;
            }

            $result = mysqli_stmt_get_result($stmt);
            $records[] = mysqli_fetch_row($result);
            mysqli_free_result($result);

        }

        mysqli_stmt_close($stmt);

    }

    mysqli_close($con);
    return $records;

}


function showHtags()
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT r.id, tl.tagname FROM records r INNER JOIN tags t ON r.id=t.id_r INNER JOIN taglist tl ON t.id_t=tl.id ORDER BY r.id ASC')) {

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;
        }

        $result = mysqli_stmt_get_result($stmt);
        $htags = mysqli_fetch_all($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $htags;

}

function showOne($id)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT r.*, u.login FROM records r LEFT  JOIN users u ON r.id_u=u.id WHERE r.id=?')) {

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;
        }

        $result = mysqli_stmt_get_result($stmt);
        $res_arr = mysqli_fetch_row($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);

    return $res_arr;
}

function showLobby($id)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT r.*, u.login FROM records r LEFT  JOIN users u ON r.id_u=u.id WHERE r.id_u=?')) {

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;
        }

        $result = mysqli_stmt_get_result($stmt);
        $res_arr = mysqli_fetch_all($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);

    return $res_arr;
}

function showComments($id)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT c.*, u.login FROM comments c LEFT  JOIN users u ON c.id_u=u.id WHERE c.id_r=?')) {

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;
        }

        $result = mysqli_stmt_get_result($stmt);
        $comments = mysqli_fetch_all($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $comments;

}

/*Дальше авторизация*/

function login()
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT * FROM users WHERE login =?')) {

        mysqli_stmt_bind_param($stmt, "s", $_POST['login']);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;
        }

        $result = mysqli_stmt_get_result($stmt);
        $res_arr = mysqli_fetch_row($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    }

    $seshash = genSeshash();

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'UPDATE users SET seshash=? WHERE id=?')) {

        mysqli_stmt_bind_param($stmt, "si", $seshash, $res_arr[0]);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;
        }

        $msg = sprintf("%d строк вставлено.\n", mysqli_stmt_affected_rows($stmt));

        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);

    if ($_POST['login'] == $res_arr[1] && password_verify($_POST['password'], $res_arr[2]) && !isset($_POST['notMyPC'])){
        $_SESSION['logined'] = 'ok';
        $_SESSION['userId'] = $res_arr[0];
        $_SESSION['login'] = $res_arr[1];
        $_SESSION['role'] = $res_arr[4];
        setcookie ('login', $_POST['login'], time()+2592000);
        setcookie ('seshash', $seshash, time()+2592000);
        $_SESSION['role'] == 0 ? $_SESSION['roleName'] = 'Administrator' : $_SESSION['roleName'] = 'Bloger';
        return TRUE;

    }elseif($_POST['login'] == $res_arr[1] && password_verify($_POST['password'], $res_arr[2]) && isset($_POST['notMyPC'])){
        $_SESSION['logined'] = 'ok';
        $_SESSION['login'] = $res_arr[1];
        $_SESSION['role'] = $res_arr[4];
        $_SESSION['role'] == 1 ? $_SESSION['roleName'] = 'Administrator' : $_SESSION['roleName'] = 'Bloger';
        return TRUE;

    }else{
        if (!is_bool($msg = chkLogin($_POST['login'], $res_arr[1]))){
            return $msg;
        }elseif(!is_bool($msg = chkPass($_POST['password'], $res_arr[2]))){
            return $msg;
        }
    }


}

function genSeshash()
{
    return $res = md5(substr(md5(rand()), 3, 7).'blogname.com'.$_SERVER['REMOTE_ADDR']);
}

function chkLogined()
{

    if(isset($_COOKIE['login']) && isset($_COOKIE['seshash'])){

        $con = mysqli_connect("localhost","root","","softgroup");

        if (mysqli_connect_errno()) {
            $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
            return $msg;
        }

        $stmt = mysqli_stmt_init($con);
        if (mysqli_stmt_prepare($stmt, 'SELECT * FROM users WHERE login =?')) {

            mysqli_stmt_bind_param($stmt, "s", $_COOKIE['login']);

            if (!mysqli_stmt_execute($stmt)) {
                $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
                return $msg;
            }

            $result = mysqli_stmt_get_result($stmt);
            $res = mysqli_fetch_row($result);
            mysqli_free_result($result);
            mysqli_stmt_close($stmt);
        }

        mysqli_close($con);

        if ($_COOKIE['seshash'] == $res[5]){
            $_SESSION['logined'] = 'ok';
            $_SESSION['userId'] = $res[0];
            $_SESSION['login'] = $res[1];
            $_SESSION['role'] = $res[4];
            $_SESSION['role'] == 0 ? $_SESSION['roleName'] = 'Administrator' : $_SESSION['roleName'] = 'Bloger';
        }else{
            //header('Location: index.php');
        }
    }


}

function logout()
{
    if (isset($_POST['submit'])){

        $con = mysqli_connect("localhost","root","","softgroup");

        if (mysqli_connect_errno()) {
            $msg = sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
            return $msg;
        }

        $seshash = null;

        $stmt = mysqli_stmt_init($con);
        if (mysqli_stmt_prepare($stmt, 'UPDATE users SET seshash=? WHERE id=?')) {

            mysqli_stmt_bind_param($stmt, "si", $seshash, $_SESSION['userId']);

            if (!mysqli_stmt_execute($stmt)) {
                $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
                return $msg;
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_close($con);

        unset($_SESSION["logined"]);
        unset($_SESSION['login']);
        unset($_SESSION['role']);
        unset($_SESSION['userId']);
        unset($_SESSION['roleName']);

        if (isset($_COOKIE['login']) || isset($_COOKIE['seshash'])){
            setcookie ("login", '');
            setcookie ("seshash", '');
        }
        header('Location: index.php');
    }

}

function reg()
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg =  sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    if (chkReg($_POST['login']) == $_POST['login']){
        $msg =  'Такой пользователь уже зарегестрирован';
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'INSERT INTO users(login, password, email, role) VALUES (?, ?, ?, ?)')) {

        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = 1;

        mysqli_stmt_bind_param($stmt, "sssi", $_POST['login'], $pass, $_POST['email'], $role);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));

        }else{
            $msg = sprintf("%d пользователь зарегестрирован.\n", mysqli_stmt_affected_rows($stmt));
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $msg;
}

function chkReg($login)
{
    $con = mysqli_connect("localhost","root","","softgroup");

    if (mysqli_connect_errno()) {
        $msg =  sprintf("Не удалось подключиться: %s\n", mysqli_connect_error());
        return $msg;
    }

    $stmt = mysqli_stmt_init($con);
    if (mysqli_stmt_prepare($stmt, 'SELECT login FROM users WHERE login=?')) {

        mysqli_stmt_bind_param($stmt, "s", $login);

        if (!mysqli_stmt_execute($stmt)) {
            $msg = sprintf("Ошибка: %s\n", mysqli_stmt_error($stmt));
            return $msg;

        }
        
        $result = mysqli_stmt_get_result($stmt);
        $res_arr = mysqli_fetch_row($result);
        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    }

    mysqli_close($con);
    return $res_arr[0];
}

function emailValid($mail)
{
    $val = preg_match('/^[a-zA-Z][a-zA-z0-9_\.]+@[a-zA-Z]+\.[a-z]{2,3}(\.[a-z]{2,3})?$/', $mail);

    return $val;
}

function loginValid($login)
{
    $val = preg_match('/^[a-zA-Z][a-zA-z0-9_]{4,20}$/', $login);

    return $val;
}

function passValid($pass)
{
    $val = preg_match('/^.{5,20}$/', $pass);

    return $val;
}

function chkPass($pass, $truePass)
{
    if (password_verify($pass, $truePass)){
        return TRUE;
    }

    return $msg = 'Invalid password';
}

function chkLogin($login, $trueLogin)
{
    if ($login == $trueLogin){
        return TRUE;
    }

    return $msg = 'No such login registred';
}
