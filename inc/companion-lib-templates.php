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

function mif_mr_the_course()
{
    global $mif_mr_lib_courses;
    echo $mif_mr_lib_courses->get_course();
}


//
// Выводит 
//

function mif_mr_the_lib_courses()
{
    global $mif_mr_lib_courses;
    echo $mif_mr_lib_courses->get_lib_courses();
}


//
// Выводит 
//

function mif_mr_the_lib_references()
{
    global $mif_mr_lib_references;
    echo $mif_mr_lib_references->get_lib_references();
}


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

function mif_mr_show_references()
{
    global $mif_mr_lib_references;
    echo $mif_mr_lib_references->show_references();
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
    echo mif_mr_companion_core::show_comp_id();
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