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
// Выводит панель (Модули/Дисциплины)
//

function mif_mr_show_panel( $type = 'courses' )
{
    global $mif_mr_opop;
    echo $mif_mr_opop->show_panel( $type );
}



//
// Вернуться к странице раздела
//

function mif_mr_show_back( $type = 'courses' )
{
    global $mif_mr_opop;
    echo $mif_mr_opop->show_back( $type );
}






//
// Выводит ОПОП ID
//

function mif_mr_the_opop_id()
{
    global $mif_mr_opop;
    echo $mif_mr_opop->get_opop_id();
}





//
// Выводит ОПОП URL
//

function mif_mr_the_opop_url()
{
    global $mif_mr_opop;
    echo $mif_mr_opop->get_opop_url();
}






//
// Выводит 
//

function mif_mr_download_link( $type = 'text' )
{
    global $mif_mr_opop;
    echo $mif_mr_opop->get_download_link( $type );
}





// // 
// // 
// // 

// function mif_mr_the_tab( $text, $slug, $flag = false )
// {
//     $s = '';
    
//     $f = ( isset( $_REQUEST[$slug] ) ) || ( empty( $_REQUEST ) && $flag );

//     if ( ! $f ) $s .= '<a href="?' . $slug . '">';
//     $s .= $text;
//     if ( ! $f ) $s .= '</a>';

//     echo $s;
// }




?>