<?php

//
// Функции шаблонов
// 
//


defined( 'ABSPATH' ) || exit;




//
// Выводит категории для поиска
//

function mif_mr_the_category()
{
    global $mif_mr_catalog;
    echo $mif_mr_catalog->get_category();
}



//
// Выводит навигацию теста
//

function mif_mr_the_catalog()
{
    global $mif_mr_catalog;
    echo $mif_mr_catalog->get_catalog();
}


?>