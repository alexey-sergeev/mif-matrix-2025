<?php

//
//  Список компетенций
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_competencies extends mif_mr_companion_core {
    

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
        if ( $template = locate_template( 'mr-competencies.php' ) ) {
           
            load_template( $template, false );

        } else {

            load_template( dirname( __FILE__ ) . '/../templates/mr-competencies.php', false );

        }
    }
    


    //
    // 
    //

    public function show_list_competencies()
    {
        global $wp_query;
     
        if ( isset( $wp_query->query_vars['id'] ) ) return;
                
        $out = '';
        
        // $out .= '<textarea name="content" class="edit textarea mt-4" autofocus>';
        // $out .= $this->get_companion_content( $type );
        // $out .= '</textarea>';
        
        $list = $this->get_list_companions( 'competencies' );
        
        // p($arr);
        
        foreach ( $list as $item ) {
            
            $out .= '<div class="col pt-2 pb-2 mt-3">';
            // $out .= '<a href="' . get_permalink($item['id']) . '">' . $item['title'] . '</a>';
            $out .= '<a href="' . mif_mr_opop_core::get_opop_url() . 'competencies/' . $item['id'] . '">' . $item['title'] . '</a>';
            $out .= '</div>';
            
        }
        
        // p($arr);
        $this->get_all_arr();
        
        return apply_filters( 'mif_mr_show_list_competencies', $out );
    }
    
    
    
    //
    // 
    //
    
    public function show_competencies()
    {
        global $wp_query;
        if ( ! isset( $wp_query->query_vars['id'] ) ) return;
        
        $comp_id = $wp_query->query_vars['id'];
        $opop_id = mif_mr_opop_core::get_opop_id();

        global $tree;
        
        $out = '';
        $f = true;

        // p($tree['content']['competencies']['data']);

        // foreach ( $tree['content']['competencies']['data'] as $item ) {

        if ( isset( $tree['content']['competencies']['data'][$comp_id] ) ) {

            $item = $tree['content']['competencies']['data'][$comp_id];

            // if ( $item['comp_id'] != $comp_id ) continue;
            
            $out .= '<h4 class="mb-6 mt-5">' . $item['name'] . '</h4>';

            $out .= '<div class="text-end">';
            $out .= '<small><a href="#" id="bnt-show">Показать всё</a> | <a href="#" id="bnt-roll-up">Свернуть</a></small>';
            $out .= '</div>';

            $out .= '<div class="container no-gutters">';
            // $out .= '<div class="container no-gutters bg-light pt-5 pb-5 mt-5 mb-5">';

            foreach ( $item['data'] as $item2 ) {
                
                if ( $f ) $out .= '<span>';
                
                $out .= '<div class="row mb-3 mt-3">';
                $out .= '<div class="col-11 mr-gray p-3 fw-bolder">';
                $out .= $item2['name'];
                $out .= '</div>';

                if ( $f ) $out .= '<div class="col mr-gray p-3 text-end">';
                if ( $f ) $out .= '<i class="fas fa-spinner fa-spin d-none"></i> ';
                if ( $f ) $out .= '<a href="#" class="edit" data-sub="' . $item2['sub_id'] . '"><i class="fa-regular fa-pen-to-square"></i></a>';
                if ( $f ) $out .= '</div>';
                $out .= '</div>';
                
                $out .= $this->show_competencies_sub( $item2['sub_id'], $comp_id, $opop_id );
                
                if ( $f ) $out .= '</span>';
                
                // foreach ( $item2['data'] as $item3 ) {
                    
                //     $out .= '<div class="row">';
                    
                //     $out .= '<div class="col col-2 col-md-1 fw-bolder">';
                //     // $out .= '@';
                //     $out .= $item3['name'];
                //     $out .= '</div>';
                    
                //     $out .= '<div class="col mb-3">';
                //     $out .= $item3['descr'];
                //     $out .= '</div>';
                    
                //     $out .= '</div>';
                    
                //     foreach ( $item3['indicators'] as $key4 => $item4 ) {
                        
                //         $out .= '<div class="row coll" style="display: none;">';
                        
                //         $out .= '<div class="col col-2 col-md-1">';
                //         // $out .= '@';
                //         $out .= '</div>';
                        
                //         $out .= '<div class="col p-1 pl-3 m-3 mr-gray fst-italic">';
                //         $out .= ( isset( $this->name_indicators[$key4] ) ) ? $this->name_indicators[$key4] : 'default';
                //         $out .= '</div>';
                        
                //         $out .= '</div>';
                        
                //         $out .= '<div class="row coll" style="display: none;">';
                //         $out .= '<div class="col col-2 col-md-1">';
                //         $out .= '</div>';
                        
                //         $out .= '<div class="col">';
                //         foreach ( $item4 as $item5 ) $out .= '<p class="m-2">' . $item5 . '</p>';
                //         $out .= '</div>';
                //         $out .= '</div>';
                        
                //     }
                    
                // }
                
                // p($item2);
                
            }
            
            $out .= '</div>';
            
        }
        
        if ( $f ) $out .= '<input type="hidden" name="opop" value="' . $opop_id . '">';
        if ( $f ) $out .= '<input type="hidden" name="comp" value="' . $comp_id . '">';
        if ( $f ) $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';
        
        


        // $arr = $this->get_arr( $wp_query->query_vars['id'] );



        // $out .= '<textarea name="content" class="edit textarea mt-4" autofocus>';
        // $out .= $this->get_companion_content( $type );
        // $out .= '</textarea>';
        
        // $arr = $this->get_list_companions( 'competencies' );
        
        // p($arr);

        // foreach ( $arr as $item ) {

        //     $out .= '<div class="col pt-2 pb-2 mt-3">';
        //     // $out .= '<a href="' . get_permalink($item['id']) . '">' . $item['title'] . '</a>';
        //     $out .= '<a href="' . $item['id'] . '">' . $item['title'] . '</a>';
        //     $out .= '</div>';

        // }

        // p($arr);


        return apply_filters( 'mif_mr_show_competencies', $out );
    }
    
    
    
    
    //
    // 
    //
    
    public function show_competencies_sub( $sub_id, $comp_id, $opop_id = NULL )
    {
        global $tree;

        $out = '';        
        if ( empty( $opop_id ) ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        if ( empty( $tree['content']['competencies']['data'][$comp_id]['data'][$sub_id] ) ) return;
        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) return $this->get_edit( $sub_id, $comp_id, $opop_id );
        
        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'save' ) $this->get_save( $sub_id, $comp_id, $opop_id );

        $item2 = $tree['content']['competencies']['data'][$comp_id]['data'][$sub_id];
        $style = ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'save' ) ? '' : 'style="display: none;"';


        $out .= '<div class="content-ajax">';

        foreach ( $item2['data'] as $item3 ) {
            
            $out .= '<div class="row">';
            
            $out .= '<div class="col col-2 col-md-1 fw-bolder">';
            // $out .= '@';
            $out .= $item3['name'];
            $out .= '</div>';
            
            $out .= '<div class="col mb-3">';
            $out .= $item3['descr'];
            $out .= '</div>';
            
            $out .= '</div>';
            
            foreach ( $item3['indicators'] as $key4 => $item4 ) {
                
                $out .= '<div class="row coll"' . $style . '>';
                
                $out .= '<div class="col col-2 col-md-1">';
                // $out .= '@';
                $out .= '</div>';
                
                $out .= '<div class="col p-1 pl-3 m-3 mr-gray fst-italic">';
                $out .= ( isset( $this->name_indicators[$key4] ) ) ? $this->name_indicators[$key4] : 'default';
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
        
        $out .= '</div>';
        // p($item2);
        
        
        
        return apply_filters( 'mif_mr_show_competencies_sub', $out, $sub_id, $comp_id, $opop_id );
    }
    
    
    
    public function get_edit( $sub_id, $comp_id, $opop_id )
    {
        // ####!!!!!

        $arr = $this->get_sub_arr( $comp_id );

        if ( isset( $arr[$sub_id] ) ) {

            $out = '';

            $out .= '<div class="content-ajax">';
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            $out .= '@';
            $out .= '</div>';
            $out .= '</div>';
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';
            $out .= '<textarea name="content[' . $sub_id . ']" class="edit textarea content" autofocus>';
            $out .= $arr[$sub_id];
            $out .= '</textarea>';
            $out .= '</div>';
            $out .= '</div>';
            
            $out .= '<div class="row">';
            $out .= '<div class="col p-0">';

            $out .= '<button type="button" class="btn btn-primary mt-4 mb-4 mr-3 save" data-sub="' . $sub_id . '">Сохранить <i class="fas fa-spinner fa-spin d-none"></i></button>';
            $out .= '<button type="button" class="btn btn-light mt-4 mb-4 mr-3 cancel" data-sub="' . $sub_id . '">Отмена <i class="fas fa-spinner fa-spin d-none"></i></button>';

            // $out .= '<input type="submit" name="save" value="Сохранить" class="btn btn-primary mt-4 mb-4 mr-3" />';
            // $out .= '<input type="button" onclick="location.href=\'' . '2' . '\';"  value="Отмена" class="btn btn-light mt-4 mb-4 mr-3" />';

            $out .= '</div>';
            $out .= '</div>';
            $out .= '</div>';
            
        }

        return apply_filters( 'mif_mr_companion_get_edit', $out, $sub_id, $comp_id, $opop_id );
    }

   
   
   
    public function get_save( $sub_id, $comp_id, $opop_id )
    {
        // ####!!!!!

        $res = false;

        $arr = $this->get_sub_arr( $comp_id );

        $arr[$sub_id] = sanitize_textarea_field( $_REQUEST['content'] );
        // p($_REQUEST);
            



        $res = wp_update_post( array(
            'ID' => $comp_id,
            'post_content' => implode( "\n", $arr ),
            ) );
                

       
        // global $messages;
        
        // $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 102 (' . $type . ')', 'danger' );
        
        if ( $res ) {
            
            global $tree;
            global $mif_mr_opop;
            
            $tree = array();
            $tree = $mif_mr_opop->get_tree();
            
        }
        











        return $res;
    }



    public function get_sub_arr( $comp_id )
    {
        $arr = array();

        $post = get_post( $comp_id );
        $data = $this->get_begin_data( $post );
        
        $n = 0;

        $arr2 = preg_split( '/\\r\\n?|\\n/', $data );
        $arr2 = array_map( 'strim', $arr2 );

        foreach ( $arr2 as $item ) {
            if ( preg_match( '/^=/', $item ) ) {
                $arr[] = $item . "\n";
            } else {
                $arr[ array_key_last( $arr ) ] .= $item . "\n";
            }
        }


        // p($arr);

        return apply_filters( 'mif_mr_companion_get_sub_arr', $arr, $sub_id, $comp_id, $opop_id );
    }



    // //
    // // 
    // //
    
    // public function show_competencies2()
    // {
    //     global $wp_query;
    //     if ( ! isset( $wp_query->query_vars['id'] ) ) return;
        
    //     $comp_id = $wp_query->query_vars['id'];

    //     global $tree;
        
    //     $out = '';
    //     $f = true;

    //     // p($tree['content']['competencies']);

    //     foreach ( $tree['content']['competencies']['data'] as $item ) {

    //         // if ( $item['comp_id'] != $wp_query->query_vars['id'] ) continue;
    //         if ( $item['comp_id'] != $comp_id ) continue;
            
    //         $out .= '<h4 class="mb-6 mt-5">' . $item['name'] . '</h4>';

    //         $out .= '<div class="text-end">';
    //         $out .= '<small><a href="#" id="bnt-show">Показать всё</a> | <a href="#" id="bnt-roll-up">Свернуть</a></small>';
    //         $out .= '</div>';

    //         $out .= '<div class="container no-gutters">';
    //         // $out .= '<div class="container no-gutters bg-light pt-5 pb-5 mt-5 mb-5">';

    //         foreach ( $item['data'] as $item2 ) {
                
    //             $out .= '<div class="row mb-3 mt-3">';
    //             $out .= '<div class="col-11 mr-gray p-3 fw-bolder">';
    //             $out .= $item2['name'];
    //             $out .= '</div>';

    //             if ( $f ) $out .= '<div class="col mr-gray p-3 text-end">';
    //             if ( $f ) $out .= '<a href="#" class="edit" data-sub="' . $item2['sub_id'] . '"><i class="fa-regular fa-pen-to-square"></i></a>';
    //             if ( $f ) $out .= '</div>';
    //             $out .= '</div>';
                
    //             foreach ( $item2['data'] as $item3 ) {
                    
    //                 $out .= '<div class="row">';
                    
    //                 $out .= '<div class="col col-2 col-md-1 fw-bolder">';
    //                 // $out .= '@';
    //                 $out .= $item3['name'];
    //                 $out .= '</div>';
                    
    //                 $out .= '<div class="col mb-3">';
    //                 $out .= $item3['descr'];
    //                 $out .= '</div>';
                    
    //                 $out .= '</div>';
                    
    //                 foreach ( $item3['indicators'] as $key4 => $item4 ) {
                        
    //                     $out .= '<div class="row coll" style="display: none;">';
                        
    //                     $out .= '<div class="col col-2 col-md-1">';
    //                     // $out .= '@';
    //                     $out .= '</div>';
                        
    //                     $out .= '<div class="col p-1 pl-3 m-3 mr-gray fst-italic">';
    //                     $out .= ( isset( $this->name_indicators[$key4] ) ) ? $this->name_indicators[$key4] : 'default';
    //                     $out .= '</div>';
                        
    //                     $out .= '</div>';
                        
    //                     $out .= '<div class="row coll" style="display: none;">';
    //                     $out .= '<div class="col col-2 col-md-1">';
    //                     $out .= '</div>';
                        
    //                     $out .= '<div class="col">';
    //                     foreach ( $item4 as $item5 ) $out .= '<p class="m-2">' . $item5 . '</p>';
    //                     $out .= '</div>';
    //                     $out .= '</div>';
                        
    //                 }
                    
    //             }
                
    //             // p($item2);
                
    //         }
            
    //         $out .= '</div>';
            
    //     }
        
    //     if ( $f ) $out .= '<input type="hidden" name="opop" value="' . mif_mr_opop_core::get_opop_id() . '">';
    //     if ( $f ) $out .= '<input type="hidden" name="comp" value="' . $comp_id . '">';
    //     if ( $f ) $out .= '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce( 'mif-mr' ) . '">';
        
        


    //     // $arr = $this->get_arr( $wp_query->query_vars['id'] );



    //     // $out .= '<textarea name="content" class="edit textarea mt-4" autofocus>';
    //     // $out .= $this->get_companion_content( $type );
    //     // $out .= '</textarea>';
        
    //     // $arr = $this->get_list_companions( 'competencies' );
        
    //     // p($arr);

    //     // foreach ( $arr as $item ) {

    //     //     $out .= '<div class="col pt-2 pb-2 mt-3">';
    //     //     // $out .= '<a href="' . get_permalink($item['id']) . '">' . $item['title'] . '</a>';
    //     //     $out .= '<a href="' . $item['id'] . '">' . $item['title'] . '</a>';
    //     //     $out .= '</div>';

    //     // }

    //     // p($arr);


    //     return apply_filters( 'mif_mr_show_competencies', $out );
    // }
    


    
    //
    // 
    //
    
    public function get_all_arr( $opop_id = NULL )
    {
        if ( $opop_id === NULL ) $opop_id = mif_mr_opop_core::get_opop_id();
        
        $arr = array();
        $list = $this->get_list_companions( 'competencies', $opop_id );
       
        foreach ( $list as $item ) {

            // p($item['id']);
            // $arr = array_merge( $arr, $this->get_competencies_arr( $item['id'] ) );
            $arr2 = $this->get_arr( $item['id'] );
            $arr[$arr2['comp_id']] = $arr2;
            
            // p($arr2['comp_id']);
            // $arr[] = $this->get_arr( $item['id'] );

        }

        // p(current($arr));
        // p(current(current($arr)));
        // p($arr);
        return apply_filters( 'mif_mr_get_all_arr', $arr );

    }



    //
    // 
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
        


    // //
    // // 
    // //
    
    // public function get_competencies_arr2( $id )
    // {
    //     $arr = array();
       
    //     $post = get_post($id);

    //     // p($post->post_title);
    //     // p($post->post_content);
        
    //     $data = '== ' . $post->post_title . "\n";
    //     $data .= "= default\n";
    //     $data .= $post->post_content;
        
    //     $p = new parser();
    //     // $arr = $p->get_arr( $data, array( 'section' => 'competencies', 'att_parts' => false, 'default' => true ) );
    //     $arr = $p->get_arr( $data, array( 'section' => $id, 'att_parts' => false, 'default' => true ) );
        
    //     // p($arr);

    //     foreach ( $arr as $key => $item )
    //     foreach ( $item as $key2 => $item2 ) {
            
    //         // p($item2);
    //         if ( isset( $item2['parts']) )
    //         foreach ( (array) $item2['parts'] as $key3 => $item3 ) {
        
    //             $n = 0;
    //             $arr2 = array();
    //             // $arr3 = array();

    //             foreach ( $item3['data'] as $key4 => $item4 ) {
            
    //                 // if ( empty( $item4 ) ) continue;

    //                 // if ( preg_match( '/^.+-\d+.\s/', $item4, $m ) ) {
    //                 if ( preg_match( '/(^.+-\d+.\s+)(.*)/', $item4, $m ) ) {

    //                     $arr2[] = array(
    //                                     'name' => $m[1],
    //                                     'desc' => $m[2],
    //                                 );
    //                     $n = 0;
    //                     continue;
    //                     // p($m);
    //                 } 
                    
    //                 // p($arr2);
    //                 // p(array_key_last($arr2));
    //                 $arr2[array_key_last($arr2)]['indicators'][$n++] = array_map( 'trim', explode( "\n", $item4 ) );
    //                 // p($item4);
    //                 // 

    //             }

    //             // p( $arr[$key][$key2]['parts'][$key3]['data'] );
                
    //             if ( ! empty( $arr2 ) ) $arr[$key][$key2]['parts'][$key3]['data'] = $arr2;
    //             if ( empty( $arr[$key][$key2]['parts'][$key3]['data'] ) ) 
    //             unset( $arr[$key][$key2]['parts'][$key3] );
    //             // if ( ! empty( $arr2 ) ) 
    //                 // p($arr2);

    //         }
        
    //     }
    //     // p($arr);
    //     // $arr = current( $arr );

    //     return apply_filters( 'mif_mr_get_competencies_arr', $arr, $id );
    // }
     


    public function get_begin_data( $post )
    {
        // $data = '== ' . $post->post_title . "\n";
        $data = '';
        if ( ! preg_match( '/^=/', $post->post_content ) ) $data .= "= " . $this->sub_default . "\n";
        $data .= $post->post_content;
        return $data;
    }


    
    private $name_indicators = array();
    private $sub_default = '';




    // //
    // // Форма редактирования связанной записи (список дисциплин, матрица компетенций, учебный план или др.) 
    // //

    // public function companion_edit( $type = 'courses' )
    // {
    //     $out = '';

    //     $out .= '<textarea name="content" class="edit textarea mt-4" autofocus>';
    //     $out .= $this->get_companion_content( $type );
    //     $out .= '</textarea>';

    //     return apply_filters( 'mif_mr_core_companion_edit', $out );
    // }
        


    // //
    // // ID связанной записи 
    // //

    // public function get_companion_id( $type = 'courses', $opop_id = NULL )
    // {
    //     if ( $opop_id === NULL ) $opop_id = get_the_ID();
        
    //     $posts = get_posts( array(
    //         'post_type'     => $type,
    //         'post_status'   => 'publish',
    //         'post_parent'   => $opop_id,
    //         ) );
            
    //     $companion_id = ( isset( $posts[0]->ID ) ) ? $posts[0]->ID : NULL;
        
    //     return apply_filters( 'mif_mr_core_get_companion_id', $companion_id, $type, $opop_id );
    // }
    
    
    
    // //
    // // Содержание связанной записи
    // //
    
    // public function get_companion_content( $type = 'courses', $opop_id = NULL )
    // {
    //     $content = '';
        
    //     if ( $opop_id === NULL ) $opop_id = get_the_ID();
                
    //     if ( $this->get_companion_id( $type, $opop_id ) !== NULL ) {
            
    //         $post = get_post( $this->get_companion_id( $type, $opop_id ) );
    //         $content = $post->post_content;

    //     }

    //     return apply_filters( 'mif_mr_core_get_companion_content', $content, $type );
    // }




    // // 
    // //  
    // //
    
    // public function save( $type = 'courses' )
    // {
    //     global $mif_mr_opop;
        
    //     if ( ! isset( $_REQUEST['save'] )) return false;
        
    //     if ( $this->get_companion_id( $type, $mif_mr_opop->get_opop_id() ) === NULL) {
            
    //         $res = wp_insert_post( array(
    //             'post_title'    => $mif_mr_opop->get_opop_title() . ' (' . $mif_mr_opop->get_opop_id() . ')',
    //             'post_type'     => $type,
    //             'post_status'   => 'publish',
    //             'post_parent'   => $mif_mr_opop->get_opop_id(),
    //             'post_content'  => sanitize_textarea_field( $_REQUEST['content'] ),
    //             ) );
                
    //     } else {
            
    //         $res = wp_update_post( array(
    //             'ID' => $this->get_companion_id( $type, $mif_mr_opop->get_opop_id() ),
    //             'post_content' => sanitize_textarea_field( $_REQUEST['content'] ),
    //             ) );
                
    //     }
        
    //     global $messages;
        
    //     $messages[] = ( $res ) ? array( 'Сохранено', 'success' ) : array( 'Какая-то ошибка. Код ошибки: 102 (' . $type . ')', 'danger' );
        
    //     if ( $res ) {
            
    //         $tree = array();
    //         $tree = $mif_mr_opop->get_tree();
            
    //     }
        
    //     return $res;
    // }
    
    
    
}


?>