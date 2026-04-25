<?php

//
// Загрузка файлов с Matrix
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_download { 

    
    function __construct()
    {
        global $wp_query;

        // p($wp_query->query);
        // p($wp_query->query['part']);
        // p($_REQUEST);

        if ( isset( $wp_query->query['part'] ) && $wp_query->query['part'] === 'file' ) $this->file_download( $wp_query->query['id'] );
        if ( ! empty( $_REQUEST['download'] ) ) $this->force_download();

    }



    function force_download()
    {
        // !!!!!!

        // global $post;

        // $opop_id = $post->ID;

        $item = sanitize_key( $_REQUEST['download'] );

        // Список  ОПОП

        if ( $item == 'opops-list-xlsx' ) {

            // $blank = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';
            $blank = dirname( __FILE__ ) . '/../templates/xlsx/opops-list.xlsx';
            $name = __( 'Список  ОПОП', 'mif-mr' ) . '.xlsx';

            $item = new mif_mr_catalog_shortcode();
            $arr = $item->get_opops_list_xlsx();

            $xlsx = new mif_mr_xlsx();
            $file = $xlsx->make_xlsx_form_arr( $arr, $blank, 'A1' );
            
            $this->download( $file, $name );

        }
        
        
        // Текст (как есть)
        
        if ( $item == 'text-raw' ) {

            $m = new mif_mr_opop();
            $a = $m->make_text_raw();

            // p($a);

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
        
        // if ( $item == 'course-d-program' ) {
        if ( preg_match( '/^course-d-/', $item ) ) {

            $m = new mif_mr_opop();
            $a = $m->make_docx_program( $item );
            // p($a);
            $this->download( $a['path'], $a['name'] );

        }

        
        // Паспорта и программы формирования компетенций
        
        if ( $item == 'passport' ) {

            $m = new mif_mr_opop();
            // $m = new mif_mr_xlsx_tpl();
            $a = $m->make_docx_passport();
            
            $this->download( $a['path'], $a['name'] );

        }
        
    }




    function file_download( $id )
    {
        // !!!!!!

        // p(get_post($id));

        $m = new mif_mr_upload();

        // p($m->get_path( $id ));
        // p($m->get_name( $id ));
        // p($m->get_type( $id ));

        $this->download( $m->get_path( $id ), $m->get_name( $id ), $m->get_type( $id ), false );

        // exit;
    }




    //
    //
    //

    static function get_path_tmp( $ext = 'txt', $name = NULL )
    {
        if ( $name === NULL ) $name = time() . rand();

        // p($name);

        $upload_dir = (object) wp_upload_dir();
        $path = trailingslashit( $upload_dir->path ) . 'temp_' . md5( $name ) . '.' . $ext;

        return apply_filters( 'mif_mr_get_path_tmp', $path, $ext, $name );
    }





    // 
    // Скачивание файла
    // 

    function download( $file, $name = '', $content_type = NULL, $unlink = true ) 
    {
        global $mr;
        
        if ( ! $mr->user_can(2) ) {
            p( 'Access denied' );    
            return;
        }

        if ( empty( $file ) ) return;

        if ( file_exists( $file ) ) {

            if ( ob_get_level() ) ob_end_clean();

        } else {
          
            return;

        }

        if ( empty( $content_type ) ) {
    
            $content_types = array(
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'xls' => 'application/vnd.ms-excel',
                'pdf' => 'application/pdf',
                'zip' => 'application/zip, application/x-compressed-zip',
            );
            
            $content_type = 'application/octet-stream';
        
            // $extension_arr = explode( ".", $file );
            // $extension = array_pop( $extension_arr );
        
            $extension = mif_mr_functions::get_ext( $file );
            if ( isset( $content_types[$extension] ) ) $content_type = $content_types[$extension];

        }

        if ( $name == '' ) $name = basename( $file );

        header('Content-Description: File Transfer');
        header('Content-Type: ') . $content_type;
        header('Content-Disposition: attachment; filename="' . $name ) . '"';
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize( $file ) );

        if ( $fd = fopen( $file, 'rb' ) ) {

            while ( !feof($fd) ) print fread( $fd, 1024 );
            fclose($fd);

        }

        if ( $unlink ) unlink( $file );

        exit;
    }


}

?>