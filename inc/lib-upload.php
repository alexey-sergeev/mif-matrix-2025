<?php

//
// Загрузка файлов с Matrix
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_upload { 

    
    function __construct()
    {

        // $this->uploads_dir = apply_filters( 'lib-upload-uploads-dir',  ( (object) wp_upload_dir() )->basedir . '/opop_data/' . mif_mr_opop_core::get_opop_id() . '/');
        $this->uploads_dir = apply_filters( 'lib-upload-uploads-dir',  ( (object) wp_upload_dir() )->basedir . '/opop_data/' . mif_mr_opop_core::get_opop_id() . '/' );
        // p($this->uploads_dir);

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
        
        if ( empty( $args['no_max_filesize'] ) ) $out .= '<div class="mt-4">Максимальный размер загружаемого файла: ' . ini_get('upload_max_filesize') . '</div>'; 
        if ( isset( $args['multiple'] ) && $args['multiple'] == true ) $out .= '<div class="mt-0">Количество одновременно загружаемых файлов : ' . ini_get('max_file_uploads') . '</div>'; 
        
        if ( isset( $args['do'] ) ) $out .= '<input type="hidden" name="do" value="' . $args['do'] . '">';
        if ( isset( $args['attid'] ) ) $out .= '<input type="hidden" name="attid" value="' . $args['attid'] . '">';
        if ( isset( $args['type'] ) ) $out .= '<input type="hidden" name="type" value="' . $args['type'] . '">';
        
        $submit = ( isset( $args['submit'] ) ) ? $args['submit'] : 'Загрузить';

        $out .= '<div class="mt-5">';
        $out .= '<input type="submit" name="submit" value="' . $submit . '" class="btn btn-primary mr-3">';
        // $out .= '<input type="button" value="Отмена" class="btn btn-light mt-6 mb-6 mr-3">';
        if ( isset( $args['cancel'] ) ) $out .= '<input type="button" value="Отмена" class="cancel btn btn-light border">';
        $out .= '</div>';

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
    
        // !!!!!!

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

                // require_once( ABSPATH . 'wp-admin/includes/image.php' );
                // require_once( ABSPATH . 'wp-admin/includes/file.php' );
                // require_once( ABSPATH . 'wp-admin/includes/media.php' );

                // // $id = media_handle_upload( 'file', mif_mr_opop_core::get_opop_id(), array( 'post_title' => mif_mr_opop_core::get_opop_title() . ' - ' . $item ) );
                
                // $file = array(  
                //     'name' => $_FILES['file']['name'][$key],
                //     'tmp_name' => $_FILES['file']['tmp_name'][$key],
                //     'error' => $_FILES['file']['error'][$key],
                //     'size' => $_FILES['file']['size'][$key],
                // );

                // // p($_FILES['file']['tmp_name'][$key]);

                // $title = apply_filters( 'lib-upload-save-title', NULL, $_FILES['file']['tmp_name'][$key], $_FILES['file']['name'][$key] );
                // $id = media_handle_sideload( $file, mif_mr_opop_core::get_opop_id(), $title );
                
                // $file = array(  
                //     'name' => $_FILES['file']['name'][$key],
                //     'tmp_name' => $_FILES['file']['tmp_name'][$key],
                //     'error' => $_FILES['file']['error'][$key],
                //     'size' => $_FILES['file']['size'][$key],
                // );

                $title = apply_filters( 'lib-upload-save-title', NULL, $_FILES['file']['tmp_name'][$key], $_FILES['file']['name'][$key] );
                // $id = media_handle_sideload( $file, mif_mr_opop_core::get_opop_id(), $title );

                $type = ( isset( $_REQUEST['type'] ) ) ? sanitize_key( $_REQUEST['type'] ) : '';
                
                $uploads_dir = $this->uploads_dir;
                if ( ! empty( $type ) ) $uploads_dir .= $type . '/';

                $res3 = true;
                if ( ! is_dir( $uploads_dir ) ) $res3 = mkdir( $uploads_dir, 0755, true );

                if ( $res3 ) {

                    $file_name = wp_unique_filename( $uploads_dir, $_FILES['file']['name'][$key] );
                    $res2 = move_uploaded_file( $_FILES['file']['tmp_name'][$key], $uploads_dir . $file_name );
                    
                    if ( $res2 ) {
                        
                        $file_path = ( ! empty( $type ) ) ? $type . '/' . $file_name : $file_name;

                        $file_id = wp_insert_post( array(
                            'post_title' => $title,
                            'post_content' => $file_path,
                            'post_type' => 'file',
                            'post_status' => 'publish',
                            'tax_input' => array( 'file_type' => $type ),
                            'post_mime_type' => $_FILES['file']['type'][$key],
                            'post_parent' => mif_mr_opop_core::get_opop_id(),
                        ) );
        
                        // p(get_post($file_id));
                        
                        if ( ! is_wp_error( $file_id ) ) {

                            $arr[$key]['status'] = 'success';
                            $arr[$key]['messages'] = 'Сохранено';
                            $arr[$key]['id'] = $file_id;

                        } else {
                        
                            $arr[$key]['status'] = 'danger';
                            $arr[$key]['messages'] = $file_id->get_error_message();
                        
                        }

                    } else {

                        $arr[$key]['status'] = 'danger';
                        $arr[$key]['messages'] = 'Какая-то ошибка. Код: 10040';

                    }
                
                } else {

                    $arr[$key]['status'] = 'danger';
                    $arr[$key]['messages'] = 'Какая-то ошибка. Код: 10041';

                }

            } else {

                $arr[$key]['status'] = 'danger';
                $arr[$key]['messages'] = $res;

            }

        }

        // p($arr);
        
        return $arr;
    }




    //
    // Обновить файл 
    // 

    public function reload()
    {
        // p($att);
        // p($_FILES);
    //     // p($_REQUEST);
    
        // !!!!!!

        if ( ! isset( $_REQUEST['submit'] ) ) return;
        if ( ! ( isset( $_REQUEST['do'] ) && $_REQUEST['do'] == 'reload' ) ) return;
        if ( ! isset( $_FILES['file']['error'] ) ) return;

        $id = (int) $_REQUEST['attid'];

        if ( mif_mr_functions::get_ext( $_FILES['file']['name'] ) != 
                mif_mr_functions::get_ext( $this->get_path( $id ) ) ) return false;

        $res = move_uploaded_file( $_FILES['file']['tmp_name'], $this->get_path( $id ) );


        // $res = $this->proceeding_upload( $_FILES['file']['error'] );

        // if ( $res == 'file' ) {

        //     $file = array(  
        //         'name' => $_FILES['file']['name'],
        //         'tmp_name' => $_FILES['file']['tmp_name'],
        //         'error' => $_FILES['file']['error'],
        //         'size' => $_FILES['file']['size'],
        //     );
        
        //     $att_id = (int) $_REQUEST['attid'];
        //     $res2 = move_uploaded_file( $_FILES['file']['tmp_name'], get_attached_file( $att_id ) );

        // }

        return $res;
    }



    //
    //  Удалить файл 
    // 

    public function remove( $id )
    {
        // !!!!!!

        unlink( $this->get_path( $id ) );
        $res = wp_delete_post( $id, true );
        return ( empty( $res ) ) ? false : true;
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
            default: $out .= 'Какая-то ошибка. Код: 06020'; break;  

        }  

        return $out;
    }



    public function get_path( $id )
    {
        $file = get_post( $id );
        return $this->uploads_dir . $file->post_content;
    }


    public function get_name( $id )
    {
        $file = get_post( $id );
        return $file->post_title . '.' . mif_mr_functions::get_ext( $file->post_content );
    }


    public function get_type( $id )
    {
        $file = get_post( $id );
        return $file->post_mime_type;
    }







    private $uploads_dir = '';







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