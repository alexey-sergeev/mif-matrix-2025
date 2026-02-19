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
            

            $ext = mif_mr_functions::get_ext( $item );

            // $a = explode( '.', $item );
            // $ext = array_pop( $a );

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

                $title = apply_filters( 'lib-upload-save-title', NULL, $_FILES['file']['tmp_name'][$key] );
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










    // //
    // // Сохранить файл 
    // // 

    // public function remove_all( $att = array() )
    // {
    //     $res = true;

    //     $args = array(
    //         'numberposts' => -1,
    //         'post_parent' => mif_mr_opop_core::get_opop_id(), 
    //         'post_type' => 'attachment',
    //         'post_status' => 'inherit',
    //     );
        
    //     $arr = get_posts( $args );



    
    //     return $res;
    // }







}

?>