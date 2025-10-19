<h3 class="pt-4 pb-4">Дисциплины</h3>

<?php mif_mr_the_form_begin(); ?>
<?php mif_mr_show_messages() ?>

<div class="courses container mt-3">

    <!-- <div class="row mt-5 mb-5">
        <div class="col-12 p-0"><a href="?edit">Редактировать</a></div>
    </div> -->
    
    <!-- <div class="row mt-5 mb-5">
        <div class="col-12 p-0"><a href="?new">Создать</a></div>
    </div> -->
    
    <?php mif_mr_the_link_edit(); ?>

    <!-- <div class="row mt-4 bg-primary-subtle border border-primary-subtle fw-semibold text-center">
        <div class="col-1 p-2"></div>
        <div class="col-10 p-2">Списки контроля доступа</div>
        <div class="col-1 p-2"></div>
    </div> -->

    

    
    
    
    
    <?php mif_mr_show_explanation('courses'); ?>
    <div class="row">
        
        <!-- <div class="col text-end p-0 mb-3">
            <?php // mif_mr_the_tab( 'модули', 'modules', true ); ?> | 
            <?php // mif_mr_the_tab( 'дисциплины', 'courses' ); ?>
        </div> -->
        
        <!-- <div class="col-auto p-0 mb-3">
                <form>
        
                    <ul class="nav nav-tabs pb-0 nav-tabs-0">
                        <li class="nav-item"><label class="nav-link mb-0 active"><input type="radio" class="d-none" name="key" value="modules" checked="" />Модули</label></li>
                        <li class="nav-item"><label class="nav-link mb-0"><input type="radio" class="d-none" name="key" value="courses" />Дисциплины</label></li>
                    </ul>
            
                    <input type="hidden" name="action" value="courses" />
                    <input type="hidden" name="opop" value="<?php // echo mif_mr_the_opop_id(); ?>" />
                    <input type="hidden" name="_wpnonce" value="<?php // echo wp_create_nonce( 'mif-mr' ); ?>" />     
        
                </form>
        </div>
         -->


        <!-- <div class="content-part p-0"> -->
            <?php mif_mr_show_panel( 'courses' ); ?>
            <?php mif_mr_the_courses(); ?>
        <!-- </div> -->
    
    </div>



    
  
</div>

<?php mif_mr_the_form_end(); ?>

