<?php

//
// Функции шаблонов
// 
//


defined( 'ABSPATH' ) || exit;


//
// Выводит 
//

function mif_mr_show_list_competencies()
{
    global $mif_mr_competencies;
    echo $mif_mr_competencies->show_list_competencies();
}



//
// Выводит 
//

function mif_mr_show_competencies()
{
    global $mif_mr_competencies;
    echo $mif_mr_competencies->show_competencies();
}


?>