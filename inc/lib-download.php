<?php

//
// Загрузка файлов с Matrix
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_download { 

    
    function __construct()
    {

        if ( empty( $_REQUEST['download'] ) ) return;

        $this->force_download();

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
            $file = $xlsx->set( $arr, $blank, 'A1' );
            
            $this->download( $file, $name );

        }
        
        
        // Текст как есть
        
        if ( $item == 'text-raw' ) {

            // $upload_dir = (object) wp_upload_dir();
            // $file = trailingslashit( $upload_dir->path ) . md5( 'serialize( $arr ) . $cell' ) . '.xlsx';
            
            // p($file);
            
            // $path = $this->get_path_tmp( 'txt' );
            
            // p($post);
            
            $m = new mif_mr_opop();
            $a = $m->make_text_raw();

            // p($a);

            if ( $a['res'] ) {

                $this->download( $a['path'], $a['name'] );

            } else {
                 
                global $messages;
                $messages[] = array( 'Какая-то ошибка. Код ошибки: 1902', 'danger' );
                
            }

            // p($path);

            // // $blank = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';
            // $blank = dirname( __FILE__ ) . '/../templates/xlsx/opops-list.xlsx';
            // $name = __( 'Список  ОПОП', 'mif-mr' ) . '.xlsx';

            // $item = new mif_mr_catalog_shortcode();
            // $arr = $item->get_opops_list_xlsx();

            // $xlsx = new mif_mr_xlsx();
            // $file = $xlsx->set( $arr, $blank, 'A1' );
            
            // $this->download( $file, $name );

        }




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

    function download( $file, $name = '' ) 
    {
        if ( empty( $file ) ) return;

        if ( file_exists( $file ) ) {

            if ( ob_get_level() ) ob_end_clean();

        } else {

            return;

        }

        $content_types = array(
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xls' => 'application/vnd.ms-excel',
            'pdf' => 'application/pdf',
            'zip' => 'application/zip, application/x-compressed-zip',
        );
        
        $content_type = 'application/octet-stream';
    
        $extension_arr = explode( ".", $file );
        $extension = array_pop( $extension_arr );
    
        if ( isset( $content_types[$extension] ) ) $content_type = $content_types[$extension];

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

        unlink( $file );

        exit;
    }


}

?>