<div class="opop">
    
    <div class="container no-gutters bg-light p-5 mt-5 mb-5 border rounded">
    <!-- <div class="container no-gutters"> -->

        <div class="row ">
            <div class="col-sm">

                <div class="d-sm-none d-block mb-5 text-end"><?php mif_mr_the_opop_id(); ?></div>
                <?php mif_mr_the_menu_item( 'Главная', '', 1 ); ?>
                
            </div>
            <div class="col-sm">
                
                <!-- <div class="text-sm-end mt-sm-0"><?php // mif_mr_the_opop_id(); ?></div> -->
                <div class="d-sm-block d-none text-end"><?php mif_mr_the_opop_id(); ?></div>
                            
            </div>
            
        </div>
        
        <div class="row">
            <div class="col-sm">
                
                <?php mif_mr_the_menu_item(); ?>
                <?php mif_mr_the_menu_item( 'Дисциплины', 'courses', 1 ); ?>
                <?php mif_mr_the_menu_item( 'Матрица компетенций', 'matrix', 1 ); ?>
                <?php mif_mr_the_menu_item( 'Учебный план', 'curriculum', 1 ); ?>
                <?php mif_mr_the_menu_item( '', '', 2 ); ?>
                <?php mif_mr_the_menu_item( 'Материалы дисциплин', '', 2 ); ?>
                <?php mif_mr_the_menu_item( '', '', 3 ); ?>
                <?php mif_mr_the_menu_item( 'Рекомендации', 'stat', 3 ); ?>
                <?php mif_mr_the_menu_item( 'Статистика ОПОП', 'stat', 2 ); ?>
                <?php // mif_mr_the_menu_item( 'Цели и содержание', '', 1 ); ?>
                <?php // mif_mr_the_menu_item( 'Описание разделов', '', 1 ); ?>
                <?php // mif_mr_the_menu_item( 'Оценочные средства', '', 1 ); ?>
                <?php // mif_mr_the_menu_item(); ?>
                <?php // mif_mr_the_menu_item( 'Литература', '', 1 ); ?>
                <?php // mif_mr_the_menu_item( 'Информационные технологии', '', 1 ); ?>
                <?php // mif_mr_the_menu_item( 'Разработчики', '', 1 ); ?>
                <?php // mif_mr_the_menu_item( 'Материально-техническое обеспечение', '', 1 ); ?>
                <?php // mif_mr_the_menu_item(); ?>
                <?php // mif_mr_the_menu_item( 'Методические указания', '', 1 ); ?>
                <?php // mif_mr_the_menu_item( 'Организация СРС', '', 1 ); ?>
                
            </div>
            <div class="col-sm">
                
                <?php mif_mr_the_menu_item(); ?>
                <?php mif_mr_the_menu_item( 'Параметры ОПОП', 'param', 2 ); ?>
                <?php mif_mr_the_menu_item( 'Атрибуты ОПОП', 'attributes', 2 ); ?>
                <?php mif_mr_the_menu_item(); ?>
                <?php mif_mr_the_menu_item( 'Библиотека дисциплин', 'lib-courses', 2 ); ?>
                <?php mif_mr_the_menu_item( 'Библиотека компетенций', 'lib-competencies', 2 ); ?>
                <?php mif_mr_the_menu_item( 'Библиотека справочников', 'lib-references', 2 ); ?>
                <?php // mif_mr_the_menu_item( 'Библиотека шаблонов', 'lib-templates', 1 ); ?>
                <?php mif_mr_the_menu_item( '', '', 3 ); ?>
                <?php mif_mr_the_menu_item( 'Инструменты разработки', 'tools', 3 ); ?>
                
            </div>
        </div>
        
    </div>    
    

    <!-- <div class="row justify-content-end">
        <div class="col-auto d-none d-sm-block">
            <a href="#" class="text-secondary" id="fullsize">
                <i class="fa-solid fa-expand fa-2x"></i><i class="d-none fa-solid fa-compress fa-2x"></i>
            </a>
        </div>
    </div> -->
    
    
    <div class="part">
        <!-- <div class="content"> -->
            
        <?php mif_mr_the_part(); ?>

        <!-- </div>     -->
    </div>    

</div>    


