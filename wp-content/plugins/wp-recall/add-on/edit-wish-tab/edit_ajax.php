<?php

//echo 'Пример 1 - передача завершилась успешно';
if( isset($_POST['wish_id'])){
    echo 'Пример 1 - передача завершилась успешно ' . $_POST['wish_id'];
    $html = get_the_title($_POST['wish_id']);
    //$html = round($_POST['wish_id'], 2);
   
    echo $html;
}