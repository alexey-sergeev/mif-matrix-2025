<h3 class="pt-4 pb-4">Все компетенции</h3>

<?php mif_mr_show_lib_comp(); ?>

<?php mif_mr_the_form_begin(); ?>
<?php mif_mr_show_messages() ?>

<div class="comp container mt-3">
<!-- <div class="set-comp"> -->

    
    <div class="mt-5 mb-5">
    
        <?php mif_mr_the_advanced_edit_link(); ?>
        <?php mif_mr_the_link_edit_visual(); ?>
    
    </div>  

    <?php //mif_mr_show_explanation('set_comp'); ?>
    
    
    <!-- <div class="row"> -->
        <?php mif_mr_the_set_comp(); ?>
        <?php // mif_mr_show_panel( 'matrix' ); ?>
        <?php // mif_mr_the_matrix(); ?>
    <!-- </div> -->
  
</div>

<?php mif_mr_the_form_end(); ?>

