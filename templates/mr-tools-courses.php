<h3 class="pt-4 pb-3">Инструменты разработки</h3>

<div class="container pt-5 tools">
    <div class="row">
        
        <div class="col-10 p-0">
            
            <h4 class="pt-3 pb-4">Импорт шаблонов учебных дисциплин</h4>
            <div class="mb-5">
                Загрузка Excel-шаблонов дисциплин для обновления данных ОПОП.
            </div>
            
            <?php mif_mr_show_messages() ?>
            
            <!-- <div class="container"> -->
            <div>
                
                <?php mif_mr_tools_courses();
                // mif_mr_form_upload( 'Загрузите файл учебного плана в формате XML', 'tools-curriculum' ); 
                // ?>
                
            </div>
            
        </div>
        
        <div class="sidebar col-2 pl-5">
            
            <div class="fiksa">
                
                <?php mif_mr_show_back( 'tools' ); ?>
                <?php mif_mr_remove_all( 'courses' ); ?>   

            </div>

        </div>
        
    </div>
</div>

<?php // mif_mr_the_form_end(); ?>

