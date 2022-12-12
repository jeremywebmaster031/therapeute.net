<div class="sq_sub_nav d-flex flex-column bd-highlight m-0 p-0 border-right">
    <?php
    $page = SQ_Classes_Helpers_Tools::getValue('page');
    $tabs = SQ_Classes_ObjController::getClass('SQ_Models_Menu')->getTabs($page);
    if (!empty($tabs)) {
        $current = (SQ_Classes_Helpers_Tools::getValue('tab') ? $page.'/'.SQ_Classes_Helpers_Tools::getValue('tab') : SQ_Classes_Helpers_Tools::arrayKeyFirst($tabs));

        if(isset($tabs[$current]['tabs']) && !empty($tabs[$current]['tabs'])){
            foreach ($tabs[$current]['tabs'] as $index => $tab) {
                if(isset($tab['show']) && !$tab['show']) continue;
                ?>
                <a href="#<?php echo esc_attr($tab['tab']) ?>" class="m-0 pl-3 pr-1 py-3 font-dark sq_sub_nav_item <?php echo esc_attr($tab['tab'])?> <?php echo ($index == 0 ? 'active' : '') ?>" data-tab="<?php echo esc_attr($tab['tab'])?>"><?php echo wp_kses_post($tab['title']) ?></a>
                <?php
            }
        }
    }
    ?>
</div>
