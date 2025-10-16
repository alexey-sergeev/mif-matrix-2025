<h3 class="pt-4 pb-4">Матрица компетенций</h3>

<?php mif_mr_the_form_begin(); ?>
<?php mif_mr_show_messages() ?>

<div class="matrix container mt-3">

    <?php mif_mr_the_link_edit(); ?>

<div class="d-none d-md-block col text-right">
    <a href="#" class="text-secondary" id="fullsize">

        <i class="fa-solid fa-expand fa-2x"></i>
        <i class="fa-solid fa-down-left-and-up-right-to-center fa-2x"></i>
        <i class="fa-solid fa-maximize fa-2x"></i>
        <i class="fa-solid fa-up-right-and-down-left-from-center fa-2x"></i>
    <!-- <i class="fas fa-expand-arrows-alt fa-2x"></i>
        <i class="fas fa-compress-arrows-alt fa-2x"></i> -->
    </a>
</div>



    <?php mif_mr_show_explanation('matrix'); ?>

    <div class="row"><?php mif_mr_the_matrix(); ?></div>
  
</div>

<?php mif_mr_the_form_end(); ?>

