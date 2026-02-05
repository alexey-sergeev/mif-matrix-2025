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




    public static function proceeding_upload()
    {
        
        $out = '';
   
        if ( isset( $_REQUEST['submit'] ) ) {

            switch ( $_FILES['file']['error'] ) {  
    
                case 0: 
                    $out = 'file';
                    break;  

                case 1:                                  
                    $out .= mif_mr_functions::get_callout( 'Размер файла превышает допустимое значение', 'danger' );
                    break;  
    
                case 4: 
                    $out .= mif_mr_functions::get_callout( 'Файл не был загружен', 'danger' );
                    break;  
                    
                default: 
                    $out .= mif_mr_functions::get_callout( 'Какая-то ошибка', 'danger' );
                    break;  

            }  

        }

        return $out;
  
    }



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