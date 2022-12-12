<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<div id="sq_wrap">
    <?php SQ_Classes_ObjController::getClass('SQ_Core_BlockToolbar')->init(); ?>
    <?php do_action('sq_form_notices'); ?>
    <div class="d-flex flex-row bg-white my-0 p-0 m-0">
        <?php $view->show_view('Blocks/Menu'); ?>

        <div class="sq_flex flex-grow-1 bg-light mx-0 py-0 pl-5">
            <div class="sq_breadcrumbs mt-5"><?php echo SQ_Classes_ObjController::getClass('SQ_Models_Menu')->getBreadcrumbs('sq_features') ?></div>
            <?php SQ_Classes_ObjController::getClass('SQ_Core_BlockFeatures')->init(); ?>
        </div>

    </div>
</div>
