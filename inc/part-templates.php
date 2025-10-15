<?php

//
// Функции шаблонов
// 
//


defined( 'ABSPATH' ) || exit;


//
// Выводит 
//

function mif_mr_the_parents()
{
    global $mif_mr_param;
    echo $mif_mr_param->get_item_parents();
}


//
// Выводит 
//

function mif_mr_the_text( $key )
{
    global $mif_mr_part;
    echo $mif_mr_part->get_item_text( $key );
}



//
// Выводит 
//

function mif_mr_the_from_id( $key, $main_key = 'param' )
{
    global $mif_mr_part;
    echo $mif_mr_part->get_from_id( $key, $main_key );
}



//
// Выводит 
//

function mif_mr_the_user( $key = 'admins' )
{
    global $mif_mr_param;
    echo $mif_mr_param->get_item_user( $key );
}



//
// Выводит 
//

function mif_mr_the_link_edit()
{
    global $mif_mr_part;
    echo $mif_mr_part->get_link_edit();
}


//
// Выводит 
//

function mif_mr_the_form_begin()
{
    global $mif_mr_part;
    echo $mif_mr_part->get_form_begin();
}


//
// Выводит 
//

function mif_mr_the_form_end()
{
    global $mif_mr_part;
    echo $mif_mr_part->get_form_end();
}

//
// Выводит 
//

function mif_mr_the_courses()
{
    global $mif_mr_courses;
    echo $mif_mr_courses->get_courses();
}


//
// Выводит 
//

function mif_mr_the_matrix()
{
    global $mif_mr_matrix;
    echo $mif_mr_matrix->get_matrix();
}


//
// Выводит 
//

function mif_mr_the_curriculum()
{
    global $mif_mr_curriculum;
    echo $mif_mr_curriculum->get_curriculum();
}




 
    
    
    
    

    





?>