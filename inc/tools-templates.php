<?php

//
// Функции шаблонов
// 
//


defined( 'ABSPATH' ) || exit;


//
// Выводит 
//

function mif_mr_form_upload( $text = '', $url = '' )
{
    echo mif_mr_upload::form_upload( $text, $url );
}


//
// Выводит 
//

function mif_mr_tools_curriculum()
{
    global $mif_mr_tools_curriculum;
    echo $mif_mr_tools_curriculum->get_tools_curriculum();
}


//
// Выводит 
//

function mif_mr_tools_courses()
{
    global $mif_mr_tools_courses;
    echo $mif_mr_tools_courses->get_tools_courses();
}




?>