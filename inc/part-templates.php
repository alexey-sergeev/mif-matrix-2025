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
    global $mif_mr_param;
    echo $mif_mr_param->get_item_text( $key );
}



//
// Выводит 
//

function mif_mr_the_from_id( $key, $main_key = 'param' )
{
    global $mif_mr_param;
    echo $mif_mr_param->get_from_id( $key, $main_key );
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
    global $mif_mr_param;
    echo $mif_mr_param->get_link_edit();
}


//
// Выводит 
//

function mif_mr_the_form_begin()
{
    global $mif_mr_param;
    echo $mif_mr_param->get_form_begin();
}


//
// Выводит 
//

function mif_mr_the_form_end()
{
    global $mif_mr_param;
    echo $mif_mr_param->get_form_end();
}




 
    
    
    
    

    





?>