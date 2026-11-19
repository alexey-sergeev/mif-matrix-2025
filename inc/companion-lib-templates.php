<?php

//
// Библиотека справочников
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_lib_templates extends mif_mr_companion_core {
    
    
    function __construct()
    {
        parent::__construct();
        
    }
    
    
    
    
    //
    // 
    //
    
    public function get_all_arr( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        $arr = array();
        $list = $this->get_list_companions( 'lib-templates', $opop_id );
        
        foreach ( $list as $item ) {

            $arr2 = $this->get_arr( $item['id'] );
            $arr[$arr2['comp_id']] = $arr2;

        }

        return apply_filters( 'mif_mr_get_all_arr_templates', $arr );

    }



    //
    // Возвращает массив из текста (post)
    //
    
    public function get_arr( $templates_id )
    {
        $arr = array();
        
        $post = get_post( $templates_id );
        // p($post);
        $arr2 = explode( "\n", $post->post_content );
        $arr2 = array_map( 'strim', $arr2 );
        
        $p = new parser();
        $arr3 = array();

        $is_numeric = false;
        $is_string = false;

        foreach ( $arr2 as $item ) {
            
            if ( empty( $item ) ) continue;
            
            $data = $p->parse_name( $item );

            $arr3[] = array( 
                'name' => $data['name'],
                'att' => ( isset( $data['att'] ) ) ? $data['att'] : '',
            );

        }

        $arr['comp_id'] = $templates_id;
        $arr['from_id'] = $post->post_parent;
        $arr['name'] = $post->post_title;
        $arr['data'] = $arr3;
        
        $arr = $this->attach_files_to_templates( $arr );

        return apply_filters( 'mif_mr_get_templates_arr', $arr, $templates_id );
    }        
    
  


    
    // 
    // Зацепить файлы 
    // 
    
    private function attach_files_to_templates( $arr )
    {
        // p($arr);
        $files_arr = $this->get_files_arr( $arr['comp_id'], $arr['from_id'] );

        // p($files_arr);

        $index = array();

        foreach ( $arr['data'] as $key => $item ) {
            
            if ( empty( $item['att'][0] ) ) continue;
            $index[$item['att'][0]] = $key; 
        
        }

        $not_attached = array();

        foreach ( $files_arr as $key => $item ) {
            
            if ( ! isset( $index[$key] ) ) {
                
                $not_attached[] = array(
                    'id' => $files_arr[$key]['id'],
                    'slug' => $files_arr[$key]['name'],
                    'path' => $files_arr[$key]['path'],
                );

                continue;
            
            }

            $arr['data'][$index[$key]]['file']['id'] = $files_arr[$key]['id'];
            $arr['data'][$index[$key]]['file']['path'] = $files_arr[$key]['path'];
            
        }
        
        $arr['not_attached'] = $not_attached;

        // 
        // status:
        //      complete
        //      incompletely
        //      empty
        //      missing
        // 

        if ( ! empty( $arr['data'] ) ) {

            $is_complete = true;
            $is_incompletely = false;
            $is_empty = true;

            foreach ( $arr['data'] as $item ) if ( ! isset( $item['file'] ) ) $is_complete = false;
            foreach ( $arr['data'] as $item ) if ( isset( $item['file'] ) ) $is_empty = false;
            if ( ! $is_complete && ! $is_empty ) $is_incompletely = true;

            if ( $is_complete ) $arr['status'] = 'complete';
            if ( $is_incompletely ) $arr['status'] = 'incompletely';
            if ( $is_empty ) $arr['status'] = 'empty';

        } else {
            
            $arr['status'] = 'missing';

        }

        foreach ( $arr['data'] as $key => $item ) if ( ! empty( $item['file']['path'] ) ) $arr['index'][$item['att'][0]] = $item['file']['path']; 

        return apply_filters( 'mif_mr_attach_files_to_templates', $arr );
    }
    
    




    private function get_files_arr( $templates_id, $from_id )
    {

        $post = get_posts( array(
            'post_type' => 'file',
            'post_status' => 'publish',
            // 'post_parent' => mif_mr_opop_core::get_opop_id(),
            'post_parent' => $from_id,
            'posts_per_page' => -1,
            'order' => 'ASC',
            ) );
        
        $m = new mif_mr_upload();
        
        $arr = array();

        foreach ( $post as $item ) {

            if ( ! preg_match( '/^templates\/' . $templates_id . '\//', $item->post_content ) ) continue;

            $arr[$item->post_title] = array( 
                'id' => $item->ID,
                'name' => $item->post_title,
                'path' => $m->get_path( $item->ID ),
            );

        }    

        return apply_filters( 'mif_mr_get_files_arr', $arr, $templates_id );
    }


    

    //
    // Разбор формы
    //

    protected function upload( $templates_id )
    {
        $out = '';
    
        $m = new mif_mr_upload();
        $res = $m->save( array( 'ext' => array( 'docx' ) ) ); 

        foreach ( (array) $res as $i ) {
            
            if ( $i['status'] == 'success' ) {

                $post = get_posts( array(
                    'post_type' => 'file',
                    'post_status' => 'publish',
                    'post_parent' => mif_mr_opop_core::get_opop_id(),
                    'posts_per_page' => -1,
                    'order' => 'ASC',
                    ) );
                
                foreach ( $post as $item ) {

                    if ( $item->post_title != $i['title'] ) continue;
                    
                    if ( ! preg_match( '/^templates\/' . $templates_id . '\//', $item->post_content ) ) continue;

                    $m = new mif_mr_upload();
                    if ( $i['id'] != $item->ID ) $m->remove( $item->ID );

                }    

            }
        
            if ( ! ( isset( $_REQUEST['quiet_mode'] ) && $_REQUEST['quiet_mode'] == 'yes' ) ) {

                $out .= mif_mr_functions::get_callout( 
                    $i['name'] . ' — <span class="fw-semibold">' . $i['messages'] . '</span>', 
                    $i['status'] ); 

            }

        }        

        return apply_filters( 'mif_mr_upload', $out );

    }



    public function remove( $templates_id, $opop_id, $type = 'lib-competencies' )
    {
        global $tree;
        
        
        // p( $tree['content']['lib-templates']['data'][$templates_id] );
        
        $arr = array();
        
        foreach ( $tree['content']['lib-templates']['data'][$templates_id]['data'] as $item ) 
            if ( $item['file']['id'] ) $arr[] = $item['file']['id'];
        
        foreach ( $tree['content']['lib-templates']['data'][$templates_id]['not_attached'] as $item ) 
            if ( $item['id'] ) $arr[] = $item['id'];
        

        $m = new mif_mr_upload();
        
        foreach ( $arr as $i ) $m->remove( $i );
        
        parent::remove( $templates_id, $opop_id, $type );
       
        // p($arr);



        //     {

        //          p( $item['file']['id'] );
        //     p($item);

        // }

        // p($comp_id);


        // // ####!!!!!
        // global $mr;
        // if ( ! $mr->user_can(3) ) return;

        // $res = false;

        // $post = get_post( $comp_id );

        // if ( $post->post_type == $type ) $res = wp_delete_post($comp_id);
       
        // global $messages;
        // $messages[] = ( $res ) ? array( 'Данные были удалены ', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 104 (' . $type . ')', 'danger' );
        
        // if ( $res ) {
            
        //     global $tree;
        //     global $mif_mr_opop;
            
        //     $tree = array();
        //     $tree = $mif_mr_opop->get_tree();
            
        // }

        // p('@@');
        return $res;
    }
   


}


?>