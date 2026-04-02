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
    // global $mif_mr_opop;
    // echo $mif_mr_opop->get_download_link( $type );
    global $mr;
    echo $mr->get_download_link( $type );
}





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

function mif_mr_the_templates()
{
    global $mif_mr_param;
    echo $mif_mr_param->get_item_templates();
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

function mif_mr_the_link_edit_visual()
{
    global $mif_mr_set_comp;
    global $mif_mr_set_courses;
    // global $mif_mr_set_references;

    if ( ! empty( $mif_mr_set_comp ) ) {

        echo $mif_mr_set_comp->get_link_edit_visual();
        return;

    }

    if ( ! empty( $mif_mr_set_courses ) ) {

        echo $mif_mr_set_courses->get_link_edit_visual();
        return;

    }
    
}


//
// Выводит 
//

function mif_mr_the_link_edit_easy()
{
    global $mif_mr_set_references;
    global $mif_mr_attributes;
    global $mif_mr_param;
    
    if ( ! empty( $mif_mr_set_references ) ) {

        echo $mif_mr_set_references->get_link_edit_easy();
        return;
  
    }
    
    if ( ! empty( $mif_mr_attributes ) ) {

        echo $mif_mr_attributes->get_link_edit_easy();
        return;
  
    }
    
    if ( ! empty( $mif_mr_param ) ) {

        echo $mif_mr_param->get_link_edit_easy();
        return;
  
    }
    
}



//
// Выводит 
//

function mif_mr_the_link_edit_advanced()
{
    global $mif_mr_set_comp;
    global $mif_mr_set_courses;
    global $mif_mr_set_references;
    global $mif_mr_attributes;
    global $mif_mr_param;
    global $mr;

    if ( ! empty( $mif_mr_set_comp ) ) {

        echo $mif_mr_set_comp->get_link_edit_advanced( 'set-competencies' );
        return;

    }

    if ( ! empty( $mif_mr_set_courses ) ) {

        echo $mif_mr_set_courses->get_link_edit_advanced( 'set-courses' );
        return;

    }

    if ( ! empty( $mif_mr_set_references ) ) {

        echo $mif_mr_set_references->get_link_edit_advanced( 'set-references' );
        return;

    }

    if ( ! empty( $mif_mr_attributes ) ) {

        echo $mif_mr_attributes->get_link_edit_advanced( 'attributes' );
        return;

    }

    // if ( ! empty( $mif_mr_param ) ) {

    //     echo $mif_mr_param->get_link_edit_advanced( 'param' );
    //     return;

    // }

    echo $mr->get_link_edit_advanced();


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

function mif_mr_the_list_courses()
{
    global $mif_mr_courses;
    echo $mif_mr_courses->get_list_courses();
}

//
// Выводит 
//

function mif_mr_the_main()
{
    global $mif_mr_main;
    echo $mif_mr_main->get_main();
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

function mif_mr_the_attributes()
{
    global $mif_mr_attributes;
    echo $mif_mr_attributes->get_attributes();
}


//
// Выводит 
//

function mif_mr_the_curriculum()
{
    global $mif_mr_curriculum;
    echo $mif_mr_curriculum->get_curriculum();
}


//
// Выводит 
//

function mif_mr_the_set_comp()
{
    global $mif_mr_set_comp;
    echo $mif_mr_set_comp->show_set_comp();
}



//
// Выводит 
//

function mif_mr_the_set_courses()
{
    global $mif_mr_set_courses;
    echo $mif_mr_set_courses->show_set_courses();
}




//
// Выводит 
//

function mif_mr_the_set_references()
{
    global $mif_mr_set_references;
    echo $mif_mr_set_references->show_set_references();
}


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

//
// Выводит 
//

function mif_mr_tools_templates()
{
    global $mif_mr_tools_templates;
    echo $mif_mr_tools_templates->get_tools_templates();
}


//
// Выводит 
//

function mif_mr_remove_all( $type = 'courses' )
{
    if ( $type == 'courses' ) {

        global $mif_mr_tools_courses;
        echo $mif_mr_tools_courses->remove_all_link( 'courses' );

    }

    if ( $type == 'curriculum' ) {

        global $mif_mr_tools_curriculum;
        echo $mif_mr_tools_curriculum->remove_all_link( 'curriculum' );

    }

}



//
// Выводит 
//

function mif_mr_the_course( $type = 'lib-courses' )
{
    if ( $type == 'lib-courses' ) {

        global $mif_mr_lib_courses;
        echo $mif_mr_lib_courses->get_course();
        
    } elseif ( $type == 'courses' ) {
        
        global $mif_mr_courses;
        echo $mif_mr_courses->get_course();    
    
    }

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