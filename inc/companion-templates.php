<?php

//
// Функции шаблонов
// 
//


defined( 'ABSPATH' ) || exit;


//
// Выводит 
//

function mif_mr_show_list_comp()
{
    global $mif_mr_list_comp;
    echo $mif_mr_list_comp->show_list_comp();
}



//
// Выводит 
//

function mif_mr_show_comp()
{
    global $mif_mr_comp;
    echo $mif_mr_comp->show_comp();
}


?>