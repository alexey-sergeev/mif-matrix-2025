<?php

//
// Учебный план
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_tools_curriculum extends mif_mr_tools_core {
    
    function __construct()
    {

        parent::__construct();
        
        // add_filter( 'mif-mr-tbody-col', array( $this, 'filter_tbody_col'), 10, 4 );
        // add_filter( 'mif-mr-tbody-class-tr', array( $this, 'filter_tbody_class_tr'), 10, 2 );
        // add_filter( 'mif-mr-tbody-colspan', array( $this, 'filter_tbody_colspan'), 10 );
        // add_filter( 'mif-mr-thead-row', array( $this, 'filter_thead_row'), 10, 2 );

        // $this->save( 'curriculum' );

        // add_filter( 'upload_mimes', array( $this, 'add_type' ), 10 );
        
        
    }
        


        // public function add_type( $mime_types ){
        //     p('@');
        //     return $mime_types;
        // }

    

    // 
    // Показывает  
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-tools-curriculum.php' ) ) {
           
            load_template( $template, false );
            
        } else {
                
            load_template( dirname( __FILE__ ) . '/../templates/mr-tools-curriculum.php', false );

        }
    }
    




    // 
    //   
    // 

    public function get_tools_curriculum()
    {

        global $wp_query;
        if ( isset( $wp_query->query_vars['id'] ) ) $att_id = $wp_query->query_vars['id'];
                
        $out = '';

        // Разбор формы

        $res = mif_mr_upload::proceeding_upload();

        if ( $res === 'file' ) { 

            if ( $att_id = $this->save() ) {
                $out .= mif_mr_functions::get_callout( 'Сохранено', 'success' );
            } else {
                $out .= mif_mr_functions::get_callout( 'План не найден', 'danger' );
            }

        } else {
            
            $out .= $res;

        }

        // Показать форму

        $out .= mif_mr_upload::form_upload( array( 
                            'text' => 'Загрузите файл учебного плана в формате XML', 
                            'title_placeholder' => 'Название плана', 
                            'url' => 'tools-curriculum' 
                        ) );
        

        // Показать список файлов плана
        
        $out .= $this->show_list_file_curriculum();
        
        
        // Показать план
        
        if ( isset( $att_id ) ) $out .= $this->show_file_curriculum( $att_id );
        
        
        // 
        
        $out .= $this->get_meta();


        return apply_filters( 'mif_mr_get_tools_curriculum', $out );

    }
    

    
    // 
    // Вывести  
    // 

    public function show_file_curriculum( $att_id )
    {
        $att = get_post( $att_id );
        
        if ( empty( $att ) ) return;
        if ( $att->post_type != 'attachment' ) return;
        
        $ext = explode( '.', $att->guid );
        if ( array_pop( $ext ) != 'plx' ) return;
        
        // p('@');
        
        // $plx = simplexml_load_file( get_attached_file( $att_id ) );
        // p(get_attached_file( $att_id ));
        $plx = new plx( get_attached_file( $att_id ) );

        // p($plx);
        // p($plx->get_courses());
        // p($plx->get_curriculum());
        // p($plx->get_matrix());
        // p($plx->get_cmp());
        // p($plx->get_kaf());
        // p($plx->get_att());
        // p($plx->get_att_arr());
        
        $out = '';
        
        $out .= $this->get_part( array( 'title' => 'Дисциплины', 'key' => 'courses', 'data' => $plx->get_courses(), 'save' => 'Сохранить в ОПОП', 'analysis' => 'Анализ' ) );
        $out .= $this->get_part( array( 'title' => 'Матрица компетенций', 'key' => 'matrix', 'data' => $plx->get_matrix(), 'save' => 'Сохранить в ОПОП', 'analysis' => 'Анализ' ) );
        $out .= $this->get_part( array( 'title' => 'Учебный план', 'key' => 'curriculum', 'data' => $plx->get_curriculum(), 'save' => 'Сохранить в ОПОП', 'analysis' => 'Анализ' ) );
        $out .= $this->get_part( array( 'title' => 'Библиотека компетенций', 'key' => 'lib-competencies', 'data' => $plx->get_cmp(), 'save' => 'Сохранить в библиотеке' ) );
        $out .= $this->get_part( array( 'title' => 'Библиотека параметров ОПОП', 'key' => 'att', 'data' => $plx->get_att(), 'save' => 'Сохранить в библиотеке', 'analysis1' => 'Анализ' ) );
        $out .= $this->get_part( array( 'title' => 'Библиотека параметров организации', 'key' => 'kaf', 'data' => $plx->get_kaf(), 'save' => 'Сохранить в библиотеке' ) );


        $out .= '<input type="hidden" name="attid" value="' . $att_id . '" />'; 

        // $out .= '<textarea name="att" class="edit textarea mt-4" readonly>';
        // $out .= $plx->get_att();
        // $out .= '</textarea>';

        // $out .= '<textarea name="courses" class="edit textarea mt-4" readonly>';
        // $out .= $plx->get_courses();
        // $out .= '</textarea>';

        // $out .= '<textarea name="curriculum" class="edit textarea mt-4" readonly>';
        // $out .= $plx->get_curriculum();
        // $out .= '</textarea>';

        // $out .= '<textarea name="matrix" class="edit textarea mt-4" readonly>';
        // $out .= $plx->get_matrix();
        // $out .= '</textarea>';

        // $out .= '<textarea name="cmp" class="edit textarea mt-4" readonly>';
        // $out .= $plx->get_cmp();
        // $out .= '</textarea>';

        // $out .= '<textarea name="kaf" class="edit textarea mt-4" readonly>';
        // $out .= $plx->get_kaf();
        // $out .= '</textarea>';


        $out .= '';
    
        // $out .= $att_id;
    
            
        // p( $att );

        return $out;
        
    }
    
   

    // 
    // Вывести  
    // 
    
    private function get_part( $att = array() )
    {
        $out = '';
        
       
        $out .= '<div class="row mt-5 mb-5 plx-item">';
        $out .= '<div class="col p-0 pt-3 pb-3">';
        if ( isset( $att['title'] ) ) $out .= '<div class="mt-3 mb-3 fw-semibold">' . $att['title'] . '</div>';
        if ( isset( $att['save'] ) ) $out .= '<a href="#" class="mr-3 save"><i class="fa-regular fa-floppy-disk fa-lg"></i>' . $att['save'] . '</a>';
        if ( isset( $att['analysis'] ) ) $out .= '<a href="#" class="mr-3 analysis"><i class="fa-solid fa-chart-simple fa-lg"></i>' . $att['analysis'] . '</a> ';
        $out .= '<a href="#" class="mr-3 copy-button"><i class="fa-regular fa-clone fa-lg"></i>Копировать</a> ';
        $out .= '</div>'; 
        $out .= '<div class="report p-0" style="display: none;"></div>'; 
        $out .= '<textarea name="' . $att['key']. '" class="edit textarea" readonly>';
        $out .= $att['data'];
        $out .= '</textarea>'; 
        if ( isset( $att['analysis'] ) ) $out .= '<div class="analysis-box p-0" style="display: none;"></div>'; 
        $out .= '</div>'; 
        

        return $out;
        
    }
        
    
    // 
    // Вывести  
    // 

    public function show_list_file_curriculum()
    {
        $out = '';

        $arr = $this->get_file_curriculum();

        // p($arr);

        $out .= '<div class="container mt-5">';
        
        $out .= '<div class="row">';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold">№</div>';
        $out .= '<div class="col p-2 pt-4 pb-4 fw-semibold">Название плана</div>';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold">Файл</div>';
        $out .= '<div class="col col-1 p-2 pt-4 pb-4 fw-semibold"></div>';
        $out .= '</div>';
        
        $n = 0;
        $out .= '<div class="striped">';
        
        foreach ( $arr as $item ) {
            
            $out .= '<div class="row">';
            $out .= '<div class="col col-1 p-2">' . ++$n . '</div>';
            $out .= '<div class="col p-2"><a href="' .  mif_mr_opop_core::get_opop_url() . 'tools-curriculum/' . $item->ID . '">' . $item->post_title . '</a></div>';
            $out .= '<div class="col col-1 p-2 text-center"><a href="' . $item->guid . '"><i class="fa-regular fa-file-code fa-lg"></i></a></div>';
            $out .= '<div class="col col-1 p-2 text-center"><a href="#" class="remove" data-attid="' . $item->ID . '"><i class="fa-regular fa-trash-can fa-lg"></i></a></div>';
            $out .= '</div>';
            
        }
            
        $out .= '</div>';

            // $out .= '<div class="row d-none no-plans">';
            $style = ( $n === 0 ) ? '' : ' style="display: none;"'; 
            $out .= '<div class="row no-plans"' . $style . '>';
            $out .= '<div class="col p-4 text-center bg-light border rounded fw-semibold">Нету планов</div>';
            $out .= '</div>';

        $out .= '</div>';


        // $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';  
        

        return $out;
        
    }





    // 
    // Получить файлы плана
    //    

    public function get_file_curriculum()
    {
        global $post;

        $args = array(
            'numberposts' => -1,
            'post_parent' => mif_mr_opop_core::get_opop_id(), 
            'post_type' => 'attachment',
            // 'order' => 'ASC',
            'order' => 'DESC',
            'post_status' => 'inherit',
            // 'orderby' => 'menu_order',
        );
        
        $arr = get_posts( $args );
            
        // Удалить из ответа лишние файлы 
        
        foreach ( $arr as $key => $item ) {
            $arr_tmp = explode( '.', $item->guid );
            $ext = array_pop( $arr_tmp );
            if ( ! in_array( $ext, array( 'plx' ) ) ) unset( $arr[$key] );
        }
            
        return $arr;
    }



    //
    // Сохранить файл плана
    // 

    public function save()
    {
        
        // !!!!!!!!!
    
        if ( ! isset( $_FILES['file']['tmp_name'] ) ) return false;

        libxml_use_internal_errors(true);

        if ( simplexml_load_file( $_FILES['file']['tmp_name'] ) !== false ) {

            $title = $_FILES['file']['name'];

            if ( empty( $_REQUEST['title'] ) ) {

                $plx = new plx( $_FILES['file']['tmp_name'] );
                $arr = $plx->get_att_arr();
                
                $title = $arr['Титул'] . ', ' . $arr['Год начала подготовки'] . ', ' . $arr['Форма обучения']; 
            
            } else {

                $title = sanitize_text_field( $_REQUEST['title'] );

            }

            // p( $title );

            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            $att_id = media_handle_upload( 'file', mif_mr_opop_core::get_opop_id(), array( 'post_title' => $title ) );
            
            // p($att_id);
            
            if ( is_wp_error( $att_id ) ) {
                
                return false;
                
            } else {
                
                return $att_id;
            
            }

        } else {

            return false;

        }

    }




  
    //
    // Сохранить часть данных из формы сайта
    // 

    public function save_comp( $att = array() )
    {
    
        // !!!!!!
    
    //     global $tree;
    // p($tree);
        $out = '';

        // p($_REQUEST);
        // p($att);
        // if ( $att['key'] == 'courses' ) {
        if ( in_array( $att['key'], array( 'courses', 'curriculum', 'matrix' ) ) ) {
        
            // if ( empty( $tree['content']['courses']['data'] ) ) {
            // if ( ! $this->is_empty( 'courses', $att['opop'] ) || ! empty( $att['yes'] ) ) {
            if ( ! $this->is_empty( $att['key'], $att['opop'] ) || ! empty( $att['yes'] ) ) {

                $m = new mif_mr_part_companion();
                $res = $m->companion_insert( array(
                    'type' => $att['key'],
                    'opop_id' => $att['opop_id'], 
                    'opop_title' => $att['opop_title'],
                    // 'content' => $att['data'],
                    'content' => $this->get_date_from_plx( $att['key'], $att['att_id'] ),
                ) );

                // p($res);

                $out .= mif_mr_functions::get_callout( 'Сохранено', 'success' );
                
            } else {
                    
                $out .= mif_mr_functions::get_callout( 
                    'В программе уже есть данные. Заменить новыми данными? <br />
                    <a href="#" class="mr-1 save" data-yes="yes">Сохранить</a>
                    <a href="#" class="mr-1 cancel">Отменить</a>
                    <a href="#" class="mr-1 analysis">Анализ</a>
                    ', 'warning' );
                    
                    // 'Это данные не являются пустыми. Заменить новыми данными? <br />
                    // Данные не являются пустыми. Заменить новыми данными?
                    // Данные не пустые. Замените их новыми данными?

            };
            
        } elseif ( in_array( $att['key'], array( 'lib-competencies' ) ) ) {

            // p(  get_post( $att['att_id'] ) );
            $attached = get_post( $att['att_id'] );
            // p( $attached->post_title );
            
            $m = new mif_mr_companion_core();
            $res = $m->companion_insert( array(
                'type' => $att['key'],
                'opop_id' => $att['opop_id'], 
                // 'opop_title' => $att['opop_title'],
                'title' => $attached->post_title,
                'data' => $this->get_date_from_plx( $att['key'], $att['att_id'] ),
            ) );

            p($res);

            $out .= mif_mr_functions::get_callout( 'Сохранено', 'success' );


        }

        


        return $out;
    }
    
    
        
        
        
        //
        // Анализ
        // 
        
    public function analysis( $att = array() )
    {
        
        // p($_REQUEST);
        $out = '';

        // p($att);
        // $out .= '@';
        $m = new mif_mr_part_companion();
        $data[0] = $this->get_date_from_plx( $att['key'], $att['att_id'] );
        $data[1] = $m->get_companion_content( $att['key'], $att['opop_id'] );
        // p($att['att_id']);


        $data = $this->analysis_process( $data );


        $one = '<div class="p-1 pt-3 pb-3 bg-light">' . $data[0] . '</div>';
        $two = '<div class="p-1 pt-3 pb-3 bg-light">' . $data[1] . '</div>';
        // $one = '<pre>' . $data[0] . '</pre>';
        // $two = '<pre>' . $data[1] . '</pre>';

        
        $out .= '<div class="container mt-5 fullsize-fix">';

        $out .= '<div class="row">';
        $out .= '<div class="col d-none d-sm-block mt-5 pr-0 text-end">';
        // $out .= '<a href="#">Сохранить</a>';
        // $out .= '<a href="#">Отменить</a></p>';
        $out .= '<a href="#" class="text-secondary" id="fullsize">';
        $out .= '<i class="fa-solid fa-expand fa-2x"></i><i class="d-none fa-solid fa-compress fa-2x"></i>';
        $out .= '</a>';
        $out .= '</div>';
        $out .= '</div>';

        $out .= '<div class="row">';

        $out .= '<div class="col col-6 p-0 pr-2 pt-4 pb-4"><div class="text-center fw-semibold">из файла</div>' . $one. '</div>';
        $out .= '<div class="col col-6 p-0 pl-2 pt-4 pb-4"><div class="text-center fw-semibold">из Matrix</div>' . $two. '</div>';

        $out .= '</div>';
   
        $out .= '<div class="row">';

        $out .= '<div class="col p-3 bg-light">';
        $out .= '<a href="#" class="mr-3 save" data-yes="yes">Сохранить</a>';
        $out .= '<a href="#" class="mr-3 cancel-analysis">Отменить</a>';
        $out .= '</div>';

        $out .= '<div class="col p-3 bg-light text-end">';
        $out .= '<a href="#" class="mr-3 help"><i class="fa-regular fa-circle-question fa-xl"></i></a>';
        $out .= '</div>';

        $out .= '</div>';
  
        $out .= '<div class="row">';

        $out .= '<div class="col bg-light help-box" style="display: none;">';
        $out .= mif_mr_functions::get_callout( 
                    'белые — эти строки доступны одинаково как в первом, так и во втором списке<br />
                     желтые — есть дисциплина, но параметры другие<br />
                     красные — это строки, которые написаны один раз, отсутствуют в другом списке.
                    ', 'info' );;
        $out .= '</div>';

        $out .= '</div>';
  
        $out .= '</div>';

        return $out;
    }




    private function analysis_process( $data = array() )
    {

        foreach ( array( 0, 1 ) as $k ) $arr[$k] = array_map( 'strim', explode( "\n", $data[$k] ) );     
        
        $p = new parser();
        foreach ( array( 0, 1 ) as $k ) foreach ( $arr[$k] as $item ) {
            
            $d = $p->parse_name( $item );
            $a = trim( preg_replace( '/' . $d['name'] . '/', '', $item ) ); 
            $arr2[$k][] = array( $d['name'], $a );       
            
            $arr3[$k][] = 2;

        }

        foreach ( $arr2[0] as $k => $i )
        foreach ( $arr2[1] as $k2 => $i2 ) {
        
            if ( $i[0] === $i2[0] ) {

                if ( $arr3[0][$k] != 0 ) $arr3[0][$k] = 1;
                if ( $arr3[1][$k2] != 0 ) $arr3[1][$k2] = 1;

            }
            
            if ( $i[0] === $i2[0] && $i[1] === $i2[1] ) {
                
                $arr3[0][$k] = 0;
                $arr3[1][$k2] = 0;
            
            }

        }

        // foreach ( $arr2[0] as $k => $i )
        // foreach ( $arr2[1] as $k2 => $i2 ) {
        
        //     if ( $i[0] !== $i2[0] ) continue;
            
        //     $arr3[0][$k] = 1;
        //     $arr3[1][$k2] = 1;
            
        //     if ( $i[1] === $i2[1] ) {
                
        //         $arr3[0][$k] = 0;
        //         $arr3[1][$k2] = 0;
            
        //     }

        //     // if ( $arr3[0][$k] == 1 ) {
                
        //     //     p('@');
        //     //     p($i[0]);
        //     //     p($i[1]);
        //     //     p($i2[0]);
        //     //     p($i2[1]);

        //     // }

        // }
            
        // p($arr2);
        
        $f = true;
        foreach ( array( 0, 1 ) as $k ) if ( empty( $data[$k]) ) $f = false;
        
        foreach ( array( 0, 1 ) as $k ) foreach ( $arr[$k] as $key => $item ) {
            
            $c = '';
            
            if ( $f && $arr3[$k][$key] === 1 ) $c = ' mr-yellow'; 
            if ( $f && $arr3[$k][$key] === 2 ) $c = ' mr-red'; 
            
            $arr[$k][$key] = '<div class="pl-3 pr-3 m-1' . $c . '">' . $item . '</div>';
        
        }

        foreach ( array( 0, 1 ) as $k ) $data[$k] = implode( "\n", $arr[$k]);

        return $data;
    }




    private function is_empty( $type, $opop_id )
    {
        $m = new mif_mr_part_companion();
        $res = $m->get_companion_id( $type, $opop_id );

        // p('@');
        // p($res);
        // p(get_post($res));

        return ( $res ) ? true : false;
    }




    private function get_date_from_plx( $type, $att_id )
    {
        $plx = new plx( get_attached_file( $att_id ) );

        switch ( $type ) {  
            case 'courses': $data = $plx->get_courses(); break;  
            case 'curriculum': $data = $plx->get_curriculum(); break;  
            case 'matrix': $data = $plx->get_matrix(); break;  
            case 'lib-competencies': $data = $plx->get_cmp(); break;  
            case 'kaf': $data = $plx->get_kaf(); break;  
            case 'att': $data = $plx->get_att(); break;  
            case 'get_att_arr': $data = $plx->get_att_arr(); break;  
            default: $data = ''; break;  
        }  
        
        return $data;
    }


    

    function remove( $attid )
    {
        
        // !!!!!!!

        $res = ( wp_delete_attachment( $attid, true ) === false ) ? false : true;
        return $res;
    }




    private static function get_meta()
    {
        $out = '';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '" />';  
        $out .= '<input type="hidden" name="opop" value="' . mif_mr_opop_core::get_opop_id() . '" />';  
        $out .= '<input type="hidden" name="opop_title" value="' . mif_mr_opop_core::get_opop_title() . '" />';  
        return $out;
    }







    // // Форма загрузки

    // public function upload_curriculum()
    // {
       
    //     $out = '';
    //     p($_REQUEST);

    //     if ( isset( $_REQUEST['submit'] ) ) {
        
    //         p('@');

    //         // global $post;
             
    //         // require_once( ABSPATH . 'wp-admin/includes/image.php' );
    //         // require_once( ABSPATH . 'wp-admin/includes/file.php' );
    //         // require_once( ABSPATH . 'wp-admin/includes/media.php' );

    //         // $attachment_id = media_handle_upload( 'template_file', $post->ID, array( 'post_title' => $_REQUEST['template_name'] ) );
           
    //         // if ( is_wp_error( $attachment_id ) ) {
    //         //     $out .= '<div class="error">Ошибка загрузки файла</div>';
    //         // } else {
    //         //     $out .= '<div class="note">Файл успешно загружен</div>';
    //         // }
           
    //     } else {
          
    //         $out .= mif_mr_upload::form_upload( array( 
    //                             'text' => 'Загрузите файл учебного плана в формате XML', 
    //                             'title_placeholder' => 'Название плана', 
    //                             'url' => 'tools-curriculum' 
    //                         ) );


    //         // $out .= '<div class="upload"><p>Загрузка шаблона';
            
    //         // $out .= '<form method="post" action="#" enctype="multipart/form-data">
    //         // <p><input type="text" name="template_name" placeholder="Название шаблона">
    //         // <p><input type="file" name="template_file" multiple="false">
    //         // <p><input type="submit" name="template_submit" value="Загрузить">       
    //         // </form>';
         
    //         // $out .= '</div>';
    //     }

    //     return $out;
            
    // }
    
    
    
    
}

?>