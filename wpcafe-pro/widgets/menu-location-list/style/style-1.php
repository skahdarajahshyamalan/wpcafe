<div class="wpc-row">
    <?php foreach ($cats as $value) { ?>
    <div class="wpc-col-lg-<?php echo esc_attr($grid_column); ?> wpc-col-md-6 ">
            <?php
            $term = get_term($value, $taxonomy);
            if ( empty($term) ) {
                ?>
                    <div><?php echo esc_html__('No Location Found', 'wpcafe-pro'); ?></div>
                <?php
            }else {
            $term_link = get_term_link($term->slug, $taxonomy);
            $image = get_term_meta($term->term_id, 'location_image', true);
            $image_url =  wp_get_attachment_image_url($image, '',  $icon = false);
            ?>
            <div class="wpc-single-cat-item">
                <?php if (isset($image_url) && $image_url != '') { ?>
                    <div class="wpc-cat-thumb" style="background-image: url(<?php echo esc_url($image_url); ?>);">
                        <a href="<?php echo esc_url($term_link); ?>" class="wpc-img-link"></a>
                    </div>
                <?php } else { ?>
                    <div class="wpc-cat-thumb"></div>
                <?php } ?>
                <h3 class="wpc-category-title">
                    <a href="<?php echo esc_url($term_link); ?>">
                        <?php
                        echo esc_html($term->name);
                        if ('yes' == $show_count) { ?>
                            <span class="menu-count">
                                ( <?php echo esc_html($term->count); ?> )
                            </span>
                        <?php } ?>
                    </a>

                </h3>

            </div>
            <?php } ?>
        </div>
    <?php } ?>
  
</div>
