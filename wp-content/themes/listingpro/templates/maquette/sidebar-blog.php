<?php include('all-style.php') ?>
<div class="maquette-container-sidebar-blog">
    <div class="maquette-sidebar-blog-author">
        <div class="maquette-sidebar-blog-author-element">
            <img class="maquette-sidebar-blog-img" src="<?php $avatar_url = listingpro_get_avatar_url(get_the_author_meta('ID'), $size = '458'); echo esc_url($avatar_url); ?>" alt="Therapeute Image <?php the_author(); ?>" title="Therapeute <?php the_author(); ?>">
            <div class="maquette-sidebar-blog-separator"></div>
            <div class="text-center">
                <h3 title="Therapeute "> <?php the_author(); ?></h3>
                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="Therapeute " target="_blank" rel="noopener noreferrer">Prendre Un RDV </a>
            </div>
        </div>
    </div>
    <div class="maquette-sidebar-blog-post">
        <div class="maquette-sidebar-blog-post-element">
            <h3 class="text-center">ARTICLES QUI POURRAIENT VOUS INTÉRESSER</h3>
            <br>
            <?php
            $args = array(
                'category__in' => wp_get_post_categories(get_queried_object_id()),
                'posts_per_page' => 5,
                'orderby'       => 'rand',
                'post__not_in' => array(get_queried_object_id())
            );
            $the_query = new WP_Query($args);

            if ($the_query->have_posts()) : ?>

                <ul class="">
                    <!-- the loop -->
                    <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>

                        <li>
                            <a class="maquette-sidebar-blog-text" href="<?php the_permalink(); ?>" title="Therapeute <?php the_title_attribute(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </li>
                        <div class="maquette-sidebar-blog-post-separator"></div>

                    <?php endwhile; ?>
                    <!-- end of the loop -->
                </ul>

                <?php wp_reset_postdata(); ?>

            <?php endif; ?>
            <div class="read-more">
                <a href="/blog" title="Therapeute " href="">Lire + d'articles </a>
            </div>
        </div>
    </div>
    <div class="maquette-sidebar-blog-image-element">
        <a href="<?php the_permalink(); ?>">
            <?php
            if (has_post_thumbnail()) {
                if ($blog_view == 'list_view') {
                    the_post_thumbnail('full');
                } else {
                    the_post_thumbnail('listingpro-thumb4');
                }
            } else {
                if ($blog_view == 'list_view') {
                    echo '<img title="Therapeute "  src="' . esc_html__('https://via.placeholder.com/1170x440', 'listingpro') . '" alt="image">';
                } else {
                    echo '<img title="Therapeute "  src="' . esc_html__('https://via.placeholder.com/270x270', 'listingpro') . '" alt="image">';
                }
            }
            ?>
        </a>
    </div>
    <div class="maquette-sidebar-blog-category">
        <div class="maquette-sidebar-blog-category-element">
            <ul>
                <li><a title="Therapeute " href="">eeeeeee</a></li>
                <li><a title="Therapeute " href="">sdsds</a></li>
            </ul>
        </div>
    </div>
</div>