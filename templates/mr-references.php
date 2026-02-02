<h3 class="pt-4 pb-4">Справочники</h3>

<div class="comp container">
    <div class="row">
        
        <div class="col-10 p-0">
            
            <?php mif_mr_show_messages() ?>
            <?php mif_mr_show_references(); ?>
            
        </div>
        
        <div class="sidebar col-2 pl-5">
            
            <div class="fiksa">
                
                <?php mif_mr_the_comp_id(); ?>
                <?php mif_mr_show_back( 'lib-references' ); ?>
                <?php mif_mr_the_edit_link(); ?>
                <?php mif_mr_the_advanced_edit_link(); ?>
                <?php mif_mr_the_remove_link(); ?>
                
            </div>

        </div>
        
    </div>
</div>


