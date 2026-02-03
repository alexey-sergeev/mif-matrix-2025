<h3 class="pt-4 pb-4">Атрибуты ОПОП</h3>

<div class="attributes container">
    <div class="row">
        
        <div class="col-10 p-0">
            
            <?php mif_mr_the_form_begin(); ?>
           
            <?php mif_mr_show_messages() ?>
            <?php mif_mr_show_explanation('attributes'); ?>
            
            <?php mif_mr_the_attributes(); ?>

            <?php mif_mr_the_form_end(); ?>
            
        </div>
        
        <div class="sidebar col-2 pl-5">
            
            <div class="fiksa">

                <div class="container">

                    <?php // mif_mr_the_link_edit_easy(); ?>
                    <?php mif_mr_the_link_edit(); ?>
                    <?php mif_mr_the_link_edit_advanced(); ?>

                </div>
                
            </div>

        </div>
        
    </div>
</div>


