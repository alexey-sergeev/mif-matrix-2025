<?php

//
// Загрузка файлов с Matrix
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_upload { 

    
    function __construct()
    {

        // if ( empty( $_REQUEST['download'] ) ) return;

        // $this->force_download();

    }




    // public static function proceeding_upload()
    // {
    //     if ( ! isset( $_REQUEST['submit'] ) ) return;
    //     if ( ! isset( $_FILES['file']['error'] ) ) return;
        
    //     // $out = '';
        
    //     $arr = array();

    //     if ( is_array( $_FILES['file']['error'] ) ) {
    //         foreach ( $_FILES['file']['error'] as $i ) $arr[] = $i;
    //     } else {
    //         $arr[0] = $_FILES['file']['error'];
    //     }

    //     p($arr);
    //     $arr2 = array();

    //     foreach ( $arr as $item ) {

    //         switch ( $item ) {  
    
    //             case 0: $arr2[] = ''; break;  
    //             case 1: $arr2[] = 'Размер файла превышает допустимое значение'; break;  
    //             case 4: $arr2[] =  'Файл не был загружен'; break;  
    //             default: $arr2[] = 'Какая-то ошибка. Код ошибки: 06020'; break;  

    //         }  

    //     }
    //     p($arr2);

    //     $arr2 = array_diff( $arr2, array( '' ) );        
    //     $arr2 = array_unique( $arr2 );        
        
    //     $item2 = implode( '.', $arr2 );

    //     // p($arr2);

    //     $out = ( ! empty( $item2 ) ) ? mif_mr_functions::get_callout( $item2, 'danger' ) : 'file';

    //     return $out;
  
    // }


    // public static function proceeding_upload( $item = NULL )
    // {
    //     if ( ! isset( $_REQUEST['submit'] ) ) return;
        
    //     if ( $item === NULL ) $item = $_FILES['file']['error'];
        
    //     $out = '';

    //     switch ( $item ) {  

    //         case 0: $out .= 'file'; break;  
    //         case 1: $out .= 'Размер файла превышает допустимое значение'; break;  
    //         case 4: $out .= 'Файл не был загружен'; break;  
    //         default: $out .='Какая-то ошибка. Код: 06020'; break;  

    //     }  

    //     return $out;
    // }



    public static function form_upload( $args = array( 'url' => '', 'multiple' => false ) )
    {
        
        $out = '';
   
        $out .= '<div class="bg-light pt-5 pb-5 pl-4 pr-4 border rounded">';
        $out .= '<form method="POST" action="' . mif_mr_opop_core::get_opop_url() . $args['url'] . '" enctype="multipart/form-data">';
        
        if ( isset( $args['text'] ) ) $out .= '<div class="fw-semibold">' . $args['text'] . '</div>';
        if ( isset( $args['title_placeholder'] ) ) $out .= '<div class="mt-4"><input type="text" class="form-control" name="title" placeholder="' . $args['title_placeholder'] . '"></div>';
        
        if ( ! isset( $args['multiple'] ) || $args['multiple'] == false ) {
        
            $out .= '<input type="file" name="file" class="mt-4 p-5 w-100" style="border: dashed 3px #ddd">';
        
        } elseif ( $args['multiple'] == true ) {
        
            $out .= '<input type="file" name="file[]" class="mt-4 p-5 w-100" style="border: dashed 3px #ddd" multiple>';

        }
        
        $out .= '<div class="mt-4">Максимальный размер загружаемого файла: ' . ini_get('upload_max_filesize') . '</div>'; 
        if ( isset( $args['multiple'] ) && $args['multiple'] == true ) $out .= '<div class="mt-0">Количество одновременно загружаемых файлов : ' . ini_get('max_file_uploads') . '</div>'; 
        
        $out .= '<div class="mt-5"><input type="submit" name="submit" value="Загрузить" class="btn btn-primary "></div>';

        // $out .= '<input type="hidden" name="action" value="' . $type . '" />';
        // $out .= '<input type="hidden" name="opop" value="' . $this->get_opop_id() . '" />';
        // $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';     

        $out .= '</form>';
        $out .= '</div>';

        return $out;
  
    }








    //
    // Сохранить файл 
    // 

    public function save( $att = array() )
    {
        // p($_FILES);
        // p($_REQUEST);
    
        if ( ! isset( $_REQUEST['submit'] ) ) return;
        if ( ! isset( $_FILES['file']['error'] ) ) return;
        if ( ! is_array( $_FILES['file']['error'] ) ) return;

        // $out = '';
        $arr = array();

        foreach ( $_FILES['file']['name'] as $key => $item ) {

            if ( empty( $item ) ) continue; 
        
            $arr[$key]['name'] = $item;
            
            $a = explode( '.', $item );
            $ext = array_pop( $a );
            // p($item);
            // p($att);
            // p($ext);

            if ( isset( $att['ext'] ) && ! in_array( $ext, $att['ext'] ) ) {



                $arr[$key]['status'] = 'warning';
                $arr[$key]['messages'] = 'Неправильный формат файла';
                // $arr[$key]['messages'] = 'Неправильный формат файла. Допускается: ' . implode( ', ', $att['ext'] );

                continue;

            } 
                
            $res = $this->proceeding_upload( $_FILES['file']['error'][$key] );

            if ( $res == 'file' ) {

                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/media.php' );

                // $id = media_handle_upload( 'file', mif_mr_opop_core::get_opop_id(), array( 'post_title' => mif_mr_opop_core::get_opop_title() . ' - ' . $item ) );
                
                $file = array(  
                    'name' => $_FILES['file']['name'][$key],
                    'tmp_name' => $_FILES['file']['tmp_name'][$key],
                    'error' => $_FILES['file']['error'][$key],
                    'size' => $_FILES['file']['size'][$key],
                );

                // p($_FILES['file']['tmp_name'][$key]);

                $title =  apply_filters( 'lib-upload-save-title', NULL, $_FILES['file']['tmp_name'][$key] );
                $id = media_handle_sideload( $file, mif_mr_opop_core::get_opop_id(), $title );

                // p($id);
                
                if ( is_wp_error( $id ) ) {
           
                    $arr[$key]['status'] = 'danger';
                    // $arr[$key]['messages'] = '???';
                    $arr[$key]['messages'] = implode( '; ', $id->errors['upload_error'] );
                    // p(is_wp_error($id));
                
                } else {

                    $arr[$key]['status'] = 'success';
                    $arr[$key]['messages'] = 'Сохранено';
                    // $arr[$key]['messages'] = 'Ок!';
                    $arr[$key]['id'] = $id;
                
                }

            } else {

                $arr[$key]['status'] = 'danger';
                $arr[$key]['messages'] = $res;

            }

        }

        // p($arr);
        
        return $arr;
    }






    public static function proceeding_upload( $item = NULL )
    {
        if ( ! isset( $_REQUEST['submit'] ) ) return;
        
        if ( $item === NULL ) $item = $_FILES['file']['error'];
        
        $out = '';

        switch ( $item ) {  

            case 0: $out .= 'file'; break;  
            case 1: $out .= 'Размер файла превышает допустимое значение'; break;  
            case 4: $out .= 'Файл не был загружен'; break;  
            default: $out .='Какая-то ошибка. Код: 06020'; break;  

        }  

        return $out;
    }





    // function force_download()
    // {
    //     global $post;
    //     $opop_id = $post->ID;

    //     $item = sanitize_key( $_REQUEST['download'] );

    //     // Список инвайтов в xlsx

    //     if ( $item == 'opops-list-xlsx' ) {

    //         // $blank = dirname( __FILE__ ) . '/../templates/xlsx/default.xlsx';
    //         $blank = dirname( __FILE__ ) . '/../templates/xlsx/opops-list.xlsx';
    //         $name = __( 'Список  ОПОП', 'mif-mr' ) . '.xlsx';

    //         $item = new mif_mr_catalog_shortcode();
    //         $arr = $item->get_opops_list_xlsx();

    //         $xlsx = new mif_mr_xlsx_core( $blank );
    //         $file = $xlsx->get( $arr, 'A1' );
            
    //         $this->download( $file, $name );

    //     }




    // }



    // // 
    // // Скачивание файла
    // // 

    // function download( $file, $name = '' ) 
    // {
    //     if ( empty( $file ) ) return;

    //     if ( file_exists( $file ) ) {

    //         if ( ob_get_level() ) ob_end_clean();

    //     } else {

    //         return;

    //     }

    //     $content_types = array(
    //         'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    //         'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //         'xls' => 'application/vnd.ms-excel',
    //         'pdf' => 'application/pdf',
    //         'zip' => 'application/zip, application/x-compressed-zip',
    //     );
        
    //     $content_type = 'application/octet-stream';
    
    //     $extension_arr = explode( ".", $file );
    //     $extension = array_pop( $extension_arr );
    
    //     if ( isset( $content_types[$extension] ) ) $content_type = $content_types[$extension];

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

    //     unlink( $file );

    //     exit;
    // }


}

?>