<div class="opop">
    
    <div class="container no-gutters bg-light pt-5 pb-5 mt-5 mb-5">

        <div class="row ">
            <div class="col-sm">

                <?php mif_mr_the_menu_item( 'Главная', '', 1 ); ?>
                <?php mif_mr_the_menu_item(); ?>

            </div>
            <div class="col-sm">
                <div class="text-sm-end fw-semibold p-1 pl-2 pr-3">ID: <?php the_ID(); ?></div>
            
            </div>

        </div>

        <div class="row">
            <div class="col-sm">
                
                <?php mif_mr_the_menu_item( 'Дисциплины', 'courses', 1 ); ?>
                <?php mif_mr_the_menu_item( 'Матрица компетенций', 'matrix', 1 ); ?>
                <?php mif_mr_the_menu_item( 'Учебный план', 'curriculum', 1 ); ?>
                <?php mif_mr_the_menu_item(); ?>
                <?php mif_mr_the_menu_item( 'Описание разделов', '', 1 ); ?>
                <?php mif_mr_the_menu_item( 'Оценочные средства', '', 1 ); ?>
                <?php mif_mr_the_menu_item(); ?>
                <?php mif_mr_the_menu_item( 'Цели и содержание', '', 1 ); ?>
                <?php mif_mr_the_menu_item( 'Разработчики', '', 1 ); ?>
                <?php mif_mr_the_menu_item(); ?>
                <?php mif_mr_the_menu_item( 'Инструменты разработки', 'tools', 1 ); ?>
                
            </div>
            <div class="col-sm">
                
                <?php mif_mr_the_menu_item( 'Параметры ОПОП', 'param', 1 ); ?>
                <?php mif_mr_the_menu_item( 'Библиотека компетенций', 'lib-competencies', 1 ); ?>
                <?php mif_mr_the_menu_item( 'Библиотека дисциплин', 'lib-courses', 1 ); ?>
                <?php mif_mr_the_menu_item(); ?>
                <?php mif_mr_the_menu_item( 'Литература', '', 1 ); ?>
                <?php mif_mr_the_menu_item( 'Информационные технологии', '', 1 ); ?>
                <?php mif_mr_the_menu_item( 'Материально-техническое обеспечение', '', 1 ); ?>
                <?php mif_mr_the_menu_item( 'Методические указания', '', 1 ); ?>
                <?php mif_mr_the_menu_item( 'Организация СРС', '', 1 ); ?>
                <?php mif_mr_the_menu_item(); ?>
                <?php mif_mr_the_menu_item( 'Статистика ОПОП', 'stat', 1 ); ?>
                
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


