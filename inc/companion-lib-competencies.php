<?php

//
//  Список компетенций
//  Компетенции
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_comp extends mif_mr_companion_core {
    

    function __construct()
    {
        parent::__construct();

        // $this->name_indicators = apply_filters( 'mif-mr-name-indicators', array( 'знать', 'уметь' ) );
        $this->sub_default = apply_filters( 'mif-mr-sub_default', 'default' );


    }
  
        
    // 
    // Показывает компетенции
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-competencies.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-competencies.php', false );

        }
    }
    

    
    
    //
    // Показать компетенции
    //
    
    public function show_comp( $comp_id = NULL, $opop_id = NULL )
    {
        // Init $comp_id, $opop_id
        
        if ( empty( $comp_id ) ) {
            
            global $wp_query;
            if ( ! isset( $wp_query->query_vars['id'] ) ) return 'wp: error 1';
            $comp_id = $wp_query->query_vars['id'];

        }

        if ( empty( $opop_id ) ) $opop_id = mif_mr_opop_core::get_opop_id();
           
        
        // Save

        // if ( isset( $_REQUEST['do'] ) && $_REQUEST['do'] == 'save' ) {
            
            if ( isset( $_REQUEST['sub'] ) ) $this->save_part( (int) $_REQUEST['sub'], $comp_id, $opop_id );

        // }


        // HTML

        global $tree;
        
        $out = '';
        $f = true;

        $out .= '<div class="content-ajax">';

        // if ( $f ) $out .= '<div><a href="' . get_edit_post_link( $comp_id ) . '">Расширенный редактор</a></div>';
        
        if ( isset( $tree['content']['lib-competencies']['data'][$comp_id] ) ) {

            $item = $tree['content']['lib-competencies']['data'][$comp_id];

            // $out .= '<h4 class="mb-6 mt-5 ">' . $item['name'] . '</h4>';
            $out .= '<h4 class="mb-4 mt-0 pb-5 pt-5 bg-body fiksa">' . $item['name'] . '</h4>';
            // $out .= 'id: ' . $comp_id;
            $out .= $this->get_show_all();

            $out .= '<div class="container no-gutters">';

            foreach ( $item['data'] as $item2 ) {
                
                $out .= '<span>';
                
                $out .= $this->show_comp_sub( $item2['sub_id'], $comp_id, $opop_id );
                
                $out .= '</span>';
                
            }
        
            // New

            if ( $f ) $out .= '<div class="row mb-3 mt-6">';
            if ( $f ) $out .= '<div class="col mr-gray p-3 fw-bolder text-center">';
            if ( $f ) $out .= '<a href="#" class="new"><i class="fa-solid fa-plus fa-xl"></i></a>';
            if ( $f ) $out .= '</div>';
            if ( $f ) $out .= '</div>';
            
            $out .= '</div>';
            
        }
        
        // if ( $f ) $out .= '<div class="row mt-3">';
        // if ( $f ) $out .= '<div class="col">';
        // if ( $f ) $out .= '<small><a href="#" class="msg-remove">Удалить</a></small>';
        
        // $msg = '<div>Вы уверены?</div>';

        // $msg .= '<div><label class="form-label mt-4"><input type="checkbox" name="yes" value="on" class="form-check-input"> Да</label></div>';
        // $msg .= '<button type="button" class="btn btn-primary mr-3 remove">Удалить <i class="fas fa-spinner fa-spin d-none"></i></button>';
        // $msg .= '<button type="button" class="btn btn-light border mr-3 cancel">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';
      
      
        // if ( $f ) $out .= '<div class="alert pl-0 pr-0" style="display: none;">' . mif_mr_functions::get_callout( $msg, 'warning' ) . '</div>';
        
        // if ( $f ) $out .= '</div>';
        // if ( $f ) $out .= '</div>';

        // Hidden
        
        if ( $f ) $out .= '<input type="hidden" name="opop" value="' . $opop_id . '">';
        if ( $f ) $out .= '<input type="hidden" name="comp" value="' . $comp_id . '">';
        if ( $f ) $out .= '<input type="hidden" name="action" value="lib-competencies">';
        if ( $f ) $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';
        
        $out .= '</div>';
        
        return apply_filters( 'mif_mr_show_competencies', $out );
    }
    
    
    

    //
    // Показать cписок компетенций - часть
    //
    
    public function show_comp_sub( $sub_id, $comp_id, $opop_id = NULL )
    {
        global $tree;
        
        $f = true;
        
        if ( empty( $opop_id ) ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        if ( ! ( isset( $tree['content']['lib-competencies']['data'][$comp_id] ) || $sub_id == '-1' ) ) return 'wp: error 2';
              
        $item = $tree['content']['lib-competencies']['data'][$comp_id]['data'][$sub_id];
        // $style = ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'save' ) ? '' : 'style="display: none;"';
        
        // HTML
        
        $out = '';   
        $out .= '<span class="content-ajax">';
        
        $out .= $this->get_sub_head( array(
                                            'name' => $item['name'],
                                            'sub_id' => $sub_id,
                                            'f' => $f            
                                            ) );

        // $out .= '<div class="row mb-3 mt-3">';
        
        // // Наименование категории

        // $out .= '<div class="col-11 mr-gray p-3 fw-bolder">';
        // $out .= $item2['name'];
        // $out .= '</div>';
        
        // // Кнопка edit

        // $out .= '<div class="col-1 mr-gray p-3 text-end">';
        // if ( $f ) $out .= '<i class="fas fa-spinner fa-spin d-none"></i> ';
        // if ( $f ) $out .= '<a href="#" class="edit pr-1" data-sub="' . $sub_id . '"><i class="fa-regular fa-pen-to-square"></i></a>';
        // $out .= '<a href="#" class="roll-up d-none"><i class="fa-solid fa-angle-up"></i></a>';
        // $out .= '<a href="#" class="roll-down"><i class="fa-solid fa-chevron-down"></i></a>';
        // $out .= '</div>';
        
        // $out .= '</div>';

        if ( isset( $_REQUEST['do'] ) && $_REQUEST['do'] == 'edit' ) {
            
            // Режим edit

            if ( $f ) $out .= $this->get_sub_edit( $sub_id, $comp_id, $opop_id );
            
        } else {
            
            // Режим отображения
            
            foreach ( $item['data'] as $item2 ) $out .= $this->get_item_body( $item2 );

        }
        
        $out .= '</span>';
   
        return apply_filters( ' mif_mr_show_competencies_sub', $out, $sub_id, $comp_id, $opop_id );
    }
    
    
    

    
    //
    // Показать cписок компетенций - компетенция, body
    //
    
    public static function get_item_body( $item )
    {
        $name_indicators = apply_filters( 'mif-mr-name-indicators', array( 'знать', 'уметь' ) );
        
        $out = '';

        $out .= '<div class="row">';
        
        $out .= '<div class="col col-2 col-md-1 fw-bolder">';
        $out .= ( isset( $item['name'] ) ) ? $item['name'] : '';
        $out .= '</div>';
        
        $out .= '<div class="col mb-3">';
        $out .= ( isset( $item['descr'] ) ) ? $item['descr'] : '';
        $out .= '</div>';
        
        $out .= '</div>';

        // Индикаторы

        if ( isset( $item['indicators'] ) ) {

            foreach ( (array) $item['indicators'] as $key4 => $item4 ) {
                
                $style = ' style="display: none;"';

                $out .= '<div class="row coll"' . $style . '>';
                
                $out .= '<div class="col col-2 col-md-1">';
                $out .= '</div>';
                
                $out .= '<div class="col p-1 pl-3 m-3 mr-gray fst-italic">';
                $out .= ( isset( $name_indicators[$key4] ) ) ? $name_indicators[$key4] : 'индикатор ' . $key4;
                $out .= '</div>';
                
                $out .= '</div>';
                
                $out .= '<div class="row coll"' . $style . '">';
                $out .= '<div class="col col-2 col-md-1">';
                $out .= '</div>';
                
                $out .= '<div class="col">';
                foreach ( $item4 as $item5 ) $out .= '<p class="m-2">' . $item5 . '</p>';
                $out .= '</div>';
                $out .= '</div>';
                
            }
            
            $out .= '<div class="row coll"' . $style . '">';
            $out .= '<div class="col col-2 col-md-1">';
            $out .= '</div>';
            
            $out .= '<div class="col">';
            $out .= '&nbsp;';
            $out .= '</div>';
            $out .= '</div>';
            
        
        }
        
        return apply_filters( ' mif_mr_get_item_body', $out, $item );
    }





    //
    // Показать cписок компетенций
    //
    
    public function show_lib_comp( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        ####!!!!!
        
        $f = true;
       
        $this->create( $opop_id );
        
        $out = '';
        
        // $list = $this->get_list_companions( 'competencies' );
        
        // p($list);
        // p($arr);
        
        $out .= '<div class="content-ajax">';
        
        $out .= '<div class="comp container bg-light pt-5 pb-5 pl-4 pr-4 border rounded">';
        // $out .= '<div class="container no-gutters">';
        
        $out .= '<div class="row">';
        
        $out .= '<div class="col">';
        $out .= '<h4 class="border-bottom pb-5"><i class="fa-regular fa-file-lines"></i> Библиотека компетенций</h4>';
        // $out .= '<hr class="bg-secondary fs-1">';
        $out .= '</div>';
        
        $out .= '</div>';
        global $tree;

        $arr = array();
        if ( isset( $tree['content']['lib-competencies']['data'] ) ) $arr = $tree['content']['lib-competencies']['data'];
    
        foreach ( $arr as $item ) {
     
            // p($item);

            $out .= '<div class="row mt-3 mb-3">';
            
            $out .= '<div class="col-10 col-md-11 pt-1 pb-1">';
            $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'lib-competencies/' . $item['comp_id'] . '">' . $item['name'] . '</a>';
            $out .= '</div>';
            
            $out .= '<div class="col-2 col-md-1 pt-1 pb-1 text-end">';
            $out .= ( $item['parent'] == mif_mr_opop_core::get_opop_id() ||  $item['parent'] == 0 ) ?
                    // $item['parent'] :
                    '' :
                    '<a href="' .  get_permalink( $item['parent'] ) . 'lib-competencies/' . $item['comp_id'] . '" title="' . 
                    $this->mb_substr( get_the_title( $item['parent'] ), 20 ) . '">' . $item['parent'] . '</a>';
            $out .= '</div>';
            
            $out .= '</div>';
            
        }
        
        if ( $f ) $out .= $this->show_lib_comp_create();
    
        $out .= '</div>';
        
        $out .= '</div>';
        
        $out .= '</div>';
        
        
        return apply_filters( 'mif_mr_show_list_competencies', $out );
    }
    
    
    
    //
    // Форму создания 
    //
    
    private  function show_lib_comp_create()
    {
        $out = '';
        
        $out .= '<div class="row mt-5">';
        $out .= '<div class="col">';
        // $out .= '<button type="button" class="btn btn-primary">Создать список компетенций</button>';
        $out .= '<button type="button" class="btn btn-primary new">Создать список</button>';
        $out .= '</div>';
        $out .= '</div>';
        
        $out .= '<div class="row new" style="display: none;">';
        $out .= '<div class="col mt-5">';
        // $out .= '<button type="button" class="btn btn-primary">Создать список компетенций</button>';
        
        // $out .= '';
        
        $out .= '<div class="mb-3">';
        // $out .= '<label class="form-label">Название перечня компетенций</label>';
        $out .= '<label class="form-label">Название cписка компетенций:</label>';
        $out .= '<input name="title" class="form-control" autofocus>';
        $out .= '<div class="form-text">Например: ФГОС "Информатика и вычислительная техника", ОПОП "Математика", ...</div>';
        $out .= '</div>';
        
        $out .= '<div class="mb-3">';
        // $out .= '<label class="form-label">Данные <i class="fa-regular fa-circle-question" style="color: #aaa;"></i></label>';
        $out .= '<label class="form-label">Данные:</label>';
        // $out .= mif_mr_functions::get_callout( '<a href="' . '123' . '">Помощь</a>', 'warning' );
        $out .= '<textarea name="data" class="form-control" rows="3"></textarea>';
        $out .= '<div class="form-text">Например: УК-1. Способен использовать философские знания, ... (<a href="' . '123' . '">помощь</a>)</div>';
        $out .= '<button type="button" class="btn btn-primary mt-4 mr-3 create">Сохранить <i class="fas fa-spinner fa-spin d-none"></i></button>';
        $out .= '<button type="button" class="btn btn-light border mt-4 mr-3 cancel">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';
        
        $out .= '<input type="hidden" name="opop" value="' . mif_mr_opop_core::get_opop_id() . '">';
        $out .= '<input type="hidden" name="action" value="lib-competencies">';
        $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';
        
        $out .= '</div>';
        $out .= '</div>';
       
        return apply_filters( 'mif_mr_show_list_compe_create', $out );
    }
    





   
    //
    // Возвращает текст компетенции из дерева
    //

    public function get_sub_arr( $comp_id )
    {
        global $tree;
        
        $arr = array();
        if ( isset( $tree['content']['lib-competencies']['data'][$comp_id] ) ) $arr = $tree['content']['lib-competencies']['data'][$comp_id];
        
        $out = array();
        
        foreach ( $arr['data'] as $item ) {
            
            $s = '';
            $s .= '= ' . $item['name'] . "\n\n";
            
            if ( empty( $item['data'] ) ) continue;
            
            foreach ( $item['data'] as $item2 ) {
                
                $s .= $item2['name'] . '. ';
                $s .= $item2['descr'] . "\n\n";
                
                if ( empty( $item2['indicators'] ) ) continue;
                
                foreach ( $item2['indicators'] as $item3 ) {
                    
                    $s .= implode( "\n", $item3 );
                    $s .= "\n\n";
                    
                }
                
                $s .= "\n";
                
            }
            
            $out[$item['sub_id']] = $s;

        }

        // p($arr);

        return apply_filters( 'mif_mr_companion_get_sub_arr', $out, $comp_id );
    }


        
    //
    // 
    //
    
    public function get_all_arr( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        $arr = array();
        $list = $this->get_list_companions( 'lib-competencies', $opop_id );
    
        foreach ( $list as $item ) {

            $arr2 = $this->get_arr( $item['id'] );
            $arr[$arr2['comp_id']] = $arr2;

        }

        return apply_filters( 'mif_mr_get_all_arr', $arr );

    }



    //
    // Возвращает массив из текста (post)
    //
    
    public function get_arr( $id )
    {
        $arr = array();
        $arr_raw = array();
       
        $post = get_post( $id );

        // p($post->post_title);
        // p($post->post_content);
        
        $data = '== ' . $post->post_title . "\n";
        $data .= $this->get_begin_data( $post );

        // $data .= "= " . $this->sub_default . "\n";
        // // $data .= "= default\n";
        // $data .= $post->post_content;
        
        $p = new parser();
        $arr_raw = $p->get_arr( $data, array( 'section' => $id, 'att_parts' => false, 'default' => true ) );

        $arr_raw = current( current( $arr_raw ) );
        // p($arr_raw);

        $arr['comp_id'] = $id;
        $arr['parent'] = $post->post_parent;
        $arr['name'] = $arr_raw['name'];
        // $arr['competencies'] = '';

        // p($arr);

        if ( isset( $arr_raw['parts'] ) ) {
            
            $arr2 = array();
            
            foreach ( (array) $arr_raw['parts'] as $key => $item ) {
                
                if ( empty( $item['data']) ) continue;
            
                // p($key);
                // p($item);
                
                $arr2[$key]['sub_id'] = $item['sub_id'];
                $arr2[$key]['name'] = $item['name'];
                
                $n = 0;
                $arr3 = array();
                
                foreach ( $item['data'] as $key2 => $item2 ) {
                    
                    // if ( preg_match( '/(^.+-\d+.\s+)(.*)/', $item2, $m ) ) {
                        if ( preg_match( '/(^.+-\d+)(.\s+)(.*)/', $item2, $m ) ) {
                        // if ( preg_match( '/(^\W+-\d+)(.\s+)(.*)/', $item2, $m ) ) {
                        // p($item);

                        $arr3[] = array(
                                        'name' => $m[1],
                                        'descr' => $m[3],
                                        'category' => $item['name'],
                                    );
                        
                        $n = 0;
                        continue;

                    } 
                    
                    $arr3[array_key_last($arr3)]['indicators'][$n++] = array_map( 'trim', explode( "\n", $item2 ) );
                    
                }
                
                
                $arr2[$key]['data'] = $arr3;
                
                // p($arr3);
                
                // // if ( ! empty( $arr2 ) ) $arr[$key][$key2]['parts'][$key3]['data'] = $arr2;
                // // if ( empty( $arr[$key][$key2]['parts'][$key3]['data'] ) ) 
                // // unset( $arr[$key][$key2]['parts'][$key3] );
                
            }
            
            $arr4 = array();
            foreach ( $arr2 as $item3 ) $arr4[$item3['sub_id']] = $item3;
            // $arr['competencies'] = $arr4;
            $arr['data'] = $arr4;

        }

        // p($arr);

        return apply_filters( 'mif_mr_get_competencies_arr', $arr, $id );
    }
        


 
    // //
    // // 
    // //
    
    // public static function set_comp_to_tree( $t = array() )
    // {
    //     $arr = array();
        
    //     foreach ( $t['content']['set-competencies']['data'] as $item ) {

    //         if ( is_numeric( $item[2] ) ) {

    //             if ( isset( $t['content']['lib-competencies']['data'][$item[2]] ) )
    //                 foreach ( $t['content']['lib-competencies']['data'][$item[2]]['data'] as $item2 ) 
    //                     foreach ( $item2['data'] as $item3 ) 
    //                         if ( $item3['name'] == $item[1] ) { 
    //                             $item3['old_name'] = $item3['name'];
    //                             $item3['comp_id'] = $item[2];
    //                             $item3['name'] = $item[0];
    //                             $arr[$item[0]] = $item3;
    //                         }
    //         } else {
                
    //             foreach ( $t['content']['lib-competencies']['data'] as $item2 ) 
    //                 foreach ( $item2['data'] as $item3 ) 
    //                     foreach ( $item3['data'] as $item4 ) 
    //                         if ( $item4['name'] == $item[1] ) {
    //                             $item4['old_name'] = $item4['name'];
    //                             $item4['comp_id'] = $item2['comp_id'];
    //                             $item4['name'] = $item[0];
    //                             $arr[$item[0]] = $item4;
    //                         } 

    //         }

    //     }

    //     // p($arr);

    //     return apply_filters( 'mif_mr_comp_set_comp_to_tree', $arr, $t );
    // }




    private function get_begin_data( $post )
    {
        $data = '';
        if ( ! preg_match( '/^=/', $post->post_content ) ) $data .= "= " . $this->sub_default . "\n";
        $data .= $post->post_content;
        return $data;
    }


    
    // private $name_indicators = array();
    private $sub_default = '';
   
}


?>