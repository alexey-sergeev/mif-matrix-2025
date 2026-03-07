<?php

//
// Список дисциплин
// 
//


defined( 'ABSPATH' ) || exit;

// include_once dirname( __FILE__ ) . '/part-core.php';

class mif_mr_courses extends mif_mr_courses_list {
    
    function __construct()
    {

        parent::__construct();
        
    }
    

    
    // 
    // Показывает часть 
    // 

    public function the_show()
    {

        global $wp_query;

        if ( isset( $wp_query->query_vars['id'] ) ) {

            if ( $template = locate_template( 'mr-course.php' ) ) {
               
                load_template( $template, false );
    
            } else {
    
                load_template( dirname( __FILE__ ) . '/../templates/mr-course.php', false );
    
            }

        } else {

            if ( $template = locate_template( 'mr-part-courses.php' ) ) {
               
                load_template( $template, false );
    
            } else {
    
                load_template( dirname( __FILE__ ) . '/../templates/mr-part-courses.php', false );
    
            }
        
        }

    }
    
    


    
    
    // 
    // Показывает дисциплины
    // 
    
    public function get_course()
    {
        global $wp_query;
        $course_id = $wp_query->query_vars['id'];
    
        global $tree;
        $arr = $tree['content']['courses']['clean'][$course_id];

        // p($arr);

        $out = '';
        $n = 1;

        $out .= '<div class="container">';
        
        $out .= $this->row( $arr['name'], 'fw-bolder text-center mr-gray p-3' );
        $out .= $this->row( '<b>Компетенции: </b>' . $this->span( $this->q( $arr['cmp'] ) ), 'mt-4' );
        $out .= $this->row( '<b>Академические часы: </b>' . $this->span( $arr['hours_raw'] ) . ' (лек, лаб, пр, СРС, кон)');
        $out .= $this->row( '<b>Зачетных единиц: </b>' . $this->span( $arr['hours_ze'] ) );
        // $out .= $this->row( '<b>Общая трудоемкость: </b>' . $this->span( $arr['hours_ze'] ) );
        $out .= $this->row( '<b>Семестры: </b>' . $this->span( $arr['exam'] ) );


        $out .= $this->row( $n++ . '. Цель освоения дисциплины', 'fw-bolder mr-gray p-2 mt-6' );
        $out .= $this->row( $arr['data']['content']['target'], 'mt-4' );

        $out .= $this->row( $n++ . '. Планируемые результаты обучения', 'fw-bolder mr-gray p-2 mt-6' );
        $out .= $this->row( '<b>В результате изучения дисциплины обучающийся должен:</b>', 'mt-3' );
        $out .= $this->row( '<i>знать:</i>', 'mt-3' );
        // $out .= $this->row( '&mdash; ' . implode( ';<br />&mdash; ', $arr['outcomes']['z'] ) . '.', '' );
        $out .= $this->row( $this->mdash( $arr['outcomes']['z'] ) );
        $out .= $this->row( '<i>уметь:</i>', 'mt-3' );
        $out .= $this->row( $this->mdash( $arr['outcomes']['u'] ) );
        // $out .= $this->row( '&mdash; ' . implode( ';<br />&mdash; ', $arr['outcomes']['u'] ) . '.', '' );
        $out .= $this->row( '<i>владеть:</i>', 'mt-3' );
        $out .= $this->row( $this->mdash( $arr['outcomes']['v'] ) );
        // $out .= $this->row( '&mdash; ' . implode( ';<br />&mdash; ', $arr['outcomes']['v'] ) . '.', '' );



        $out .= $this->row( $n++ . '. Содержание дисциплины', 'fw-bolder mr-gray p-2 mt-6' );
        
        foreach ( $arr['data']['content']['parts'] as $k => $i ) {
            
            $out .= $this->row( '<b>Раздел ' . $k+1 . '.</b> ' . $i['name'], 'mt-5' );
            $out .= $this->row( $i['content'], 'mt-3' );
            // $out .= $this->row( '<b>Компетенции: </b>' . $this->span( $this->q( $i['cmp'] ) ), 'mt-3' );
            // $out .= $this->row( '<b>Часы: </b>' . $this->span( $i['hours_raw'] ) . ' <span title="Всего">' . $this->span( $i['hours_z'] ) . '</span>', 'mb-5' );
            $out .= $this->row( '<b>Трудоемкость: </b>' . $this->span( $i['hours_raw'], 'Лек, лаб, пр, СРС, кон' ) . ' ' . 
                                $this->span( $i['hours_z'], 'Всего' ), 'mt-3 mb-3' );
            // $out .= $this->row( $this->span( $i['hours_raw'] ) . ' <span title="Всего">' . $this->span( $i['hours_z'] ) . '</span>', 'mt-3 mb-4' );
            
        }
        
        if ( isset( $arr['data']['content']['parts_stat']['hours_raw'] ) )
            $out .= $this->row( '<b>ИТОГО по дисциплине: </b>' . 
                        $this->span( $arr['data']['content']['parts_stat']['hours_raw'], 'Лек, лаб, пр, СРС, кон' ) . ' ' .
                        $this->span( $arr['data']['content']['parts_stat']['hours_z'], 'Всего' ) . ' ' .
                        $this->span( $arr['data']['content']['parts_stat']['hours_ze'], 'Зачетных единиц' ) 
                    );
        
        
        $out .= $this->row( $n++ . '. Оценочные средства', 'fw-bolder mr-gray p-2 mt-6' );
        
        $out .= '<div class="row">';
        $out .= '<div class="col mt-5 p-0">';
        $out .= '<table>';
        
        foreach ( $arr['data']['evaluations'] as $k => $i ) {
            $out .= '<thead><tr><th colspan="3">Семестр ' . $k . '</th></tr></thead>';
            foreach ( $i['data'] as $i2 ) $out .= $this->tr( $i2['name'], $i2['att']['rating'], $this->q( $i2['att']['cmp'] ) );
            $out .= $this->tr( '<b>ИТОГО в семестре</b>', '<b>' . $i['stat']['rating'] . '</b>', '<b>' . $this->q( $i['stat']['cmp'] ) . '</b>' );
        }

        if ( count( $arr['data']['evaluations'] ) > 1 )
            $out .= $this->tr( '<b>ИТОГО по всем семестрам</b>', '', '<b>' . $this->q( $arr['data']['evaluations_stat']['cmp'] ) . '</b>' );
            
        $out .= '</table>';
        $out .= '</div>';
        $out .= '</div>';





        // foreach ( $arr['data']['evaluations'] as $k => $i ) {

        //     $out .= $this->row( 'Семестр ' . $k, 'fw-bolder bg-light border pl-3 mt-3' );
            
        //     foreach ( $i['data'] as $i2 ) {
                
        //         $out .= '<div class="row">';
                
        //         $out .= $this->col( $i2['name'], 'border' );
        //         $out .= $this->col( $i2['att']['rating'], 'border' );
        //         $out .= $this->col( $i2['att']['cmp'], 'border' );
                
        //         $out .= '</div>';
                
        //     }


        // }


        $out .= $this->row( $n++ . '. Индикаторы', 'fw-bolder mr-gray p-2 mt-6' );
        
        foreach ( $arr['data']['content']['parts'] as $k => $i ) {
            
            $out .= $this->row( '<b>Раздел ' . $k+1 . '.</b> ' . $i['name'], 'mt-5' );

            $out .= $this->row( '<i>знать:</i>' );
            $out .= $this->row( $this->mdash( $i['outcomes']['z'] ) );
            $out .= $this->row( '<i>уметь:</i>' );
            $out .= $this->row( $this->mdash( $i['outcomes']['u'] ) );
            $out .= $this->row( '<i>владеть:</i>' );
            $out .= $this->row( $this->mdash( $i['outcomes']['v'] ) );

            $out .= $this->row( '<b>Компетенции: </b>' . $this->span( $this->q( $i['cmp'] ) ), 'mt-3 mb-3' );
            
        }
        
        if ( isset( $arr['data']['content']['parts_stat']['cmp'] ) )
            $out .= $this->row( '<b>ИТОГО по дисциплине: </b>' . $this->span( $this->q( $arr['data']['content']['parts_stat']['cmp'] ) ) );



        $out .= $this->row( $n++ . '. Литература', 'fw-bolder mr-gray p-2 mt-6' );
        $out .= $this->row( '<b>Основная литература</b> ', 'mt-4' );
        if ( isset( $arr['data']['biblio']['basic'] ) ) $out .= $this->row(  $this->ol( $arr['data']['biblio']['basic'] ) );
        $out .= $this->row( '<b>Дополнительная литература</b> ', 'mt-4' );
        if ( isset( $arr['data']['biblio']['additional'] ) ) $out .= $this->row(  $this->ol( $arr['data']['biblio']['additional'] ) );

        $out .= $this->row( $n++ . '. Информационные технологии', 'fw-bolder mr-gray p-2 mt-6' );
        $out .= $this->row( '<b>Информационно-справочные и образовательные ресурсы Интернета</b> ', 'mt-4' );
        if ( isset( $arr['data']['it']['inet'] ) ) $out .= $this->row(  $this->ol( $arr['data']['it']['inet'] ) );
        $out .= $this->row( '<b>Информационные технологии и программное обеспечение</b> ', 'mt-4' );
        if ( isset( $arr['data']['it']['app'] ) ) $out .= $this->row(  $this->ol( $arr['data']['it']['app'] ) );

        $out .= $this->row( $n++ . '. Материально-техническое обеспечение', 'fw-bolder mr-gray p-2 mt-6' );
        if ( isset( $arr['data']['mto']['mto'] ) ) $out .= $this->row(  $this->ol( $arr['data']['mto']['mto'] ) );

        $out .= $this->row( $n++ . '. Разработчики', 'fw-bolder mr-gray p-2 mt-6' );
        if ( isset( $arr['data']['authors']['authors'] ) ) $out .= $this->row( $this->p( $arr['data']['authors']['authors'] ), 'mt-3' );

        $out .= '</div>';


        return apply_filters( 'mif_mr_part_get_courses', $out );
    }
    
    


    private function mdash( $a )
    {
        if ( empty( $a ) ) return;
        return '&mdash; ' . implode( ';<br />&mdash; ', $a ) . '.';
    }
    

    private function ol( $a )
    {
        if ( empty( $a ) ) return;
        return '<ol><li>' . implode( '</li><li>', $a ) . '</li></ol>';
    }
    

    private function p( $a )
    {
        if ( empty( $a ) ) return;
        return '<p>' . implode( '</p><p>', $a ) . '</p>';
    }
    
    


    // private function question( $s )
    private function q( $s )
    {
        return ( $s ) ? $s : '?';
    }
    
    

    private function span( $text, $t = '', $c = '' )
    {
        return '<span class="p-1 pl-3 pr-3 rounded mr-gray ' . $c. '" title="' . $t . '">' . $text . '</span>';
    }


    private function row( $t, $cc = '', $rc = '' )
    {
        $out = '';

        $out .= '<div class="row ' . $rc . '">';
        $out .= '<div class="col p-1 ' . $cc . '">';
        $out .= $t;
        $out .= '</div>';
        $out .= '</div>';

        return $out;
    }


    private function col( $t, $cc = '' )
    {
        $out = '';

        $out .= '<div class="col p-1 ' . $cc . '">';
        $out .= $t;
        $out .= '</div>';

        return $out;
    }


    private function tr( $t, $t2, $t3 )
    {
        $out = '';

        $out .= '<tr>';
        
        $out .= '<td width="70%">' . $t . '</td>';
        $out .= '<td width="10%">' . $t2 . '</td>';
        $out .= '<td width="20%">' . $t3 . '</td>';
        
        $out .= '</tr>';
        

        return $out;
    }




}

?>