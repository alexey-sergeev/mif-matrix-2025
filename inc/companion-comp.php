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

        $this->name_indicators = apply_filters( 'mif-mr-name-indicators', array( 'знать', 'уметь' ) );
        $this->sub_default = apply_filters( 'mif-mr-sub_default', 'default' );


    }
  
        
    // 
    // Показывает компетенции
    // 

    public function the_show()
    {
        if ( $template = locate_template( 'mr-comp.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-comp.php', false );

        }
    }
    


    // //
    // // 
    // //

    // public function show_list_comp()
    // {
    //     global $wp_query;
     
    //     if ( isset( $wp_query->query_vars['id'] ) ) return;
                
    //     $out = '';
        
    //     // $out .= '<textarea name="content" class="edit textarea mt-4" autofocus>';
    //     // $out .= $this->get_companion_content( $type );
    //     // $out .= '</textarea>';
        
    //     $list = $this->get_list_companions( 'competencies' );
        
    //     // p($arr);
        
    //     foreach ( $list as $item ) {
            
    //         $out .= '<div class="col pt-2 pb-2 mt-3">';
    //         // $out .= '<a href="' . get_permalink($item['id']) . '">' . $item['title'] . '</a>';
    //         $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'competencies/' . $item['id'] . '">' . $item['title'] . '</a>';
    //         $out .= '</div>';
            
    //     }
        
    //     // p($arr);
    //     // $this->get_all_arr();
        
    //     return apply_filters( 'mif_mr_show_list_competencies', $out );
    // }
    
    
    
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

        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'save' ) {
            
            if ( isset( $_REQUEST['sub'] ) ) $this->save( (int) $_REQUEST['sub'], $comp_id, $opop_id );

        }


        // HTML

        global $tree;
        
        $out = '';
        $f = true;

        $out .= '<div class="content-ajax">';

        $out .= '<div class="pb-4"><a href="' . get_permalink( $opop_id ) .'competencies/">← Вернуться к странице раздела</a></div>';
        if ( $f ) $out .= '<div><a href="' . get_edit_post_link( $comp_id ) . '">Расширенный редактор</a></div>';
        
        if ( isset( $tree['content']['competencies']['data'][$comp_id] ) ) {

            $item = $tree['content']['competencies']['data'][$comp_id];


            $out .= '<h4 class="mb-6 mt-5">' . $item['name'] . '</h4>';
            $out .= '<div class="text-end">';
            $out .= '<small><a href="#" id="roll-down-all">Показать всё</a> | <a href="#" id="roll-up-all">Свернуть</a></small>';
            $out .= '</div>';

            $out .= '<div class="container no-gutters">';

            foreach ( $item['data'] as $item2 ) {
                
                if ( $f ) $out .= '<span>';
                
                $out .= $this->show_comp_sub( $item2['sub_id'], $comp_id, $opop_id );
                
                if ( $f ) $out .= '</span>';
                
            }
        
            // New

            if ( $f ) $out .= '<div class="row mb-3 mt-6">';
            if ( $f ) $out .= '<div class="col mr-gray p-3 fw-bolder text-center">';
            if ( $f ) $out .= '<a href="#" class="new"><i class="fa-solid fa-plus fa-xl"></i></a>';
            if ( $f ) $out .= '</div>';
            if ( $f ) $out .= '</div>';
            
            $out .= '</div>';
            
        }
        
        if ( $f ) $out .= '<div class="row mt-3">';
        if ( $f ) $out .= '<div class="col">';
        if ( $f ) $out .= '<small><a href="#" class="remove">Удалить</a></small>';
        // if ( $f ) $out .= '<div class="alert" style="display: none;">Вы уверены? <a href="#" class="ok">Да</a> / <a href="#" class="cancel">отмена</a></div>';
        if ( $f ) $out .= '<div class="alert pl-0 pr-0" style="display: none;">' . 
                            mif_mr_functions::get_callout( 'Вы уверены? <a href="#" class="ok">Да</a> / <a href="#" class="cancel">отмена</a>', 'warning' ) . '</div>';
        if ( $f ) $out .= '</div>';
        if ( $f ) $out .= '</div>';

        // Hidden
        
        if ( $f ) $out .= '<input type="hidden" name="opop" value="' . $opop_id . '">';
        if ( $f ) $out .= '<input type="hidden" name="comp" value="' . $comp_id . '">';
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
        
        if ( ! ( isset( $tree['content']['competencies']['data'][$comp_id] ) || $sub_id == '-1' ) ) return 'wp: error 2';
        
        $item2 = $tree['content']['competencies']['data'][$comp_id]['data'][$sub_id];
        // $style = ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'save' ) ? '' : 'style="display: none;"';
        
        // HTML
        
        $out = '';   
        $out .= '<span class="content-ajax">';
        
        $out .= '<div class="row mb-3 mt-3">';
        
        // Наименование категории

        $out .= '<div class="col-11 mr-gray p-3 fw-bolder">';
        $out .= $item2['name'];
        $out .= '</div>';
        
        // Кнопка edit

        $out .= '<div class="col-1 mr-gray p-3 text-end">';
        if ( $f ) $out .= '<i class="fas fa-spinner fa-spin d-none"></i> ';
        if ( $f ) $out .= '<a href="#" class="edit pr-1" data-sub="' . $sub_id . '"><i class="fa-regular fa-pen-to-square"></i></a>';
        $out .= '<a href="#" class="roll-up d-none"><i class="fa-solid fa-angle-up"></i></a>';
        $out .= '<a href="#" class="roll-down"><i class="fa-solid fa-chevron-down"></i></a>';
        $out .= '</div>';
        
        $out .= '</div>';

        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) {
            
            // Режим edit

            if ( $f ) $out .= $this->get_edit( $sub_id, $comp_id, $opop_id );
            
        } else {
            
            // Режим отображения
            
            foreach ( $item2['data'] as $item3 ) {
                
                $out .= '<div class="row">';
                
                $out .= '<div class="col col-2 col-md-1 fw-bolder">';
                $out .= $item3['name'];
                $out .= '</div>';
                
                $out .= '<div class="col mb-3">';
                $out .= $item3['descr'];
                $out .= '</div>';
                
                $out .= '</div>';

                // Индикаторы

                if ( isset( $item3['indicators'] ) ) {

                    foreach ( (array) $item3['indicators'] as $key4 => $item4 ) {
                        
                        $style = ' style="display: none;"';

                        $out .= '<div class="row coll"' . $style . '>';
                        
                        $out .= '<div class="col col-2 col-md-1">';
                        $out .= '</div>';
                        
                        $out .= '<div class="col p-1 pl-3 m-3 mr-gray fst-italic">';
                        $out .= ( isset( $this->name_indicators[$key4] ) ) ? $this->name_indicators[$key4] : 'индикатор ' . $key4;
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
                
                }
                
            }

        }
        
        $out .= '</span>';
   
        return apply_filters( 'mif_mr_show_competencies_sub', $out, $sub_id, $comp_id, $opop_id );
    }
    

   
   
    //
    // Возвращает текст компетенции из дерева
    //

    public function get_sub_arr( $comp_id )
    {
        global $tree;
        
        $arr = array();
        if ( isset( $tree['content']['competencies']['data'][$comp_id] ) ) $arr = $tree['content']['competencies']['data'][$comp_id];
        
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
    // Возвращает массив из текста (post)
    //
    
    public function get_arr( $id )
    {
        $arr = array();
        $arr_raw = array();
       
        $post = get_post($id);

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
        $arr['name'] = $arr_raw['name'];
        // $arr['competencies'] = '';

        // p($arr);

        if ( isset( $arr_raw['parts']) ) {
            
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

                        $arr3[] = array(
                                        'name' => $m[1],
                                        'descr' => $m[3],
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
        



    private function get_begin_data( $post )
    {
        $data = '';
        if ( ! preg_match( '/^=/', $post->post_content ) ) $data .= "= " . $this->sub_default . "\n";
        $data .= $post->post_content;
        return $data;
    }


    
    private $name_indicators = array();
    private $sub_default = '';
   
}


?>