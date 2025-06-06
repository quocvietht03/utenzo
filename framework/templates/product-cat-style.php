<?php
	$shop_page_url = get_permalink(wc_get_page_id('shop'));
    $category_url = $shop_page_url . '?product_cat=' . $args['category']->slug;
?>
<div class="bt-product-category--item">
    <a class="bt-product-category--inner" href="<?php echo esc_url($category_url); ?>">
        <div class="bt-product-category--thumb">
            <div class="bt-cover-image">
                <?php
                $thumbnail_id = get_term_meta($args['category']->term_id, 'thumbnail_id', true);
                if ($thumbnail_id) {
                    echo wp_get_attachment_image($thumbnail_id, $args['image-size'], false);
                } 
                ?>
            </div>
        </div>
        <h3 class="bt-product-category--name">
            <span><?php echo esc_html($args['category']->name); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20.7806 12.531L14.0306 19.281C13.8899 19.4218 13.699 19.5008 13.5 19.5008C13.301 19.5008 13.1101 19.4218 12.9694 19.281C12.8286 19.1403 12.7496 18.9494 12.7496 18.7504C12.7496 18.5514 12.8286 18.3605 12.9694 18.2198L18.4397 12.7504H3.75C3.55109 12.7504 3.36032 12.6714 3.21967 12.5307C3.07902 12.3901 3 12.1993 3 12.0004C3 11.8015 3.07902 11.6107 3.21967 11.4701C3.36032 11.3294 3.55109 11.2504 3.75 11.2504H18.4397L12.9694 5.78104C12.8286 5.64031 12.7496 5.44944 12.7496 5.25042C12.7496 5.05139 12.8286 4.86052 12.9694 4.71979C13.1101 4.57906 13.301 4.5 13.5 4.5C13.699 4.5 13.8899 4.57906 14.0306 4.71979L20.7806 11.4698C20.8504 11.5394 20.9057 11.6222 20.9434 11.7132C20.9812 11.8043 21.0006 11.9019 21.0006 12.0004C21.0006 12.099 20.9812 12.1966 20.9434 12.2876C20.9057 12.3787 20.8504 12.4614 20.7806 12.531Z" />
            </svg>
        </h3>
    </a>
</div>