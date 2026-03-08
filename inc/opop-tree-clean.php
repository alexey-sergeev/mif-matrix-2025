<?php

//
// Ядро ОПОП
// 
//


defined( 'ABSPATH' ) || exit;


class mif_mr_opop_tree_clean extends mif_mr_opop_tree_raw {
        
    
    function __construct()
    {
        parent::__construct();

        global $tree;
        $tree = $this->get_tree_clean( $tree ); 

        // p($tree);
    }
    



    // 
    // Tree clean
    // 
    
    public function get_tree_clean( $t = array() )
    {
        
        $t['content']['courses']['clean'] = $this->make_courses_clean();
        


        return apply_filters( 'mif_mr_core_opop_get_tree_clean', $t );
    }



    // 
    // Сделать дисциплины чистыми
    // 
    
    private function make_courses_clean()
    {
        global $tree;

        
        
        $arr = array();
        
        foreach ( $tree['content']['courses']['index'] as $key => $item ) {
        // foreach ( $tree['content']['courses']['index'] as $item ) {

            if ( empty( $item['course_id'] ) ) continue;
            
            $key2 = $item['course_id'];

            $c = new cmp( ( isset( $tree['content']['matrix']['data'][$key] ) ) ? $tree['content']['matrix']['data'][$key] : '' );
            // $k = $item['course_id'];

            $arr[$key2]['course_id'] = $item['course_id'];
            $arr[$key2]['name'] = $key;


            // Компетенции

            if ( ( isset( $tree['content']['matrix']['data'][$key] ) ) ) {

                $arr[$key2]['cmp'] = $tree['content']['matrix']['data'][$key];
                $arr[$key2]['cmp_raw'] = $c->get_cmp( $tree['content']['matrix']['data'][$key] );

                foreach ( $tree['content']['matrix']['data'][$key] as $i ) {

                    $arr[$key2]['cmp_descr'][] = ( isset( $tree['content']['competencies']['data'][$i] ) ) ?
                            array( 
                                'name' => $tree['content']['competencies']['data'][$i]['name'],
                                'descr' => $tree['content']['competencies']['data'][$i]['descr'],
                                'category' => $tree['content']['competencies']['data'][$i]['category'],
                                ) :
                            array( 'name' => '', 'descr' => '', 'category' => '' );
                }


            }
            

            // Семестры (академические часы, конроль)

            if ( isset( $tree['content']['curriculum']['data'][$key]['semesters'] ) ) {

                $arr[$key2]['semesters'] = $tree['content']['curriculum']['data'][$key]['semesters'];
                $arr[$key2]['exam'] = $this->get_exam( $tree['content']['curriculum']['data'][$key]['semesters'] );

            }


            // Cтатистика по всем семестрам 
            
            if ( isset( $tree['content']['curriculum']['data'][$key]['course_stat'] ) ) {

                $arr[$key2]['hours'] = $tree['content']['curriculum']['data'][$key]['course_stat'];
                $arr[$key2]['hours_z'] = $this->get_hours_z( $tree['content']['curriculum']['data'][$key]['course_stat'] );
                $arr[$key2]['hours_ze'] = $this->get_hours_ze( $tree['content']['curriculum']['data'][$key]['course_stat'] );
                $arr[$key2]['hours_raw'] = $this->get_hours_raw( $tree['content']['curriculum']['data'][$key]['course_stat'] );
                
            }


            // Сводные индикаторы

            $d = $tree['content']['lib-courses']['data'][$item['course_id']]['data'];
            
            if ( ! empty( $dd = $this->get_outcomes( $d, 'z' ) ) ) $arr[$key2]['outcomes']['z'] = $dd;
            if ( ! empty( $dd = $this->get_outcomes( $d, 'u' ) ) ) $arr[$key2]['outcomes']['u'] = $dd;
            if ( ! empty( $dd = $this->get_outcomes( $d, 'v' ) ) ) $arr[$key2]['outcomes']['v'] = $dd;
            
            // $arr[$key2]['outcomes']['z'] = $this->get_outcomes( $d, 'z' );
            // $arr[$key2]['outcomes']['u'] = $this->get_outcomes( $d, 'u' );
            // $arr[$key2]['outcomes']['v'] = $this->get_outcomes( $d, 'v' );
            

            // Содержание

            $arr[$key2]['data']['content']['target'] = ( isset( $d['content']['target'] ) ) ? $d['content']['target'] : '';
            $arr[$key2]['data']['content']['parts'] = array();

            if ( isset( $d['content']['parts'] ) ) {

                $hours_arr = $this->get_part_hours( $tree['content']['curriculum']['data'][$key]['course_stat'], $d['content']['parts'] );
                $cmp_total_arr = array();
                $hours_total_arr = array();
                
                foreach ( $d['content']['parts'] as $k => $i ) {
                    
                    // p($k);
                    // p($i);
                    // p($i['cmp']);
                
                    $arr[$key2]['data']['content']['parts'][$k]['name'] = $i['name'];
                    $arr[$key2]['data']['content']['parts'][$k]['cmp'] = $c->get_cmp( $i['cmp'] );
                    $cmp_total_arr[] = $c->get_cmp( $i['cmp'] );
                    $arr[$key2]['data']['content']['parts'][$k]['content'] = $i['content'];
                    $arr[$key2]['data']['content']['parts'][$k]['outcomes'] = $i['outcomes'];
                    $arr[$key2]['data']['content']['parts'][$k]['hours'] = $hours_arr[$k];
                    $hours_total_arr[] = $hours_arr[$k];
                    $arr[$key2]['data']['content']['parts'][$k]['hours_z'] = array_sum( $hours_arr[$k] );
                    $arr[$key2]['data']['content']['parts'][$k]['hours_raw'] = implode( ', ', $hours_arr[$k] );

                }

                $hours_total = $this->get_hours_total( $hours_total_arr );
                $arr[$key2]['data']['content']['parts_stat'] = array( 
                                                'cmp' => $c->get_cmp( implode( ', ', $cmp_total_arr ) ) ,
                                                'hours' => $hours_total,
                                                'hours_raw' => implode( ', ', $hours_total ),
                                                'hours_z' => array_sum( $hours_total ),
                                                'hours_ze' => array_sum( $hours_total ) / 36,
                                            );
            
            }


            // Оценочные средства

            $arr[$key2]['data']['evaluations'] = array();

            $n = 0;
            $cmp_total_arr = array();

            foreach ( (array) $arr[$key2]['semesters'] as $k => $i ) {

                $rating = 0;
                $cmp_arr = array();

                if ( isset( $d['evaluations'][$n]['data'] ) ) {

                    foreach ( $d['evaluations'][$n]['data'] as $k2 => $i2 ) {

                        $cmp = $c->get_cmp( $i2['att']['cmp'] );

                        $arr[$key2]['data']['evaluations'][$k]['data'][] = array(
                            'name' => $i2['name'],
                            'att' => array( 
                                'rating' => $i2['att']['rating'],
                                'cmp' => $cmp,
                            )
                        ); 

                        $rating += $i2['att']['rating'];
                        $cmp_arr[] = $cmp;
                        $cmp_total_arr[] = $cmp;
                        
                    }

                    $arr[$key2]['data']['evaluations'][$k]['stat']['rating'] = $rating;
                    $arr[$key2]['data']['evaluations'][$k]['stat']['cmp'] = $c->get_cmp( implode( ', ', $cmp_arr ) );

                } 


                if ( isset( $d['evaluations'][$n+1]['data'] ) ) $n++;

                // p($k);
                // p($i);

            }

            $arr[$key2]['data']['evaluations_stat'] = array( 'cmp' => $c->get_cmp( implode( ', ', $cmp_total_arr ) ) );


            // p($d['evaluations']);
            // p($tree['content']['lib-courses']['data'][$item['course_id']]['data']);

            // p($item);

            $arr[$key2]['data']['biblio'] = $d['biblio'];
            $arr[$key2]['data']['it'] = $d['it'];
            $arr[$key2]['data']['mto'] = $d['mto'];
            $arr[$key2]['data']['guidelines'] = $d['guidelines'];
            $arr[$key2]['data']['authors'] = $d['authors'];

        }


        // p($arr);

        return apply_filters( 'mif_mr_core_opop_make_courses_clean', $arr );
    }




    private function get_hours_total( $a ) 
    {
        $b = array( 'lec' => 0, 'lab' => 0, 'prac' => 0, 'srs' => 0, 'exam' => 0 );
        foreach ( $a as $i ) foreach ( $i as $k => $i2 ) $b[$k] += $i2; 

        return $b;
    }



    private function get_outcomes( $d, $k = 'z' ) 
    {
        if ( empty( $d['content']['parts'] ) ) return;
        
        $a = array();
        foreach ( $d['content']['parts'] as $i ) foreach( (array) $i['outcomes'][$k] as $i2 ) $a[] = trim( $i2, ',.;: ');
        $a = array_unique( $a );
        
        // p($a);

        return $a;
    }





    private function get_exam( $a ) 
    {
        $b = array();

        foreach ( $a as $k => $i ) {
            $e = ( isset( $i['att'] ) ) ? ' (' . implode( ', ', $i['att'] ) . ')' : '';
            $b[] = $k . $e;
        }

        return implode( ', ', $b );
    }




    private function get_hours_z( $a ) 
    {
        if ( isset( $a['att'] ) ) unset( $a['att'] );
        return array_sum( $a );
    }


    
    private function get_hours_ze( $a ) 
    {
        if ( isset( $a['att'] ) ) unset( $a['att'] );
        return array_sum( $a ) / 36;
    }




    private function get_hours_raw( $a ) 
    {
        if ( isset( $a['att'] ) ) unset( $a['att'] );
        return implode( ', ', $a );
    }




    private function get_part_hours( $ht, $d ) 
    {

        $hours_arr = array();
        $hours_arr[0] = array( 'lec' => 0, 'lab' => 0, 'prac' => 0, 'srs' => 0, 'exam' => 0 );

        $hs = array( 'lec' => 0, 'lab' => 0, 'prac' => 0, 'srs' => 0, 'exam' => 0 );
        $hf = array( 'lec' => 0, 'lab' => 0, 'prac' => 0, 'srs' => 0, 'exam' => 0 );
        $hd = array();

        foreach ( $d as $k => $i ) foreach ( $i['hours'] as $k2 => $i2 ) $hs[$k2] += $i2;
        foreach ( $d as $k => $i ) foreach ( $i['hours'] as $k2 => $i2 ) $hours_arr[$k][$k2] = ( $hs[$k2] !== 0 ) ? floor( $i2 * $ht[$k2] / $hs[$k2] ) : 0;
        foreach ( $hours_arr as $k => $i ) foreach ( $i as $k2 => $i2 ) $hf[$k2] += $i2;
        foreach ( $hf as $k => $i ) $hd[$k] = $ht[$k] - $hf[$k];
        foreach ( $hd as $k => $i ) $hours_arr[0][$k] += $i; 

        // p('@');
        // p($hours_arr);
        // p($hs);
        // p($ht);
        // p($hf);
        // p($hd); 
    
        return $hours_arr;

    }














    // // 
    // // Tree
    // // 
    
    // public function get_tree()
    // {
    //     $t = $this->get_tree_clean(); 
    //     return apply_filters( 'mif_mr_core_opop_get_tree', $t );
    // }
    
    
    
    // // 
    // // Tree clean
    // // 
    
    // public function get_tree_clean()
    // {
        
    //     $t = $this->get_tree_raw(); 
        
    //     // $t['content']['competencies']['from_id'] = $this->get_opop_id();
    //     // $t['content']['competencies']['data'] = mif_mr_comp::set_comp_to_tree( $t );
        
    //     $t['content']['courses']['index'] = mif_mr_set_core::set_courses_to_tree( $t, $this->opop_id );
    //     $t['content']['competencies']['data'] = mif_mr_set_core::set_comp_to_tree( $t );
    //     $t['content']['references']['data'] = mif_mr_set_core::set_references_to_tree( $t );
        
    //     return apply_filters( 'mif_mr_core_opop_get_tree_clean', $t );
    // }



    // // 
    // // Tree RAW
    // // 
    
    // public function get_tree_raw()
    // {
    //     // global $post;
    //     // global $tree;
    //     // p($post);
    //     $post = get_post( $this->current_opop_id ); 
        
    //     if ( $post->post_type != 'opop' ) return;
        
    //     $this->opop_id = $post->ID;

    //     // global $t;
        
    //     $t = array();
        
    //     // $tree['main']['title'] = $post->post_title;
    //     // $tree['main']['id'] = $post->ID;
        
    //     // $t = array_merge( $tree, $this->get_param_and_meta() );
    //     // $t = $this->get_param_and_meta();
    //     // 
    //     // p( $post );
    //     // p( WP_Post::get_instance( 176 ) );
    //     // p( $tree );
    //     $this->parents_arr = array();
    //     $this->get_parents_arr( $this->current_opop_id );
    //     $this->parents_arr = array_reverse( $this->parents_arr );
    //     // p( $this->parents_arr );
        
        
    //     foreach ( $this->parents_arr as $item ) {
            
    //         // p($item);
    //         // p($this->get_param_and_meta( $item ));
    //         // p($this->get_courses( $item ));
    //         // $t = array_replace_recursive( $t, $this->get_param_and_meta( $item ) ); 
    //         // $t = array_replace_recursive( $t, $this->get_companion( 'courses', $item ) ); 
    //         // $t = array_replace_recursive( $t, $this->get_companion( 'matrix', $item ) ); 
    //         // $t = array_replace_recursive( $t, $this->get_companion( 'curriculum', $item ) ); 
            
    //         $t = $this->arr_replace( $t, $this->get_param_and_meta( $item ) ); 
    //         $t = $this->arr_replace( $t, $this->get_companion( 'courses', $item ) ); 
    //         $t = $this->arr_replace( $t, $this->get_companion( 'matrix', $item ) ); 
    //         $t = $this->arr_replace( $t, $this->get_companion( 'curriculum', $item ) ); 
    //         $t = $this->arr_replace( $t, $this->get_companion( 'attributes', $item ) ); 
    //         $t = array_replace_recursive( $t, $this->get_companion( 'lib-competencies', $item ) ); 
    //         $t = array_replace_recursive( $t, $this->get_companion( 'lib-courses', $item ) ); 
    //         $t = array_replace_recursive( $t, $this->get_companion( 'lib-references', $item ) ); 
    //         $t = $this->arr_replace( $t, $this->get_companion( 'set-competencies', $item ) ); 
    //         $t = $this->arr_replace( $t, $this->get_companion( 'set-courses', $item ) ); 
    //         $t = $this->arr_replace( $t, $this->get_companion( 'set-references', $item ) ); 
    //         // 
            
    //         // p($this->get_companion( 'competencies', $item ));

    //     }




    //     return apply_filters( 'mif_mr_core_opop_get_tree_raw', $t );
    // }
    
    
    
    
    
    // private function arr_replace( $arr, $arr2 )
    // {
    //     foreach ( $arr2 as $key2 => $item2 ) {
    //         foreach ( (array) $item2 as $key3 => $item3 )
    //             if ( isset( $item3['data'] ) && isset( $item3['from_id'] ) ) {

    //                 $arr[$key2][$key3]['from_id'] = $item3['from_id'];
    //                 $arr[$key2][$key3]['data'] = $item3['data'];

    //                 unset( $arr2[$key2][$key3]['from_id'] );
    //                 unset( $arr2[$key2][$key3]['data'] );

    //             }

    //     }

    //     $arr = array_replace_recursive( $arr, $arr2 ); 

    //     return $arr;
    // }
 
 
 
    // private function get_parents_arr( $opop_id )
    // {
    //     $t = $this->get_param_and_meta( $opop_id );
        
    //     // p($t);
    //     // p($t['param']['parents']['data']);
    //     // p($t['main']['id']); 
    //     // $this->parents_arr[] = $t['main']['title'];
    //     if ( ! isset( $t['main']['id'] ) ) return;
        
    //     $this->parents_arr[] = $t['main']['id'];
        
    //     if ( ! isset( $t['param']['parents']['data'] ) ) return; 
        
    //     foreach ( $t['param']['parents']['data'] as $item ) {
            
    //         // p($item);
    //         $this->get_parents_arr( $item );
            
            
    //     }
        
    // }
    

    
    
    // // 
    // // 
    // // 
    
    // private function get_companion( $part = 'courses', $opop_id = NULL )
    // {
    //     if ( $opop_id === NULL ) $opop_id = get_the_ID();
    //     // p($opop_id);
    //     $c = new mif_mr_part_companion();

    //     switch ( $part ) {
                
    //         case 'courses':
    //             // p('@ '.$opop_id);
    //             $m = new modules( $c->get_companion_content( 'courses', $opop_id ) );
    //             $data = $m->get_arr();
    //             // $data = $m->get_courses();
    //             // $data = $m->get_tree();
    //         break;
                
    //         case 'matrix':
    //             $m = new matrix( $c->get_companion_content( 'matrix', $opop_id ) );
    //             $data = $m->get_arr();
    //         break;
                    
    //         case 'curriculum':
    //             $m = new curriculum( $c->get_companion_content( 'curriculum', $opop_id ) );
    //             $data = $m->get_arr();
    //         break;
            
    //         case 'attributes':
    //             $m = new attributes( $c->get_companion_content( 'attributes', $opop_id ) );
    //             $data = $m->get_arr();
    //         break;
            
    //         case 'lib-competencies':
    //             // $m = new curriculum( $c->get_companion_content( 'curriculum', $opop_id ) );
    //             // $data = $m->get_arr();
    //             $m = new mif_mr_lib_competencies();
    //             $data = $m->get_all_arr( $opop_id );
    //             // p($data);
    //         break;
                
    //         case 'lib-courses':
    //             // $m = new curriculum( $c->get_companion_content( 'curriculum', $opop_id ) );
    //             // $data = $m->get_arr();
    //             $m = new mif_mr_lib_courses();
    //             $data = $m->get_all_arr( $opop_id );
    //             // p($data);
    //         break;
                
    //         case 'lib-references':
    //             $m = new mif_mr_lib_references();
    //             $data = $m->get_all_arr( $opop_id );
    //         break;
                
    //         case 'set-competencies':
    //             // p('@');
    //             $m = new mif_mr_set_core();
    //             $data = $m->get_arr_competencies( $opop_id );
    //         break;
            
                
    //         case 'set-courses':
    //             // p('@');
    //             $m = new mif_mr_set_core();
    //             $data = $m->get_arr_courses( $opop_id );
    //         break;
            
    //         case 'set-references':
    //             // p('@');
    //             $m = new mif_mr_set_core();
    //             $data = $m->get_arr_references( $opop_id );
    //         break;
            


    //         default:
    //             $data = 'none';
    //         break;
        
    //     }
    
    //     // $t['content'][$part] = array(
    //     $t['content'][$part] = array(
    //                         'from_id' => $opop_id,
    //                         'data' => $data 
    //                     );
    //     // p($part);
    //     // p($data);
    //     return apply_filters( 'mif_mr_core_opop_get_companion', $t, $part, $opop_id );
    // }
    

    

    // private $opop_id = NULL;


    // // private function param_map_index()
    // // {
    // //     $arr = array();

    // //     // foreach ( $this->param_map as $item ) 
    // //     //     foreach ( (array) $item['key'] as $key => $item2 ) 
    // //     //         if ( $key != 0 ) $arr[ $item['key'][0] ][] = $item2;
        
    // //     foreach ( $this->param_map as $item )
    // //         foreach ( (array) $item['key'] as $item2 ) 
    // //             $arr[$item2] = $item['key'][0];
        
    // //     return $arr;    
    
    // // }


   
}

?>