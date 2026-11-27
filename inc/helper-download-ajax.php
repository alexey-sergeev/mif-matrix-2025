<?php

//
// Загрузка файлов с Matrix
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_helper_download_ajax extends mif_mr_download {  

    
    function __construct()
    {

        parent::__construct();

    }




    function force_download()
    {
        global $tree;

        $item = sanitize_key( $_REQUEST['download'] );

        $out = '';

        
        // docx

        if ( preg_match( '/^docx-/', $item ) ) {
            
            preg_match( '/(^docx-)(.*)/', $item, $a );
            $type = $a[2];

            $dir = $this->get_home_dir( $type );

            
            // Аннотация, Программа, Фонд оценочных средств дисциплины и д.р.

            if ( preg_match( '/^b.*/', $type ) ) {
                
                foreach ( $tree['content']['courses']['clean'] as $course ) {
                    
                    if ( ! empty( $tree['content']['templates']['data']['templates']['index_by_place'][$type][$course['unit']] ) ) {

                        $item = $tree['content']['templates']['data']['templates']['index_by_place'][$type][$course['unit']];
                        $file = $course['name'] . ' (' . mb_strtolower( $item['name'] ) . ').docx';
                        
                        $m = new mif_mr_docx_program( $item['path'] );
                        $m->arr_to_docx( $course, $dir . '/' . $file );

                    }
                    
                }
                
                // $names = apply_filters( 'mif-mr-download-panel-names', mif_mr_functions::get_download_panel_names() );
                // $name_zip = $tree['main']['title'] . ' (' . mb_strtolower( $names[$type] ) . ').zip';

                // $name_zip = $this->make_zip( $dir, $name_zip );

                // $out .= mif_mr_opop_core::get_opop_url() . '?download=zip&type=' . $type;

            }


            // Паспорта и программы формирования компетенций

            if ( $type === 'a' ) {

                foreach ( $tree['content']['competencies']['data'] as $name => $data ) {

                    if ( ! empty( $tree['content']['templates']['data']['templates']['index_by_slug']['passport'] ) ) {

                        $blank = $tree['content']['templates']['data']['templates']['index_by_slug']['passport']['path'];
                        $file = $name . ' (паспорт компетенции).docx';
                        
                        $m = new mif_mr_docx_program( $blank );
                        $m->arr_to_docx_passport( $data, $dir . '/' . $file );

                    }
                



                }

            
            }


            $names = apply_filters( 'mif-mr-download-panel-names', mif_mr_functions::get_download_panel_names() );
            $name_zip = $tree['main']['title'] . ' (' . mb_strtolower( $names[$type] ) . ').zip';

            $name_zip = $this->make_zip( $dir, $name_zip );

            $out .= mif_mr_opop_core::get_opop_url() . '?download=zip&type=' . $type;



        }

        
        // // Паспорта и программы формирования компетенций
        
        // if ( $item == 'passport' ) {

        //     $m = new mif_mr_opop();
        //     // $m = new mif_mr_xlsx_tpl();
        //     $a = $m->make_docx_passport();
            
        //     $this->download( $a['path'], $a['name'] );

        // }
     
        
        
        return $out;
    }




    public function make_zip( $dir, $name_zip = '' )
    {
        if ( empty( $name_zip ) ) $name_zip = array_pop( explode( '/', $dir ) ) . '.zip';

        $files = scandir( $dir );
        $files = array_diff( $files, array( '.', '..' ) );
        foreach ( $files as $k => $f ) if ( ! preg_match( '/.*.docx$/', $f ) ) unset( $files[$k] );

        $zip = new ZipArchive();

        if ( $zip->open( $dir . '/' . $name_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE ) ) {

            foreach ( $files as $f ) $zip->addFile( $dir . '/' . $f, $f );
            $zip->close();

        } else {

            p( 'Ошибка создания архива (27111): ' . $zip->getStatusString() );

        }

        foreach ( $files as $f ) unlink( $dir . '/' . $f );

        return $name_zip;
    }



}

?>