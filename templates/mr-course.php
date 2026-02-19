<!-- <h3 class="pt-4 pb-4">Библиотека дисциплин</h3> -->
<!-- <h3 class="mt-6">Все дисциплины и практики</h3> -->
<!-- <p class="mb-5">Комментарий</p> -->

<h3 class="pt-4 pb-4">Дисциплина из библиотеки</h3>


<?php mif_mr_the_lib_courses(); ?>

<div class="container">
    <div class="row">
        
        <div class="col-10 p-0">
            
            <?php mif_mr_show_messages() ?>
            <?php mif_mr_the_course(); ?>

        </div>
        
        <div class="sidebar col-2 pl-5">

            <div class="fiksa">
            
                <?php mif_mr_the_comp_id(); ?>
                <?php mif_mr_show_back( 'lib-courses' ); ?>
                <?php mif_mr_the_edit_link(); ?>
                <?php mif_mr_the_advanced_edit_link(); ?>
                <?php mif_mr_the_remove_link(); ?>
                <?php mif_mr_download_link( 'xls' ); ?>
                <?php mif_mr_download_link( 'text' ); ?>
            
            </div>

        </div>
        
    </div>
</div>



