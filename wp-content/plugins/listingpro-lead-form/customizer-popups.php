<!--Start Edit and save changes popup-->
<div class="modal fade lp-alerts-customizer" id="attributesModal" tabindex="-1" role="dialog" aria-labelledby="attributesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!--<h5 class="modal-title" id="">Modal title</h5>-->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary lp-btn-primary-cust" data-dismiss="modal"><?php echo esc_html( __('Cancel & Close', 'listingpro-lead-form')); ?></button>
                <button type="button" class="btn btn-primary" id="lp-save-attrs"><?php echo esc_html( __('Save Changes', 'listingpro-lead-form')); ?></button>
            </div>
        </div>
    </div>
</div>
<!--End Edit and save changes popup-->

<!--Start reset and cancel popup-->
<div class="modal fade lp-alerts-customizer" id="lp-reset-action" tabindex="-1" role="dialog" aria-labelledby="attributesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content lp-delete-wrap">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="lp-delete-box-text">
                <p><?php echo esc_html( __('Are you sure you want to reset this template?','listingpro-lead-form')); ?></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary lp-btn-primary-cust" data-dismiss="modal"><?php echo esc_html( __('Cancel & Close','listingpro-lead-form')); ?></button>
                <button type="button" class="btn btn-primary delete-popup-btn confirm-reset" id=""><i class="fa fa-refresh"></i> <?php echo esc_html( __('Reset','listingpro-lead-form')); ?></button>
            </div>
        </div>
    </div>
</div>
<!--End reset and cencel popup-->

<!--Start delete and cancel popup-->
<div class="modal fade lp-alerts-customizer" id="lp-el-remove-action" tabindex="-1" role="dialog" aria-labelledby="attributesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content lp-delete-wrap">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="lp-delete-box-text">
                <p><?php echo esc_html( __('Are you sure you want to delete this block?','listingpro-lead-form')); ?></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary lp-btn-primary-cust" data-dismiss="modal"><?php echo esc_html( __('Cancel & Close','listingpro-lead-form')); ?></button>
                <button type="button" class="btn btn-primary delete-popup-btn lp-el-confirm-delete" id=""><i class="fa fa-trash"></i> <?php echo esc_html( __('Delete','listingpro-lead-form')); ?></button>
            </div>
        </div>
    </div>
</div>
<!--End delete and cencel popup-->