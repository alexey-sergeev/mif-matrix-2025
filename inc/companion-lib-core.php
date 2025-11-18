<?php

//
//  Связанная запись (дисциплины, компетенции или др.)
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_companion_core {
    

    function __construct()
    {

  
    }
  
 
    
     
    //
    // Список связанной записи
    //
    
    public function get_list_companions( $type = 'course', $opop_id = NULL )
    {
        // $content = '';
        
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        // p($type);
        // p($opop_id);
        $post = get_posts( array(
            'post_type' => $type,
            'post_status' => 'publish',
            'post_parent' => $opop_id,
            'posts_per_page' => -1,
            'order' => 'ASC',
            ) );
        
        $arr = array();

        foreach ( $post as $item ) {

            // p($item);
            $arr[] = array( 
                'id' => $item->ID,
                'title' => $item->post_title,
                'parent' => $item->post_parent,
                'content' => $item->post_content
            );

        }    
        // p($arr);

        return apply_filters( 'mif_mr_core_get_list_companions', $arr, $type, $opop_id );
    }
    
    
    //
    // save_all
    //
    
    public function save_all()
    {
        if ( empty( $_REQUEST['save'] ) ) return;
        
        // ####!!!!!


        $res = false;

        $content = sanitize_textarea_field( $_REQUEST['content'] );

        if ( ! empty( $this->get_comp_id() ) ) {

            // p($content);
            $res = $this->save( $this->get_comp_id(), $content );

        }

        return $res;
    }
        
  
    
    //
    // save_part
    //
   
    public function save_part( $sub_id, $comp_id, $opop_id, $add_key = false )
    {
        if ( ! ( isset( $_REQUEST['do'] ) && $_REQUEST['do'] == 'save' ) ) return;

        // ####!!!!!

        // $res = false;
        
        $arr = $this->get_sub_arr( $comp_id );
        // p($arr);
        // p($comp_id);
        
        if ( $sub_id == -1 ) $sub_id = (int) array_key_last( $arr ) + 1; 
        
        $content = sanitize_textarea_field( $_REQUEST['content'] );
        // if ( preg_match( '/^=/', $content ) ) $content = '_' . $content;
        if ( $add_key ) $content = preg_replace( '/\n==/', '__', $content );
        $arr[$sub_id] = $content;
        
        // p($_REQUEST);
        
        if ( $add_key ) foreach ( $arr as $key => $item ) $arr[$key] = '== ' . $key . "\n\n" . $arr[$key];
        
        $res = $this->save( $comp_id, implode( "\n", $arr ) );


        // $res = wp_update_post( array(
        //     'ID' => $comp_id,
        //     'post_content' => implode( "\n", $arr ),
        //     ) );
        // // p(implode( "\n", $arr ));        
       
        // global $messages;
        // $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 103 (' . $type . ')', 'danger' );
        
        // if ( $res ) {
            
        //     global $tree;
        //     global $mif_mr_opop;
            
        //     $tree = array();
        //     $tree = $mif_mr_opop->get_tree();
            
        // }
        
        return $res;
    }



    //
    // save
    //

    public function save( $comp_id, $content )
    {
        $res = false;

        $res = wp_update_post( array(
            'ID' => $comp_id,
            'post_content' => $content,
            ) );
       
        global $messages;
        $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 103', 'danger' );
        
        if ( $res ) {
            
            global $tree;
            global $mif_mr_opop;
            
            $tree = array();
            $tree = $mif_mr_opop->get_tree();
            
        }
        
        return $res;
    }

    





    public function remove( $comp_id, $opop_id, $type = 'lib-competencies' )
    {
        // ####!!!!!

        $res = false;

        $post = get_post( $comp_id );

        if ( $post->post_type == $type ) $res = wp_delete_post($comp_id);
       
        global $messages;
        $messages[] = ( $res ) ? array( 'Данные были удалены ', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 104 (' . $type . ')', 'danger' );
        
        if ( $res ) {
            
            global $tree;
            global $mif_mr_opop;
            
            $tree = array();
            $tree = $mif_mr_opop->get_tree();
            
        }

        return $res;
    }
   


    public function create( $opop_id, $type = 'lib-competencies' )
    {
        // ####!!!!!

        $res = false;

        if ( empty( $_REQUEST['title'] ) ) return;
  

        $res = wp_insert_post( array(
            'post_title'    => sanitize_textarea_field( $_REQUEST['title'] ),
            'post_content'  => ( isset( $_REQUEST['data'] ) ) ? sanitize_textarea_field( $_REQUEST['data'] ) : '',
            'post_type'     => $type,
            'post_status'   => 'publish',
            'post_parent'   => $opop_id,
            ) );


        // global $messages;
        // $messages[] = ( $res ) ? array( 'Данные были удалены', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 105 (' . $type . ')', 'danger' );
        
        if ( $res ) {
            
            global $tree;
            global $mif_mr_opop;

            $tree = array();
            $tree = $mif_mr_opop->get_tree();
            
        }

        return $res;
    }



    
    //
    // Показать всё
    //
    
    public static function get_show_all()
    {
        $out = '';
        
        $out .= '<div class="text-end">';
        $out .= '<small><a href="#" id="roll-down-all">Показать всё</a> | <a href="#" id="roll-up-all">Свернуть</a></small>';
        $out .= '</div>';

        return apply_filters( 'mif_mr_get_show_all', $out );
    }



    
    //
    // Показать cписок компетенций - компетенция, head
    //
    
    public static function get_sub_head( $item )
    {
        if ( ! isset( $item['sub_id'] ) ) $item['sub_id'] = 0;
        if ( ! isset( $item['f'] ) ) $item['f'] = false;
        
        $data_sub_id = ' data-sub="' . $item['sub_id'] . '"';
        $data_part = ( isset( $item['part'] ) ) ? ' data-part="' . $item['part'] . '"' : '';
        $part = ( isset( $item['part'] ) ) ? $item['part'] : $item['sub_id'];

        $up = ' d-none';
        $down = '';
        $onoff = 'off';
        
        if ( isset( $item['coll'] ) && $item['coll'] ) {
            
            $up = '';
            $down = ' d-none';
            $onoff = 'on';
        
        }        
        
        $out = '';
        
        $out .= '<div class="row mb-3 mt-3">';
        
        // Наименование категории

        $out .= '<div class="name col-11 mr-gray p-3 fw-bolder">';
        $out .= $item['name'];
        $out .= '</div>';
        
        // Кнопка edit

        $out .= '<div class="col-1 mr-gray p-3 text-end">';
        if ( $item['f'] ) $out .= '<i class="fas fa-spinner fa-spin d-none"></i> ';
        if ( $item['f'] ) $out .= '<a href="#" class="edit pr-1"' . $data_sub_id . $data_part . '><i class="fa-regular fa-pen-to-square"></i></a>';
        // if ( $item['f'] ) $out .= '<a href="#" class="edit pr-1" data-sub="' . $item['sub_id'] . '"><i class="fa-regular fa-pen-to-square"></i></a>';
        $out .= '<a href="#" class="roll-up' . $up . '"><i class="fa-solid fa-angle-up"></i></a>';
        $out .= '<a href="#" class="roll-down' . $down . '"><i class="fa-solid fa-chevron-down"></i></a>';
        $out .= '</div>';

        $out .= '<input type="hidden" class="coll" data-key="' . $part . '" data-value="' . $onoff . '">';
        // $out .= '<input type="hidden" class="coll-status" name="coll[' . $part . ']" value="' . $onoff . '">';
        // $out .= '<input type="hidden" name="coll[' . $part . ']" value="' . $onoff . '">';
        
        $out .= '</div>';

        return apply_filters( ' mif_mr_get_item_head', $out, $item );
    }

    
    
    
    //
    // Режим edit
    //
    
    public function get_edit( $comp_id )
    {
        $post = get_post( $comp_id );
        
        // p($post->post_title);
        // p($post->post_content);
        // global $wp_query;
        // p( $wp_query);
        
        $out = '';
        
        $out .= '<form method="POST" action="' . $this->get_permalink_comp() . '">';

        $out .= '<textarea name="content" class="edit textarea mt-4 mr-h-48">';
        $out .= $post->post_content;
        $out .= '</textarea>';

        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';
        $out .= '<input type="submit" name="save" value="Сохранить" class="btn btn-primary mt-6 mb-6 mr-3" />';
        $out .= '<input type="button" onclick="location.href=\'' . $this->get_permalink_comp() . '\';"  value="Отмена" class="btn btn-light mt-6 mb-6 mr-3" />';
        $out .= '</form>';        


        return apply_filters( ' mif_mr_companion_lib_core_get_edit', $out, $comp_id );
    }
    




    //
    // Режим edit
    //

    // public function get_sub_edit( $sub_id, $comp_id, $opop_id = NULL )
    public function get_sub_edit( $sub_id, $comp_id, $part = NULL )
    {
        // ####!!!!!
        // p('@');
        $arr = $this->get_sub_arr( $comp_id );
        
        $data_sub_id = ' data-sub="' . $sub_id . '"';
        $data_part = ( ! empty( $part ) ) ? ' data-part="' . $part . '"' : '';

        $out = '';

        if ( isset( $arr[$sub_id] ) || $sub_id == '-1' ) {

            // $out .= '<div class="content-ajax">';
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            
            $out .= mif_mr_functions::get_callout( '<a href="' . '123' . '">Помощь</a>', 'warning' );
            
            $out .= '</div>';
            $out .= '</div>';
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            
            $out .= '<textarea name="content[' . $sub_id . ']" class="edit textarea content" autofocus>';
            
            if ( isset( $arr[$sub_id] ) ) $out .= $arr[$sub_id];
            
            $out .= '</textarea>';
            $out .= '</div>';
            $out .= '</div>';
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';

            // $out .= '<button type="button" class="btn btn-primary mt-4 mb-4 mr-3 save" data-sub="' . $sub_id . '">Сохранить <i class="fas fa-spinner fa-spin d-none"></i></button>';
            // $out .= '<button type="button" class="btn btn-light mt-4 mb-4 mr-3 cancel" data-sub="' . $sub_id . '">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';
            $out .= '<button type="button" class="btn btn-primary mt-4 mb-4 mr-3 save"' . $data_sub_id . $data_part . '>Сохранить <i class="fas fa-spinner fa-spin d-none"></i></button>';
            $out .= '<button type="button" class="btn btn-light mt-4 mb-4 mr-3 cancel"' . $data_sub_id . $data_part . '>Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';

            $out .= '</div>';
            $out .= '</div>';
            // $out .= '</div>';
            
        }

        return apply_filters( 'mif_mr_companion_get_edit', $out, $sub_id, $comp_id, $opop_id );
    }




    //
    // Форму создания 
    //
    
    public function get_lib_create( $arr )
    {
        $out = '';
        
        $out .= '<div class="row mt-5">';
        $out .= '<div class="col">';
        // $out .= '<button type="button" class="btn btn-primary new">Создать дисциплин</button>';
        $out .= '<button type="button" class="btn btn-primary new">' . $arr['button']. '</button>';
        $out .= '</div>';
        $out .= '</div>';
        
        $out .= '<div class="row new" style="display: none;">';
        $out .= '<div class="col mt-5">';
        
        $out .= '<div class="mb-3">';
        // $out .= '<label class="form-label">Название cписка компетенций:</label>';
        $out .= '<label class="form-label">' . $arr['title']. ':</label>';
        $out .= '<input name="title" class="form-control" autofocus>';
        // $out .= '<div class="form-text">Например: ФГОС "Информатика и вычислительная техника", ОПОП "Математика", ...</div>';
        $out .= '<div class="form-text">' . $arr['hint_a']. '</div>';
        $out .= '</div>';
        
        $out .= '<div class="mb-3">';
        // $out .= '<label class="form-label">Данные:</label>';
        $out .= '<label class="form-label">' . $arr['date']. ':</label>';
        $out .= '<textarea name="data" class="form-control" rows="3"></textarea>';
        // $out .= '<div class="form-text">Например: УК-1. Способен использовать философские знания, ... (<a href="' . '123' . '">помощь</a>)</div>';
        $out .= '<div class="form-text">' . $arr['hint_b']. '</div>';
        $out .= '<button type="button" class="btn btn-primary mt-4 mr-3 create">Сохранить <i class="fas fa-spinner fa-spin d-none"></i></button>';
        $out .= '<button type="button" class="btn btn-light border mt-4 mr-3 cancel">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';
        
        $out .= '<input type="hidden" name="opop" value="' . mif_mr_opop_core::get_opop_id() . '">';
        $out .= '<input type="hidden" name="action" value="' . $arr['action'] . '">';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';
        
        $out .= '</div>';
        $out .= '</div>';
       
        return apply_filters( 'mif_mr_show_list_compe_create', $out );
    }
    


    
    
    
    //
    //  id
    //
    
    public function get_comp_id()
    {
        global $wp_query;
        $comp_id = ( isset( $wp_query->query_vars['id'] ) ) ? $wp_query->query_vars['id'] : NULL;  
        return apply_filters( 'mif_mr_get_comp_id', $comp_id );
    }    
 

    
    
    
    //
    // Показать id
    //
    
    public static function show_comp_id()
    {
        global $wp_query;

        $out = '';

        if ( isset( $wp_query->query_vars['id'] ) ) {

            // $out .= '<span class="p-0 pr-3 pl-3 rounded">ID: '; 
            $out .= '<div class="mb-4 mt-0 pb-5 pt-5">'; 
            $out .= '<span class="bg-secondary text-light rounded pl-4 pr-4 p-2">ID: '; 
            $out .= $wp_query->query_vars['id']; 
            $out .= '</span>'; 
            $out .= '</div>'; 
        
        }

        return apply_filters( 'mif_mr_show_comp_id', $out );
    }    
 


    
    
    
    //
    // Показать edit link
    //
    
    public static function get_advanced_edit_link()
    {
        global $wp_query;

        $out = '';

        if ( isset( $wp_query->query_vars['id'] ) ) {

            $out .= '<div class="mb-4">'; 
            // $out .= '<div><a href="' . get_edit_post_link( $wp_query->query_vars['id'] ) . '">Расширенный редактор</a></div>';
            $out .= '<a href="' . get_edit_post_link( $wp_query->query_vars['id'] ) . '">Расширенный редактор</a>';
            $out .= '</div>'; 
        
        }

        return apply_filters( 'mif_mr_get_advanced_edit_link', $out );
    }    
 

    

    //
    // Показать edit link
    //
    
    public static function get_edit_link()
    {
        global $wp_query;

        $out = '';

        if ( isset( $wp_query->query_vars['id'] ) ) {

            // $out .= '<div class="mb-4 mt-0 pb-3 pt-5">'; 
            $out .= '<div class="mb-4">'; 
            // $out .= 'Редактировать';
            $out .= '<div><a href="?edit">Редактировать</a></div>';
            $out .= '</div>'; 
            
        }
        
        return apply_filters( 'mif_mr_get_edit_link', $out );
    }    
    
    
    
    
    //
    // Показать edit link
    //
    
    public static function get_remove_link()
    {
        global $wp_query;
        
        $out = '';
        
        if ( isset( $wp_query->query_vars['id'] ) ) {
            
            // $out .= '<div class="mb-4 mt-6">'; 
            // $out .= '<small><a href="#" class="msg-remove">Удалить</a></small>';
            $out .= '<div class="mb-4">'; 
            $out .= '<a href="#" class="msg-remove">Удалить</a>';
            
            $msg = '<div>Вы уверены?</div>';
            
            $msg .= '<div><label class="form-label mt-4"><input type="checkbox" name="yes" value="on" class="form-check-input"> Да</label></div>';
            $msg .= '<button type="button" class="btn btn-primary mr-3 mb-3 remove">Удалить <i class="fas fa-spinner fa-spin d-none"></i></button>';
            $msg .= '<button type="button" class="btn btn-light border mr-3 cancel">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';
            $out .= '</div>'; 
            
            $out .= '<div class="alert pl-0 pr-0" style="display: none;">' . mif_mr_functions::get_callout( $msg, 'warning' ) . '</div>';
            
        }
        
        return apply_filters( 'mif_mr_get_remove_link', $out );
    }    
    
    
    
    
    //
    // Показать permalink companion
    //
    
    public static function get_permalink_comp()
    {
        global $wp_query;
        // p($wp_query);
        $out = mif_mr_opop_core::get_opop_url() . $wp_query->query['part'] . '/' . $wp_query->query['id'];
        return apply_filters( 'mif_mr_get_permalink_comp', $out );
    }    
    
    // public static function get_permalink_comp( $comp_id )
    // {
    //     // $out = mif_mr_opop_core::get_opop_url() . '/' . $comp_id;
    //     $out = mif_mr_opop_core::get_opop_url() . $comp_id;
    //     return apply_filters( 'mif_mr_get_permalink_comp', $out, $comp_id );
    // }    
 


   
    //
    //  
    //
    
    public function mb_substr( $s, $length )
    {
        // $out = mb_substr( $s, 0, $length - 3 );
        // if ( mb_strlen( $s ) >= $length ) $out .= '...';
        // return apply_filters( 'mif_mr_part_core_mb_substr', $out );
        return mif_mr_functions::mb_substr( $s, $length );
    }
    

}


?>