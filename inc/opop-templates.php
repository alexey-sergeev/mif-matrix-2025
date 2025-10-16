<?php

//
// Функции шаблонов
// 
//


defined( 'ABSPATH' ) || exit;


//
// Выводит главное меню
//

function mif_mr_the_menu_item( $text = '', $url = '', $level_access = 0 )
{
    global $mif_mr_opop;
    echo $mif_mr_opop->get_menu_item( $text, $url, $level_access );
}



//
// Отображает часть 
//

function mif_mr_the_part()
{
    global $mif_mr_opop;
    echo $mif_mr_opop->get_part();
}



//
// Выводит сообщение 
//

function mif_mr_show_messages()
{
    global $mif_mr_opop;
    echo $mif_mr_opop->show_messages();
}




//
// Выводит пояснение
//

function mif_mr_show_explanation( $key = NULL )
{
    global $mif_mr_opop;
    echo $mif_mr_opop->show_explanation( $key );
}




// 
// 
// 

function mif_mr_the_tab( $text, $slug, $flag = false )
{
    $s = '';
    
    $f = ( isset( $_REQUEST[$slug] ) ) || ( empty( $_REQUEST ) && $flag );

    if ( ! $f ) $s .= '<a href="?' . $slug . '">';
    $s .= $text;
    if ( ! $f ) $s .= '</a>';

    echo $s;
}




?>