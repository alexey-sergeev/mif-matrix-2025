<?php

//
// Загрузка файлов с Matrix
// 
//


defined( 'ABSPATH' ) || exit;



class mif_mr_upload { 

    
    function __construct()
    {

        $this->uploads_dir = apply_filters( 'lib-upload-uploads-dir',  ( (object) wp_upload_dir() )->basedir . '/opop_data/' );

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
        if ( isset( $args['slug'] ) ) $out .= '<input type="hidden" name="slug" value="' . $args['slug'] . '">';
        
        $submit = ( isset( $args['submit'] ) ) ? $args['submit'] : 'Загрузить';

        $out .= '<div class="mt-5">';
        $out .= '<input type="submit" name="submit" value="' . $submit . '" class="submit btn btn-primary mr-3">';
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

        // p('@');
        
        if ( ! isset( $_REQUEST['submit'] ) ) return;
        if ( ! isset( $_FILES['file']['error'] ) ) return;
        // if ( ! is_array( $_FILES['file']['error'] ) ) return;
        
        if ( is_array( $_FILES['file']['error'] ) ) {

            $files = $_FILES;

        } else {
            
            $files['file'] = array(

                'name' => array( $_FILES['file']['name'] ),
                'full_path' => array( $_FILES['file']['full_path'] ),
                'type' => array( $_FILES['file']['type'] ),
                'tmp_name' => array( $_FILES['file']['tmp_name'] ),
                'error' => array( $_FILES['file']['error'] ),
                'size' => array( $_FILES['file']['size'] ),

            );
        
        }

        // p($files);
        // ###!!!

        global $mr;
        if ( ! $mr->user_can(3) ) return;

        // p('@');

        // $out = '';
        $arr = array();

        $is_save = false;

        foreach ( $files['file']['name'] as $key => $item ) {

            if ( empty( $item ) ) continue; 

            $ext = mif_mr_functions::get_ext( $item );
            if ( ! empty( $_REQUEST['slug'] ) ) $item = sanitize_key( $_REQUEST['slug'] ) . '.' . $ext;

            $arr[$key]['name'] = $item;

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
                
            $res = $this->proceeding_upload( $files['file']['error'][$key] );

            if ( $res == 'file' ) {

                $title = apply_filters( 'lib-upload-save-title', NULL, $files['file']['tmp_name'][$key], $files['file']['name'][$key] );
                if ( ! empty( $_REQUEST['slug'] ) ) $title = sanitize_key( $_REQUEST['slug'] );
                $arr[$key]['title'] = $title;

                $type = ( isset( $_REQUEST['type'] ) ) ? sanitize_key( $_REQUEST['type'] ) : '';
                
                $uploads_dir = $this->uploads_dir. mif_mr_opop_core::get_opop_id() . '/';
                if ( ! empty( $type ) ) $uploads_dir .= $type . '/';

                global $wp_query;
                $sub_dir = ( ! empty( $wp_query->query_vars['id'] ) ) ? $wp_query->query_vars['id'] . '/' : '';
                $sub_dir = ( ! empty( $_REQUEST['attid'] ) ) ? sanitize_key( $_REQUEST['attid'] ) . '/' : '';
                $uploads_dir .= $sub_dir;

                $res3 = true;
                if ( ! is_dir( $uploads_dir ) ) $res3 = mkdir( $uploads_dir, 0755, true );

                if ( $res3 ) {

                    // $file_name = wp_unique_filename( $uploads_dir, $files['file']['name'][$key] );
                    $file_name = wp_unique_filename( $uploads_dir, $item );
                    $res2 = move_uploaded_file( $files['file']['tmp_name'][$key], $uploads_dir . $file_name );
                    
                    // p($_FILES['file']['tmp_name'][$key]);
                    // p($uploads_dir . $file_name);

                    if ( $res2 ) {
                        
                        $file_path = ( ! empty( $type ) ) ? $type . '/' . $sub_dir . $file_name : $file_name;

                        $file_id = wp_insert_post( array(
                            'post_title' => $title,
                            'post_content' => $file_path,
                            'post_type' => 'file',
                            'post_status' => 'publish',
                            'tax_input' => array( 'file_type' => $type ),
                            'post_mime_type' => $files['file']['type'][$key],
                            'post_parent' => mif_mr_opop_core::get_opop_id(),
                            // 'post_parent' => $parent,
                        ) );
        
                        // p(get_post($file_id));
                        
                        if ( ! is_wp_error( $file_id ) ) {

                            $arr[$key]['status'] = 'success';
                            $arr[$key]['messages'] = 'Сохранено';
                            $arr[$key]['id'] = $file_id;

                            $is_save = true;

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


        if ( $is_save ) {
            
            global $tree;
            global $mif_mr_opop;
            
            $tree = array();
            $tree = $mif_mr_opop->get_tree();
            
        }


        // p($arr);
        
        return $arr;
    }




    //
    // Обновить файл 
    // 

    public function reload()
    {
        // !!!!!!

        if ( ! isset( $_REQUEST['submit'] ) ) return;
        if ( ! ( isset( $_REQUEST['do'] ) && $_REQUEST['do'] == 'reload' ) ) return;
        if ( ! isset( $_FILES['file']['error'] ) ) return;

        $id = (int) $_REQUEST['attid'];

        if ( mif_mr_functions::get_ext( $_FILES['file']['name'] ) != 
                mif_mr_functions::get_ext( $this->get_path( $id ) ) ) return false;

        $res = move_uploaded_file( $_FILES['file']['tmp_name'], $this->get_path( $id ) );

        return $res;
    }



    //
    //  Удалить файл 
    // 

    public function remove( $id )
    {
        // ###!!!!!!
        global $mr;
        if ( ! $mr->user_can(3) ) return;
        
        unlink( $this->get_path( $id ) );
        $res = wp_delete_post( $id, true );
        
        $res2 = ( empty( $res ) ) ? false : true;
        
        if ( $res2 ) {
            
            global $tree;
            global $mif_mr_opop;
            
            $tree = array();
            $tree = $mif_mr_opop->get_tree();
            
        }

        return $res2;
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
        // p($file);
        return $this->uploads_dir . $file->post_parent . '/' . $file->post_content;
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

}

?>