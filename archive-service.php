<?php
get_header();
get_template_part('framework/templates/site', 'titlebar');

?>
<main id="bt_main" class="bt-site-main">
    <div class="bt-main-content-ss">
        <div class="bt-container bt-elwg-service-list">
            <?php
            if (have_posts()) {
            ?>
                <div class="bt-service-list bt-image-effect">
                    <?php
                    while (have_posts()) : the_post();
                        $img_1 = get_field('image_template_1', get_the_ID());
                        $img_1_url = !empty($img_1['url']) ? $img_1['url'] : '';
                        $service_types = get_field('service_types', get_the_ID());
                    ?>
                        <div class="bt-service-list-item bubble-container">
                            <a href="<?php the_permalink(); ?>" class="bt-post--img">
                                <?php if (!empty($img_1_url)) { ?>
                                    <img src="<?php echo esc_url($img_1_url) ?>" alt="">
                                <?php } ?>
                            </a>
                            <div class="bt-post--content">
                                <?php echo utenzo_post_title_render(); ?>
                                <?php if (!empty($service_types)): ?>
                                    <ul class="bt-service-types">
                                        <?php foreach ($service_types as $type): ?>
                                            <li class="bt-type-item"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M21.0573 7.00193C21.4381 7.40195 21.4225 8.03492 21.0225 8.41571L9.46695 19.4157C9.26961 19.6036 9.00417 19.7028 8.732 19.6904C8.45984 19.678 8.2045 19.5551 8.02505 19.3501L3.5806 14.2732C3.21682 13.8576 3.25879 13.2258 3.67434 12.8621C4.08989 12.4983 4.72166 12.5403 5.08544 12.9558L8.84314 17.2483L19.6435 6.9671C20.0436 6.58631 20.6765 6.60191 21.0573 7.00193Z" fill="#2D77DC" />
                                                </svg><?php echo esc_html($type['name']) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                <?php echo utenzo_service_button_book_now_render('Request An Estimate'); ?>
                            </div>

                            <!-- Small, medium, and large bubbles -->
                            <?php
                            for ($i = 1; $i <= 10; $i++):
                                switch ($i) {
                                    case 1:
                                    case 4:
                                    case 7:
                                        $bubble_size = 'small';
                                        break;
                                    case 2:
                                    case 5:
                                    case 8:
                                        $bubble_size = 'large';
                                        break;
                                    default:
                                        $bubble_size = 'medium';
                                        break;
                                }
                            ?>
                                <img class="bubble <?php echo esc_attr($bubble_size) ?>" src="<?php echo CLEANIRA_IMG_DIR . 'img-bubble-white.png'; ?>" alt="">
                            <?php endfor; ?>
                        </div>
                    <?php
                    endwhile;
                    ?>
                </div>
            <?php
                utenzo_paging_nav();
            } else {
                get_template_part('framework/templates/post', 'none');
            }
            ?>
        </div>
    </div>
</main><!-- #main -->

<?php get_footer(); ?>