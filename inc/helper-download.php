<?php

//
// Загрузка файлов с Matrix
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_helper_download extends mif_mr_download {  

    
    function __construct()
    {

        parent::__construct();

        if ( isset( $wp_query->query['part'] ) && $wp_query->query['part'] === 'file' ) $this->file_download( $wp_query->query['id'] );
        if ( ! empty( $_REQUEST['download'] ) ) $this->force_download();

    }




    function force_download()
    {
        // !!!!!!

        $item = sanitize_key( $_REQUEST['download'] );

        // Список  ОПОП

        if ( $item == 'opops-list-xlsx' ) {

            $blank = dirname( __FILE__ ) . '/../templates/xlsx/opops-list.xlsx';
            $name = __( 'Список  ОПОП', 'mif-mr' ) . '.xlsx';

            $item = new mif_mr_catalog_shortcode();
            $arr = $item->get_opops_list_xlsx();

            $xlsx = new mif_mr_xlsx( $blank );
            $file = $xlsx->make_xlsx_form_arr( $arr, 'A1' );
            
            $this->download( $file, $name );

        }
        
        
        // Текст (как есть)
        
        if ( $item == 'text-raw' ) {

            $m = new mif_mr_opop();
            $a = $m->make_text_raw();

            if ( $a['res'] ) {

                $this->download( $a['path'], $a['name'] );

            } else {
                 
                global $messages;
                $messages[] = array( 'Какая-то ошибка. Код ошибки: 1902', 'danger' );
                
            }

        }
        
        
        // Шаблон дисциплины
        
        if ( $item == 'course-x-tpl' ) {

            $m = new mif_mr_opop();
            // $m = new mif_mr_xlsx_tpl();
            $a = $m->make_xlsx_tpl();
            
            $this->download( $a['path'], $a['name'] );

        }
        
        
        // Программа дисциплины
        
        if ( preg_match( '/^course-d-/', $item ) ) {
           
            $m = new mif_mr_opop();
            $a = $m->make_docx_program( $item );

            $this->download( $a['path'], $a['name'] );

        }

        
        // Паспорта и программы формирования компетенций
        
        if ( $item == 'passport' ) {

            $m = new mif_mr_opop();
            $a = $m->make_docx_passport();

            $this->download( $a['path'], $a['name'] );

        }
     
        

        // Шаблон (d-tpl)

        if ( $item == 'd-tpl' ) {

            global $wp_query;
            global $tree;

            $m = new mif_mr_opop();
            
            if ( isset( $wp_query->query_vars['id'] ) && isset( $_REQUEST['slug'] ) ) {

                $slug = sanitize_text_field( $_REQUEST['slug'] );
                $path = $tree['content']['lib-templates']['data'][$wp_query->query_vars['id']]['index'][$slug];
                $name = $slug . '.docx';

                $this->download( $path, $name, NULL, false );
                    
            } else {
                        
                global $messages;
                $messages[] = array( 'Какая-то ошибка. Код ошибки: 2310', 'danger' );

            } 

        }
        

        // Просто файл

        if ( $item == 'file' ) {

            if ( isset( $_REQUEST['id'] ) ) {

                $m = new mif_mr_upload();
                $path = $m->get_path( (int) $_REQUEST['id'] );
                $name = $m->get_name( (int) $_REQUEST['id'] );

                $this->download( $path, $name, NULL, false );
                    
            } else {
                        
                global $messages;
                $messages[] = array( 'Какая-то ошибка. Код ошибки: 2510', 'danger' );

            } 

        }
        

        // Просто файл

        if ( $item == 'zip' ) {

            if ( isset( $_REQUEST['type'] ) ) {

                $dir = $this->get_home_dir( sanitize_key( $_REQUEST['type'] ) );
                $files = scandir( $dir );
                $files = array_diff( $files, array( '.', '..' ) );
                
                foreach ( $files as $k => $f ) if ( ! preg_match( '/.*.zip$/', $f ) ) unset( $files[$k] );
                
                $name = array_pop( $files ); // !!!###
                
                foreach ( $files as $f ) unlink( $dir . '/' . $f ); 

                $this->download( $dir . '/' . $name, $name );
                    
            } else {
                        
                global $messages;
                $messages[] = array( 'Какая-то ошибка. Код ошибки: 27110', 'danger' );

            } 

        }

        

        // Матрица компетенций

        if ( $item == 'matrix' ) {

            global $tree;
            $m = new mif_mr_opop();

            $a = $m->make_xlsx_matrix();
            $this->download( $a['path'], $a['name'] );

        }


    }


}

?>