<?php
global $post;
$extra_fields = medicalpro_all_extra_fields($post->ID);
if(isset($extra_fields) && !empty($extra_fields)){ ?>	
    <div id="mp-services-tab" class="mp-services-tab margin-bottom-60">
        <?php echo $extra_fields; ?>
    </div>
<?php } ?>