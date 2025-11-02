<?php

//
// Функции шаблонов
// 
//


defined( 'ABSPATH' ) || exit;


//
// Выводит 
//

function mif_mr_show_lib_comp()
{
    global $mif_mr_lib_comp;
    echo $mif_mr_lib_comp->show_lib_comp();
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