<h3 class="pt-4 pb-4">Все компетенции</h3>

<?php mif_mr_show_lib_comp(); ?>

<?php mif_mr_the_form_begin(); ?>


<div class="comp container pt-5">
    <div class="row">
        
        <div class="col-10 p-0">
            
            <?php mif_mr_show_messages() ?>
            
            <div class="container">
                
                <?php mif_mr_the_set_comp(); ?>
                
            </div>
            
        </div>
        
        <div class="sidebar col-2 pl-5">
            
            <div class="fiksa">
                
                <div class="container">
                    
                    <?php mif_mr_the_link_edit_visual(); ?>
                    <?php mif_mr_the_link_edit() ?>
                    <?php mif_mr_the_link_edit_advanced( 'set-competencies' ); ?>
                    
                </div>
            
            </div>

        </div>
        
    </div>
</div>













<?php mif_mr_the_form_end(); ?>

