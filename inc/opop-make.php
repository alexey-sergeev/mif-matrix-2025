<?php

//
// Формирует файл, который будет передан пользователю 
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_opop_make extends mif_mr_opop_tree_clean {

    
    function __construct()
    {

        parent::__construct();

    }
    
    
    
    
    //
    // Делает файл для выдачи пользователю (text)
    //
    
    public function make_text_raw()
    {
        global $wp_query;
        // p( $wp_query );
       
        $arr = array( 'name' => '', 'path' => '', 'res' => false );

        if ( isset( $wp_query->query_vars['id'] ) ) {

            $p = get_post($wp_query->query_vars['id']);
            $arr['name'] .= mif_mr_functions::mb_substr( $p->post_title, 50 );
            $arr['name'] .= ' (' . $p->post_type . ', ' . $p->ID . ', ' . mif_mr_functions::mb_substr( $wp_query->query_vars['name'], 20 ) . ')';
            $arr['name'] .= '.txt';
            // p($p);

            // $arr['path'] = '/root/1';
            $arr['path'] = mif_mr_download::get_path_tmp( 'txt' );

            $content = '=== ' . $p->post_title ."\n\n";
            $content .= $p->post_content;

            $arr['res'] = ( $file = fopen( $arr['path'], 'w') ) ? true : false;
            if ( $arr['res'] == true ) $arr['res'] = ( fwrite( $file, $content ) ) ? true : false;
            if ( $arr['res'] == true ) $arr['res'] = ( fclose( $file ) ) ? true : false;

        } else {
            
            p( 'Какая-то ошибка. Код: 26112' );

        }

        // p($arr);
        return $arr;
        
    }
    
    
    
    //
    // Делает шаблон дисциплины для выдачи пользователю (x-tpl)
    //
    
    public function make_xlsx_tpl()
    {
        global $wp_query;
        global $tree;
        // p( $wp_query );
       
        $arr = array( 'name' => '', 'path' => '', 'res' => false );

        if ( isset( $wp_query->query_vars['id'] ) ) {

            $arr2 = ( isset( $tree['content']['lib-courses']['data'][$wp_query->query_vars['id']] ) ) ?
                 $tree['content']['lib-courses']['data'][$wp_query->query_vars['id']] :
                 array();
            
            // p($arr2);

            $blank = apply_filters( 'course-x-tpl', dirname( __FILE__ ) . '/../templates/xlsx/course-x-tpl.xlsx' );

            // $arr['name'] .= '123.xlsx';
            // $name = $arr2['name'];
            // if ( isset( $_REQUEST['name'] ) ) $name = sanitize_text_field( $_REQUEST['name'] );
            
            if ( empty( $arr2['name'] ) && isset( $_REQUEST['name'] ) ) $arr2['name'] = sanitize_text_field( $_REQUEST['name'] );
            if ( empty( $arr2['data'] ) && isset( $_REQUEST['name'] ) ) $arr2['data'] = array('data'=>array()); // !!!!

            // $arr['name'] .= $name . ' (шаблон дисциплины).xlsx';
            $arr['name'] .= $arr2['name'] . ' (шаблон дисциплины).xlsx';

            $m = new mif_mr_xlsx_tpl( $blank );
            // $m->arr_to_xlsx( $arr2 );
            // $m->сorrection_height();
            // $arr['path'] = $m->make_xlsx();
            $arr['path'] = $m->arr_to_xlsx( $arr2 );
            // $m->сorrection_height();
            // $m->make_xlsx();

        } else {
            
            p( 'Какая-то ошибка. Код: 26111' );


        }

        // p($arr);
        return $arr;
        
    }
    
    
    
    //
    // Делает программу дисциплины для выдачи пользователю (d-program)
    //
    
    public function make_docx_program( $type )
    {
        global $wp_query;
        global $tree;
        
        preg_match( '/(^course-d-)(.*)/', $type, $a );
        $type = $a[2];
       
        $arr = array( 'name' => '', 'path' => '', 'res' => false );
        
        // if ( empty( $tree['templates'][$type] ) ) return $arr;
        if ( empty( $tree['content']['templates']['data']['templates']['index_by_slug'][$type]['path'] ) ) return $arr;

        $course_id = NULL;
        $key = NULL;

        if ( isset( $_REQUEST['course'] ) ) 
            if ( is_numeric( $_REQUEST['course'] ) ) {
                
                if ( ! empty( $tree['content']['courses']['index'][(int) $_REQUEST['course']] ) )
                    $key = $tree['content']['courses']['index'][(int) $_REQUEST['course']];
            } else {
                if ( ! empty( $tree['content']['courses']['complete'][sanitize_text_field($_REQUEST['course'])] ) )
                    $key = sanitize_text_field( $_REQUEST['course'] );
            }
            
        if ( isset( $wp_query->query_vars['id'] ) ) 
            if ( ! empty( $tree['content']['courses']['index'][$wp_query->query_vars['id']] ) )
                $key = $tree['content']['courses']['index'][$wp_query->query_vars['id']];

        if ( ! empty( $key ) ) {

            $arr2 = ( isset( $tree['content']['courses']['clean'][$key] ) ) ?
                 $tree['content']['courses']['clean'][$key] :
                 array();

            $blank = $tree['content']['templates']['data']['templates']['index_by_slug'][$type]['path'];
            
            $text = ( ! empty( $tree['content']['templates']['data']['templates']['index_by_slug'][$type]['name'] ) ) ?
                    ' (' . mb_strtolower( $tree['content']['templates']['data']['templates']['index_by_slug'][$type]['name'] ) . ')' :
                    '';

            $m = new mif_mr_docx_program( $blank );

            $arr['name'] = $arr2['name'] . $text . '.docx';
            $arr['path'] = $m->arr_to_docx( $arr2 );

        } else {
            
            p( 'Какая-то ошибка. Код: 26110' );

        }

        // p($arr);
        return $arr;
        // return ;
        
    }



    //
    // Делает паспорт и программу формирования компетенций
    //
    
    public function make_docx_passport()
    {
        global $tree;
        
        $arr = array( 'name' => '', 'path' => '', 'res' => false );
        
        // if ( empty( $tree['templates']['passport'] ) ) return $arr;
        if ( empty( $tree['content']['templates']['data']['templates']['index']['passport'] ) ) return $arr;
        if ( empty( $tree['content']['competencies']['data'][$_REQUEST['comp']] ) ) return $arr;
       
        $arr2 = $tree['content']['competencies']['data'][$_REQUEST['comp']];

        // $blank = $tree['templates']['passport']['data']['path'];
        $blank = $tree['content']['templates']['data']['templates']['index']['passport'];
        
        $m = new mif_mr_docx_program( $blank );

        $arr['name'] = sanitize_text_field( $_REQUEST['comp'] ) . ' (паспорт компетенции).docx';
        $arr['path'] = $m->arr_to_docx_passport( $arr2 );

        return $arr;
        
    }
    


    //
    // Делает матрицу компетенций
    //
    
    public function make_xlsx_matrix()
    {
        // global $tree;
        // p($tree);

        $arr = array( 'name' => '', 'path' => '', 'res' => false );
        
        // $arr2 = $tree['content']['matrix'];

        // $blank = apply_filters( 'course-x-tpl', dirname( __FILE__ ) . '/../templates/xlsx/course-x-tpl.xlsx' );
        // $blank = '/../templates/xlsx/default.xlsx';
        $blank = dirname( __FILE__ ) . '/../templates/xlsx/matrix.xlsx';

        $m = new mif_mr_xlsx_matrix( $blank );

        $arr['name'] = 'Матрица компетенций.xlsx';
        $arr['path'] = $m->arr_to_xlsx();

        return $arr;
        
    }
    

    

}

?>