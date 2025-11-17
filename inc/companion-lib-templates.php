<?php

//
// Функции шаблонов
// 
//


defined( 'ABSPATH' ) || exit;


// //
// // Выводит 
// //

// function mif_mr_show_lib_courses()
// {
//     global $mif_mr_lib_courses;
//     echo $mif_mr_lib_courses->show_lib_courses();
// }

//
// Выводит 
//

function mif_mr_show_courses()
{
    global $mif_mr_lib_courses;
    echo $mif_mr_lib_courses->show_courses();
}


//
// Выводит 
//

function mif_mr_show_lib_comp()
{
    global $mif_mr_comp;
    echo $mif_mr_comp->show_lib_comp();
}



//
// Выводит 
//

function mif_mr_show_comp()
{
    global $mif_mr_comp;
    echo $mif_mr_comp->show_comp();
}


//
// Выводит 
//

function mif_mr_the_comp_id()
{
    echo mif_mr_companion_core::get_comp_id();
}


//
// Выводит 
//

function mif_mr_the_advanced_edit_link()
{
    echo mif_mr_companion_core::get_advanced_edit_link();
}


//
// Выводит 
//

function mif_mr_the_edit_link()
{
    echo mif_mr_companion_core::get_edit_link();
}


//
// Выводит 
//

function mif_mr_the_remove_link()
{
    echo mif_mr_companion_core::get_remove_link();
}


?>