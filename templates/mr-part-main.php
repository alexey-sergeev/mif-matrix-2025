<h3 class="pt-4 pb-4">Главная</h3>

<?php // mif_mr_the_form_begin(); ?>
<?php // mif_mr_show_messages() ?>

<div class="main container mt-3 overflow-auto">

    <div class="row mt-5 mb-5">
    
        <?php mif_mr_the_competencies(); ?>
        <?php // mif_mr_the_link_edit(); ?>
    
    </div>  


    <?php // mif_mr_show_explanation('courses'); ?>
    <div class="row">
        
            <?php mif_mr_show_panel( 'main' ); ?>
            <?php mif_mr_the_main(); ?>
    
    </div>
  
</div>

<?php // mif_mr_the_form_end(); ?>

