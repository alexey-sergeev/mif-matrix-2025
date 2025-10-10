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
        global $post;
        $opop_id = $post->ID;

        $item = sanitize_key( $_REQUEST['download'] );

        // Список инвайтов в xlsx

        if ( $item == 'opops-list-xlsx' ) {

            // $blank = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';
            $blank = dirname( __FILE__ ) . '/../templates/xlsx/opops-list.xlsx';
            $name = __( 'Список  ОПОП', 'mif-mr' ) . '.xlsx';

            $item = new mif_mr_catalog_shortcode();
            $arr = $item->get_opops_list_xlsx();

            $xlsx = new mif_mr_xlsx_core( $blank );
            $file = $xlsx->get( $arr, 'A1' );
            
            $this->download( $file, $name );

        }


        // // Список инвайтов в docx

        // if ( $item == 'invites-docx' ) {

        //     $blank = dirname( __FILE__ ) . '/../templates/docx/invites.docx';
        //     $name = __( 'Приглашения', 'mif-qm' ) . ' (' . __( 'тест', 'mif-qm' ) . ' ' . $quiz_id . ').docx';

        //     $invites = new mif_qm_invites_core();
        //     $arr = $invites->get_docx_arr( $quiz_id );

        //     $docx = new mif_qm_docx_core( $blank );
        //     $file = $docx->get( $arr );
            
        //     $this->download( $file, $name );

        // }


        // // Результаты теста в xlsx

        // if ( $item == 'results-xlsx' || $item == 'results-archive-xlsx' ) {

        //     $blank = dirname( __FILE__ ) . '/../templates/xlsx/results.xlsx';

        //     if ( $item == 'results-archive-xlsx' ) {

        //         $name = __( 'Архив результатов', 'mif-qm' ) . ' (' . __( 'тест', 'mif-qm' ) . ' ' . $quiz_id . ').xlsx';
        //         $archive = 'achive';

        //     } else {

        //         $name = __( 'Таблица результатов', 'mif-qm' ) . ' (' . __( 'тест', 'mif-qm' ) . ' ' . $quiz_id . ').xlsx';
        //         $archive = false;

        //     }


        //     $results = new mif_qm_process_results();
        //     $arr = $results->get_xlsx_arr( $quiz_id, $archive );

        //     $xlsx = new mif_qm_xlsx_core( $blank );
        //     $file = $xlsx->get( $arr, 'B2' );
            
        //     $this->download( $file, $name );

        // }





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