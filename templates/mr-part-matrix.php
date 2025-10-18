<h3 class="pt-4 pb-4">Матрица компетенций</h3>

<?php mif_mr_the_form_begin(); ?>
<?php mif_mr_show_messages() ?>

<div class="matrix container mt-3">

    <?php mif_mr_the_link_edit(); ?>

    <?php mif_mr_show_explanation('matrix'); ?>

    <div class="row"><?php mif_mr_the_matrix(); ?></div>
  
</div>

<?php mif_mr_the_form_end(); ?>

