<?php

//
// Загрузка файлов с Matrix
// 
//


defined( 'ABSPATH' ) || exit;


// require_once dirname( __FILE__ ) . '/../vendor/pclzip/pclzip.lib.php';


// class mif_mr_helper_download_ajax { 
class mif_mr_helper_download_ajax extends mif_mr_download {  

    
    function __construct()
    {

        parent::__construct();

        // $this->home_dir = apply_filters( 'mif-mr-home-dir',  ( (object) wp_upload_dir() )->basedir . '/home/' );
    
    }




    function force_download()
    {
        global $tree;
        // p($tree);

        $item = sanitize_key( $_REQUEST['download'] );

        $out = '';
        // p('$item' );
        // Программа дисциплин, паспорта и программы формирования компетенций и д.р.
        
        // if ( $item == 'course-d-program' ) {
        if ( preg_match( '/^docx-/', $item ) ) {
            
            preg_match( '/(^docx-)(.*)/', $item, $a );
            $type = $a[2];

            if ( preg_match( '/^b.*/', $type ) ) {

                // p('@@');
                $dir = $this->get_home_dir( $type );
                // p($dir);
                
                foreach ( $tree['content']['courses']['clean'] as $course ) {
                    
                    if ( ! empty( $tree['content']['templates']['data']['templates']['index_by_place'][$type][$course['unit']] ) ) {

                        $item = $tree['content']['templates']['data']['templates']['index_by_place'][$type][$course['unit']];
                        // p($item);
                        // p($course);

                        $file = $course['name'] . ' (' . mb_strtolower( $item['name'] ) . ').docx';
                        
                        $m = new mif_mr_docx_program( $item['path'] );
                        $m->arr_to_docx( $course, $dir . '/' . $file );



                    }
                    
                    // $files = scandir( $dir );
                    // p($files);
                    
                    // p($item['name']);
                    
                }
                
                $names = apply_filters( 'mif-mr-download-panel-names', mif_mr_functions::get_download_panel_names() );
                $name_zip = $tree['main']['title'] . ' (' . mb_strtolower( $names[$type] ) . ').zip';

                // p($names[$type]);
                $name_zip = $this->make_zip( $dir, $name_zip );
                // p($dir);


                // $out .= '<a href="?download=zip&type=' . $type . '">Ссылка</a>';
                $out .= mif_mr_opop_core::get_opop_url() . '?download=zip&type=' . $type;

                // p($name_zip);

                // $this->download( $dir . '/' . $name_zip, $name_zip );




                // p($tree['content']['courses']['clean']);

                // $m = new mif_mr_opop();

                // p($tree['content']['templates']['data']);
                
                // $a = $m->make_docx_program( $item );

                // p($a);
                // $this->download( $a['path'], $a['name'] );

            }



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



    // public function get_home_dir( $type )
    // {
    //     if ( ! is_user_logged_in() ) return;
    //     $user = wp_get_current_user();
    //     $dir = $this->home_dir . $user->user_login . '/' . mif_mr_opop_core::get_opop_id() . '/' . $type;
    //     if ( ! is_dir( $dir ) ) $res = mkdir( $dir, 0755, true );
    //     return $dir;
    // }



    public function make_zip( $dir, $name_zip = '' )
    {
        // global $tree;
        // p('@@@');
        if ( empty( $name_zip ) ) $name_zip = array_pop( explode( '/', $dir ) ) . '.zip';
        // p($name_zip);
        // p($tree['main']['title']);

        $files = scandir( $dir );
        $files = array_diff( $files, array( '.', '..' ) );
        foreach ( $files as $k => $f ) if ( ! preg_match( '/.*.docx$/', $f ) ) unset( $files[$k] );
        // p($files);

        // foreach ( $files as &$f ) $f = $dir . '/' . $f;
        // p($files);
        
        // //         p($dir);
        // $zip = new PclZip( $dir . '/' . $name_zip );
        // //         // $zip->create( $dir );
        
        // if ( ! $zip->create( $files, PCLZIP_OPT_REMOVE_PATH, $dir ) ) p( 'Какая-то ошибка (26115): ' . $zip->errorInfo( true ) );
        // p($dir . '/' . $name_zip);
        $zip = new ZipArchive();

        // $res = $zip->open( 'archive.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE );
        // p( extension_loaded('zip') );

        if ( $zip->open( $dir . '/' . $name_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE ) ) {
        // if ( $zip->open( $name_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE ) ) {
        //     // Добавляем файл из локальной файловой системы
        //     $zip->addFile('/path/to/file.txt', 'file.txt'); // Второй параметр — имя внутри архива

            foreach ( $files as $f ) $zip->addFile( $dir . '/' . $f, $f );
            

        //     // Добавляем содержимое строки как файл в архив
        //     $zip->addFromString('readme.txt', 'Hello from PHP!');

        //     // Добавляем пустую директорию
        //     $zip->addEmptyDir('logs');

        //     echo "Архив $filename создан успешно.\n";
            $zip->close(); // Обязательно закрываем архив

        } else {

            p( 'Ошибка создания архива (27111): ' . $zip->getStatusString() );

        }



        foreach ( $files as $f ) unlink( $dir . '/' . $f );

        return $name_zip;
    }





    // private function get_path()
    // {
    //     $file = get_post( $id );
    //     // p($file);
    //     return $this->uploads_dir . $file->post_parent . '/' . $file->post_content;
    // }



    // function file_download( $id )
    // {
    //     // !!!!!!

    //     // p(get_post($id));

    //     $m = new mif_mr_upload();

    //     // p($m->get_path( $id ));
    //     // p($m->get_name( $id ));
    //     // p($m->get_type( $id ));

    //     $this->download( $m->get_path( $id ), $m->get_name( $id ), $m->get_type( $id ), false );

    //     // exit;
    // }




    // //
    // //
    // //

    // static function get_path_tmp( $ext = 'txt', $name = NULL )
    // {
    //     if ( $name === NULL ) $name = time() . rand();

    //     // p($name);

    //     $upload_dir = (object) wp_upload_dir();
    //     $path = trailingslashit( $upload_dir->path ) . 'temp_' . md5( $name ) . '.' . $ext;

    //     return apply_filters( 'mif_mr_get_path_tmp', $path, $ext, $name );
    // }





    // // 
    // // Скачивание файла
    // // 

    // function download( $file, $name = '', $content_type = NULL, $unlink = true ) 
    // {
    //     global $mr;
               
    //     if ( ! $mr->user_can(2) ) {
    //         p( 'Access denied' );    
    //         return;
    //     }

    //     if ( empty( $file ) ) return;

    //     if ( file_exists( $file ) ) {

    //         if ( ob_get_level() ) ob_end_clean();

    //     } else {
          
    //         return;

    //     }

    //     if ( empty( $content_type ) ) {
    
    //         $content_types = array(
    //             'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    //             'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //             'xls' => 'application/vnd.ms-excel',
    //             'pdf' => 'application/pdf',
    //             'zip' => 'application/zip, application/x-compressed-zip',
    //         );
            
    //         $content_type = 'application/octet-stream';
        
    //         // $extension_arr = explode( ".", $file );
    //         // $extension = array_pop( $extension_arr );
        
    //         $extension = mif_mr_functions::get_ext( $file );
    //         if ( isset( $content_types[$extension] ) ) $content_type = $content_types[$extension];

    //     }

    //     if ( $name == '' ) $name = basename( $file );

    //     header('Content-Description: File Transfer');
    //     header('Content-Type: ') . $content_type;
    //     header('Content-Disposition: attachment; filename="' . $name ) . '"';
    //     header('Content-Transfer-Encoding: binary');
    //     header('Expires: 0');
    //     header('Cache-Control: must-revalidate');
    //     header('Pragma: public');
    //     header('Content-Length: ' . filesize( $file ) );

    //     if ( $fd = fopen( $file, 'rb' ) ) {

    //         while ( !feof($fd) ) print fread( $fd, 1024 );
    //         fclose($fd);

    //     }

    //     if ( $unlink ) unlink( $file );

    //     exit;
    // }


    // private $home_dir = '';

}

?>