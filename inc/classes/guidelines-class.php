<?php

//
// Класс для работы с методическими указаниями
// А. Н. Сергеев, Волгоград
// Август 2026
// 

include_once dirname( __FILE__ ) . '/functions.php';
include_once dirname( __FILE__ ) . '/parser-class.php';
include_once dirname( __FILE__ ) . '/curriculum-class.php';
include_once dirname( __FILE__ ) . '/html-class.php';

class guidelines {

    //
    // Инициализация 
    // На входе - массив или текстовое описание всех дисциплин и их методических указаний
    // $curriculum - данные для построения учебного плана
    // 

    function __construct( $data = '' )
    {

        if ( is_string( $data ) ) {
    
            $p = new parser();
            $data = $p->get_arr( $data, array( 'section' => 'guidelines' ) );
    
        }
        
        // p($data);

        if ( isset( $data['GUIDELINES']['guidelines']['parts'] ) ) {

            foreach ( (array) $data['GUIDELINES']['guidelines']['parts'] as $item ) {
                
                $this->guidelines_arr['guidelines'][$item['att'][0]] = array(
                                                                            
                                                                            'name' => $item['name'],
                                                                            'text' => implode( "\n", $item['data'] ),
                                                                        
                                                                        );

            }
 
        };
        
        // p($this->guidelines_arr);


        // // Построить массив учебного плана или взять план, если это уже массив

        // if ( is_string( $curriculum ) ) {

        //     $c = new curriculum( $curriculum );
        //     $this->curriculum_arr = $c->get_arr();
            
        // } else {
            
        //     $this->curriculum_arr = $curriculum;

        // }

        // // Указания по умолчанию
        
        // if ( ! empty( $this->curriculum_arr ) ) {
            
        //     foreach ( $this->curriculum_arr as $course => $sem_data ) {

        //         $this->guidelines_arr[$course]['guidelines'] = $this->get_default_guidelines( $course );

        //     }
            
        // }
        
        // // Уточнить фактическим описанием

        // foreach ( $data as $item ) {

        //     if ( empty( $item['guidelines']['data'] ) ) continue;
        //     $this->guidelines_arr[$item['guidelines']['name']]['guidelines'] = $this->get_guidelines( $item['guidelines'] );

        // }


    }
 
    

    


    // // 
    // // Формирует методические указания по умолчанию
    // // 

    // private function get_default_guidelines( $course )
    // {
    //     $arr = array();

    //     // p($course);
        
    //     $course_stat = $this->curriculum_arr[$course]['course_stat'];
        
    //     // p($course_stat);

    //     $elem = array();

    //     if ( $course_stat['lec'] ) $elem[] = 'чтение лекций';
    //     if ( $course_stat['prac'] ) $elem[] = 'проведение практических занятий';
    //     if ( $course_stat['lab'] ) $elem[] = 'выполнение лабораторных работ';
    //     if ( $course_stat['srs'] ) $elem[] = 'выполнение самостоятельной работы студента';

    //     $elem_att = array();

    //     if ( isset( $course_stat['att']['к'] ) ) $elem_att[] = 'контрольной работы';
    //     if ( isset( $course_stat['att']['зч'] ) ) $elem_att[] = 'зачёта';
    //     if ( isset( $course_stat['att']['зчо'] ) ) $elem_att[] = 'зачёта с оценкой';
    //     if ( isset( $course_stat['att']['эк'] ) ) $elem_att[] = 'экзамена';

    //     $kr = ( isset( $course_stat['att']['кр'] ) ) ? ' В ' . implode( ', ', $course_stat['att']['кр'] ) . ' семестре предусмотрено написание курсовой работы.' : '';
    //     $kp = ( isset( $course_stat['att']['кп'] ) ) ? ' В ' . implode( ', ', $course_stat['att']['кп'] ) . ' семестре предусмотрено выполнение курсового проекта.' : '';

    //     $arr[] = 'Программой дисциплины предусмотрено ' . implode( ', ', $elem ) . '. 
    //     Промежуточная аттестация проводится в форме ' . implode( ', ', $elem_att ) . '.' . $kr . $kp;

    //     if ( $course_stat['lec'] ) $arr[] = 'Лекционные занятия направлены на формирование глубоких, систематизированных знаний 
    //     по разделам дисциплины. В ходе лекций преподаватель раскрывает основные, наиболее сложные понятия дисциплины, 
    //     а также связанные с ними теоретические и практические проблемы, даёт рекомендации по практическому освоению изучаемого 
    //     материала. В целях качественного освоения лекционного материала обучающимся рекомендуется составлять конспекты лекций, 
    //     использовать эти конспекты при подготовке к практическим занятиям, промежуточной и итоговой аттестации.';

    //     if ( $course_stat['prac'] ) $arr[] = 'Практические занятия являются формой организации педагогического процесса, 
    //     направленной на углубление научно-теоретических знаний и овладение методами работы, в процессе которых вырабатываются 
    //     умения и навыки выполнения учебных действий в сфере изучаемой науки. Практические занятия предполагают детальное изучение 
    //     обучающимися отдельных теоретических положений учебной дисциплины. В ходе практических занятий формируются умения и навыки 
    //     практического применения теоретических знаний в конкретных ситуациях путем выполнения поставленных задач, развивается научное 
    //     мышление и речь, осуществляется контроль учебных достижений обучающихся. При подготовке к практическим занятиям необходимо 
    //     ознакомиться с теоретическим материалом дисциплины по изучаемым темам – разобрать конспекты лекций, изучить литературу, 
    //     рекомендованную преподавателем. Во время самого занятия рекомендуется активно участвовать в выполнении поставленных заданий, 
    //     задавать вопросы, принимать участие в дискуссиях, аккуратно и своевременно выполнять контрольные задания.';

    //     if ( $course_stat['lab'] ) $arr[] = 'Лабораторная работа представляет собой особый вид индивидуальных практических 
    //     занятий обучающихся, в ходе которых используются теоретические знания на практике, применяются специальные технические 
    //     средства, различные инструменты и оборудование. Такие работы призваны углубить профессиональные знания обучающихся, 
    //     сформировать умения и навыки практической работы в соответствующей отрасли наук. В процессе лабораторной работы обучающийся 
    //     изучает практическую реализацию тех или иных процессов, сопоставляет полученные результаты с положениями теории, осуществляет 
    //     интерпретацию результатов работы, оценивает возможность применения полученных знаний на практике. При подготовке 
    //     к лабораторным работам следует внимательно ознакомиться с теоретическим материалом по изучаемым темам. Необходимым 
    //     условием допуска к лабораторным работам, предполагающим использованием специального оборудования и материалов, 
    //     является освоение правил безопасного поведения при проведении соответствующих работ. В ходе самой работы необходимо 
    //     строго придерживаться плана работы, предложенного преподавателем, фиксировать промежуточные результаты работы для 
    //     отчета по лабораторной работе.';

    //     if ( $course_stat['srs'] ) $arr[] = 'Самостоятельная работа обучающихся является неотъемлемой частью процесса обучения в вузе. 
    //     Правильная организация самостоятельной работы позволяет обучающимся развивать умения и навыки в усвоении и систематизации 
    //     приобретаемых знаний, обеспечивает высокий уровень успеваемости в период обучения, способствует формированию навыков 
    //     совершенствования профессионального мастерства. Самостоятельная работа обучающихся во внеаудиторное время включает в себя 
    //     подготовку к аудиторным занятиям, а также изучение отдельных тем, расширяющих и углубляющих представления обучающихся по 
    //     разделам изучаемой дисциплины. Такая работа может предполагать проработку теоретического материала, работу с научной литературой, 
    //     выполнение практических заданий, подготовку ко всем видам контрольных испытаний, выполнение творческих работ.';

    //     $arr[] = 'Контроль за качеством обучения и ходом освоения дисциплины осуществляется на основе рейтинговой системы текущего 
    //     контроля успеваемости и промежуточной аттестации студентов. Рейтинговая система предполагает 100-балльную оценку успеваемости 
    //     студента по учебной дисциплине в течение семестра, 60 из которых отводится на текущий контроль, а 40 – на промежуточную 
    //     аттестацию по дисциплине. Критериальная база рейтинговой оценки, типовые контрольные задания, а также методические 
    //     материалы по их применению описаны в фонде оценочных средств по дисциплине, являющемся приложением к данной программе.';
        
    //     // В качестве основной формы отчетности по практике является письменный отчет, 
    //     // представленный в виде дневника практики или описания полученных результатов. Отчет должен содержать сведения о конкретно 
    //     // выполненной работе в период практики в соответствии с программой практики. Описание формы, примерного содержания, 
    //     // структуры и критериев оценивания отчета представлено в фонде оценочных средств.';

    //     $arr = array_map( 'strim', $arr );
        

    //     return $arr;
    // }    

    

    // 
    // Функция возвращает методические указания из описания
    // 
  
    public function get_guidelines( $raw, $lib, $course )
    {
        if ( empty( $lib['data'] ) ) return $raw;

        // p($course['name']);
                // p($course['meta']['hours']);

        $unit = $course['unit'];
        
        foreach ( array( 'lec', 'lab', 'prac', 'srs' ) as $i )
            $flags[$i] = ( isset( $course['meta']['hours'][$i] ) && ! $course['meta']['hours'][$i] ) ? false : true; 
        // $flags[$i] = ( isset( $cur['course_stat'][$i] ) && ! $cur['course_stat'][$i] ) ? false : true; 
        // if ( isset( $cur['course_stat'][$i] ) && ! $cur['course_stat'][$i] ) $flags[$i] = false;    
        
        foreach ( array( 'эк', 'зч', 'зчо', 'к', 'кр', 'кп' ) as $i )
            $flags[ 'exam_' . $i ] = ( isset( $course['meta']['hours']['att'][$i] ) && $course['meta']['hours']['att'][$i] ) ? true : false;    

        // $flags['exam_мод'] = ( empty( $course['meta']['hours']['att'] ) ) ? true : false;
        $flags['exam_мод'] = ( empty( $course['meta']['hours']['att']['эч'] ) &&
                                empty( $course['meta']['hours']['att']['зч'] ) &&
                                empty( $course['meta']['hours']['att']['зчо'] ) &&
                                empty( $course['meta']['hours']['att']['к'] ) ) ? true : false;
        
        $index = array();
        foreach ( $lib['data'] as $k => $d ) $index[$d['att']['slug']][] = $k;
            
        // p($index);

        $course_exam = ( ! empty( $course['meta']['hours']['att'] ) ) ? array_unique( array_keys( $course['meta']['hours']['att'] ) ) : array();
        // p($course_exam);

        $arr = array();

        foreach ( $index as $k => $d ) {
            // p('@@');
            // p($k);
            
            foreach ( $d as $i ) {

                $arr_units = array_map( 'mb_strtoupper', array_map( 'trim', explode( ',', $lib['data'][$i]['att']['units'] ) ) );
                $arr_exam = array_diff( array_map( 'mb_strtolower', array_map( 'trim', explode( ',', $lib['data'][$i]['att']['exam'] ) ) ), array( '' ) );

                $intersect = array_intersect( $arr_exam, $course_exam );

                // p('@');
                // p($arr_exam);
                // p($course_exam);
                // p($intersect);
                
                
                if ( in_array( $unit, $arr_units ) ) {

                    // p($unit);
                    // p($unit);
                    // p( $lib['data'][$i]['att']['units']  );
                    // p( $lib['data'][$i]['att']['exam']  );

                    // p( explode( ',', $lib['data'][$i]['att']['exam'] ) ) ;

                    // p($lib['data'][$i]['data'][0]);
                    
                    if ( empty( $arr_exam ) || ( ! empty( $arr_exam ) && ! empty( $intersect ) ) ) {

                        $arr[$k][] = array( 
                                        'name' => $lib['data'][$i]['data'][0]['key'],
                                        'text' => $this->do_clean( $lib['data'][$i]['data'][0]['value'], $flags ),
                                    );

                    }
                    

                    


                    // continue;

                }

            }
        }


        // p($flags);
        // p($lib);
        // p($arr);

        $arr2 = array();
        foreach ( $arr as $k => $a ) {
            
            $arr3 = array();
            foreach ( $a as $i ) $arr3[] = $i['text'];
            
            $arr2[$k]['name'] = $arr[$k][0]['name']; 
            $arr2[$k]['text'] = implode( "\n", $arr3 );

        }
        
        
        // p($raw);
        
        foreach ( (array) $raw as $k => $a ) $arr2[$k] = $a;
        
        // p($arr2);
        return $arr2;
    }
    



    private function do_clean( $t, $flags )
    {
        // p($t);

        $adapter = apply_filters( 'mif_mr_guidelines_do_clean', array( 
                    'ЛЕК' => 'lec', 
                    'ЛАБ' => 'lab', 
                    'ПРАК' => 'prac', 
                    'СРС' => 'srs',
                    'ЭК' => 'exam_эк',
                    'ЗЧ' => 'exam_зч',
                    'ЗЧО' => 'exam_зчо', 
                    'К' => 'exam_к',
                    'КР' => 'exam_кр', 
                    'КП' => 'exam_кп',
                    'МОД' => 'exam_мод',
                ) );

        // $arr = array();
        $arr = explode( '{', $t );

        $s = '';

        foreach ( $arr as $a ) {
            // p($a);
            // preg_match( '/^@(?<key>\w+)(?<value>.*)/', $a, $m )
            // preg_match( '/^(.*):(.*)}/U', $a, $m );
            preg_match( '/^(?<key>.*):(?<value>.*)}(?<value2>.*\s.*)$/U', $a, $m );

            if ( empty( $m['key'] ) ) {
                // p($m);
                // p($a);
                $s .= $a;
                continue;

            }

            $key = ( isset( $m['key'] ) ) ? mb_strtoupper( trim( $m['key'] ) ) : '';
            $value = ( isset( $m['value'] ) ) ? trim( $m['value'] ) : '';
            $value2 = ( isset( $m['value2'] ) ) ? $m['value2'] : '';

            $value3 = '';
            if ( isset( $adapter[$key] ) && $flags[ $adapter[$key] ] ) $value3 = $value;
            
            $s .= $value3 . $value2;



            // switch ( $key ) {

            //     case 'ЛЕК':
            //         if ( $flags['lec'] ) $v = $value;
            //         break;
                
            //     default:
            //         # code...
            //         break;
            // }
            

            // p('@');
            // p($a);
            // p($m);
            // p($key);
            // p($value);
            // p($value2);
            // p($after);

        }

        $arr2 = explode( "\n", $s );

        $arr3 = array();
        
        foreach ( $arr2 as $a ) {

            if ( empty( $a ) ) continue;

            $a = strim( $a );
            // $a = preg_replace( '/,? ?\.$/', '.', $a );
            $a = preg_replace( '/, \./', '.', $a );

            $arr3[] = $a;

        }

        // $arr2 = array_map( 'strim', $arr2 );
        // p($arr3);

        // $out = preg_replace( '/ +/', ' ', $out );
        // $out = preg_replace( '/\s\s+/', "\n", $out );
        // $out = trim( $out, ',. ');
        // $out = preg_replace( '/,? ?\.$/', ".", $out );
        // p($s);
        $out = implode( "\n", $arr3 );
        
        // p($out);

        return $out;
    }




    // public function get_guidelines( $raw, $lib, $course )
    // {

    //     $unit = $course['unit'];

    //     p($unit);
    //     // if ( empty( $lib ) ) return $raw;

    //     // $flags = array(
    //     //             'lec' => true,
    //     //             'lab' => true,
    //     //             'prac' => true,
    //     //             'srs' => true,
    //     //             'exam_эк' => false,
    //     //             'exam_зч' => false,
    //     //             'exam_зчо' => false,
    //     //             'exam_к' => false,
    //     //             'exam_кр' => false,
    //     //             'exam_кп' => false,
    //     //         );

    //     foreach ( array( 'lec', 'lab', 'prac', 'srs' ) as $i )
    //         $flags[$i] = ( isset( $course['meta']['hours'][$i] ) && ! $course['meta']['hours'][$i] ) ? false : true; 
    //         // $flags[$i] = ( isset( $cur['course_stat'][$i] ) && ! $cur['course_stat'][$i] ) ? false : true; 
    //         // if ( isset( $cur['course_stat'][$i] ) && ! $cur['course_stat'][$i] ) $flags[$i] = false;    
        
    //     foreach ( array( 'эк', 'зч', 'зчо', 'к', 'кр', 'кп' ) as $i )
    //         $flags[ 'exam_' . $i ] = ( isset( $course['meta']['hours']['att'][$i] ) && $course['meta']['hours']['att'][$i] ) ? true : false;    
    //         // $flags[ 'exam_' . $i ] = ( isset( $cur['course_stat']['att'][$i] ) && $cur['course_stat']['att'][$i] ) ? true : false;    
    //         // if ( isset( $cur['course_stat']['att'][$i] ) && $cur['course_stat']['att'][$i] ) $flags[ 'exam_' . $i ] = true;    

    //     // p($flags);

    //     // p($lib);


    //     return;
    //     // $arr = array_map( 'strim', $data['data'] );
    //     // return $arr;
    // }
    

    // 
    // Функция возвращает массив методических указаний
    // 
    
    function get_arr()
    {
        return $this->guidelines_arr;
    }
 


    // // 
    // // Функция возвращает методические указания в виде HTML-таблицы
    // // 

    // function get_html( $arr = NULL, $full = true )
    // {
    //     $html = '';
        
    //     $h = new html();

    //     // Если полный формат - добавляем заголовок таблицы

    //     if ( $full ) {

    //         $html .= $h->table_header();
            
    //     }
        
    //     $n = 1;

    //     if ( $arr === NULL ) $arr = $this->guidelines_arr;

    //     foreach ( $arr as $course => $data ) {
            
    //         $html .= $h->course_name_tr( $course, $n );

    //         $row = "<p><strong>Методические указания</strong></p>\n";
    //         $row .= "<p>";
    //         $row .= implode( "</p>\n<p>", $data['guidelines'] );
    //         $row .= "</p>";

    //         $html .= $h->course_data_tr( $row );
            
    //         $n++;
    //     }
        
    //     // Если полный формат - добавляем низ таблицы

    //     if ( $full ) {
            
    //         $html .= $h->table_footer();
            
    //     }
        
    //     return $html;
    // }


    private $guidelines_arr = array();
    // private $curriculum_arr = array();

}
    


if ( ! function_exists( "p") ) {
    
    function p( $t )
    {
        print_r( "<pre>" );
        print_r( $t );
        print_r( "</pre>" );
    }

}



?>