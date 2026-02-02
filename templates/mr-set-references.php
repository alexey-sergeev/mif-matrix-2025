<h3 class="pt-4 pb-4">Все справочники</h3>

<?php mif_mr_the_lib_references(); ?>

<?php mif_mr_the_form_begin(); ?>


<div class="container pt-5">
    <div class="row">
        
        <div class="col-10 p-0">
            
            <?php mif_mr_show_messages() ?>
            
            <div class="container">
                
                <?php // mif_mr_the_set_references(); ?>
                
            </div>
            
        </div>
        
        <div class="sidebar col-2 pl-5">
            
            <div class="fiksa">
                
                <div class="container">
                    
                    <?php // mif_mr_the_link_edit_visual(); ?>
                    <?php mif_mr_the_link_edit() ?>
                    <?php mif_mr_the_link_edit_advanced(); ?>
                    
                </div>
            
            </div>

        </div>
        
    </div>
</div>

<?php mif_mr_the_form_end(); ?>

