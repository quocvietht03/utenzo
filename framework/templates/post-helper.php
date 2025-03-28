<?php
/* Count post view. */
if (!function_exists('utenzo_set_count_view')) {
  function utenzo_set_count_view()
  {
    $post_id = get_the_ID();

    if (is_single() && !empty($post_id) && !isset($_COOKIE['utenzo_post_view_' . $post_id])) {
      $views = get_post_meta($post_id, '_post_count_views', true);
      $views = $views ? $views : 0;
      $views++;

      update_post_meta($post_id, '_post_count_views', $views);

      /* set cookie. */
      setcookie('utenzo_post_view_' . $post_id, $post_id, time() * 20, '/');
    }
  }
}
add_action('wp', 'utenzo_set_count_view');

/* Post count view */
if (!function_exists('utenzo_get_count_view')) {
  function utenzo_get_count_view()
  {
    $post_id = get_the_ID();
    $views = get_post_meta($post_id, '_post_count_views', true);

    $views = $views ? $views : 0;
    $label = $views > 1 ? esc_html__('Views', 'utenzo') : esc_html__('View', 'utenzo');
    return $views . ' ' . $label;
  }
}

/* Post Reading */
if (!function_exists('utenzo_reading_time_render')) {
  function utenzo_reading_time_render()
  {
    $content = get_the_content();
    $word_count = str_word_count(strip_tags($content));
    $readingtime = ceil($word_count / 200);

    return '<div class="bt-reading-time">' . $readingtime . ' min read' . '</div>';
  }
}

/* Single Post Title */
if (!function_exists('utenzo_single_post_title_render')) {
  function utenzo_single_post_title_render()
  {
    ob_start();
?>
    <h3 class="bt-post--title">
      <?php the_title(); ?>
    </h3>
  <?php

    return ob_get_clean();
  }
}

/* Post Title */
if (!function_exists('utenzo_post_title_render')) {
  function utenzo_post_title_render()
  {
    ob_start();
  ?>
    <h3 class="bt-post--title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>
    <?php

    return ob_get_clean();
  }
}

/* Post Featured */
if (!function_exists('utenzo_post_featured_render')) {
  function utenzo_post_featured_render($image_size = 'full')
  {
    ob_start();

    if (is_single()) {
    ?>
      <div class="bt-post--featured">
        <div class="bt-cover-image">
          <?php if (has_post_thumbnail()) {
            the_post_thumbnail($image_size);
          } ?>
        </div>
      </div>
    <?php
    } else {
    ?>
      <div class="bt-post--featured">
        <a href="<?php the_permalink(); ?>">
          <div class="bt-cover-image">
            <?php if (has_post_thumbnail()) {
              the_post_thumbnail($image_size);
            } ?>
          </div>
        </a>
      </div>
    <?php

    }

    return ob_get_clean();
  }
}

/* Post Cover Featured */
if (!function_exists('utenzo_post_cover_featured_render')) {
  function utenzo_post_cover_featured_render($image_size = 'full')
  {
    ob_start();
    ?>
    <div class="bt-post--featured">
      <a href="<?php the_permalink(); ?>">
        <div class="bt-cover-image">
          <?php
          if (has_post_thumbnail()) {
            the_post_thumbnail($image_size);
          }
          ?>
        </div>
      </a>
    </div>
  <?php

    return ob_get_clean();
  }
}

/* Post Publish */
if (!function_exists('utenzo_post_publish_render')) {
  function utenzo_post_publish_render()
  {
    ob_start();

  ?>
    <div class="bt-post--publish">
      <span> <?php echo get_the_date(get_option('date_format')); ?> </span>
    </div>
  <?php

    return ob_get_clean();
  }
}

/* Post Short Meta */
if (!function_exists('utenzo_post_short_meta_render')) {
  function utenzo_post_short_meta_render()
  {
    ob_start();

  ?>
    <div class="bt-post--meta">
      <?php
      the_terms(get_the_ID(), 'category', '<div class="bt-post-cat">', ', ', '</div>');
      echo utenzo_reading_time_render();
      ?>
    </div>
  <?php

    return ob_get_clean();
  }
}

/* Post Meta */
if (!function_exists('utenzo_post_meta_render')) {
  function utenzo_post_meta_render()
  {
    ob_start();

  ?>
    <ul class="bt-post--meta">
      <li class="bt-meta bt-meta--publish">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
          <path d="M16.25 2.5H14.375V1.875C14.375 1.70924 14.3092 1.55027 14.1919 1.43306C14.0747 1.31585 13.9158 1.25 13.75 1.25C13.5842 1.25 13.4253 1.31585 13.3081 1.43306C13.1908 1.55027 13.125 1.70924 13.125 1.875V2.5H6.875V1.875C6.875 1.70924 6.80915 1.55027 6.69194 1.43306C6.57473 1.31585 6.41576 1.25 6.25 1.25C6.08424 1.25 5.92527 1.31585 5.80806 1.43306C5.69085 1.55027 5.625 1.70924 5.625 1.875V2.5H3.75C3.41848 2.5 3.10054 2.6317 2.86612 2.86612C2.6317 3.10054 2.5 3.41848 2.5 3.75V16.25C2.5 16.5815 2.6317 16.8995 2.86612 17.1339C3.10054 17.3683 3.41848 17.5 3.75 17.5H16.25C16.5815 17.5 16.8995 17.3683 17.1339 17.1339C17.3683 16.8995 17.5 16.5815 17.5 16.25V3.75C17.5 3.41848 17.3683 3.10054 17.1339 2.86612C16.8995 2.6317 16.5815 2.5 16.25 2.5ZM5.625 3.75V4.375C5.625 4.54076 5.69085 4.69973 5.80806 4.81694C5.92527 4.93415 6.08424 5 6.25 5C6.41576 5 6.57473 4.93415 6.69194 4.81694C6.80915 4.69973 6.875 4.54076 6.875 4.375V3.75H13.125V4.375C13.125 4.54076 13.1908 4.69973 13.3081 4.81694C13.4253 4.93415 13.5842 5 13.75 5C13.9158 5 14.0747 4.93415 14.1919 4.81694C14.3092 4.69973 14.375 4.54076 14.375 4.375V3.75H16.25V6.25H3.75V3.75H5.625ZM16.25 16.25H3.75V7.5H16.25V16.25Z" fill="#212121" />
        </svg>
        <?php echo get_the_date(get_option('date_format')); ?>
      </li>
      <li class="bt-meta bt-meta--author">
        <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M18.0407 16.5624C16.8508 14.5054 15.0172 13.0304 12.8774 12.3312C13.9358 11.7011 14.7582 10.7409 15.2182 9.59821C15.6781 8.45548 15.7503 7.19337 15.4235 6.00568C15.0968 4.81798 14.3892 3.77039 13.4094 3.02378C12.4296 2.27716 11.2318 1.8728 10 1.8728C8.76821 1.8728 7.57044 2.27716 6.59067 3.02378C5.6109 3.77039 4.90331 4.81798 4.57654 6.00568C4.24978 7.19337 4.32193 8.45548 4.78189 9.59821C5.24186 10.7409 6.06422 11.7011 7.12268 12.3312C4.98284 13.0296 3.14925 14.5046 1.9594 16.5624C1.91577 16.6336 1.88683 16.7127 1.87429 16.7953C1.86174 16.8778 1.86585 16.962 1.88638 17.0429C1.9069 17.1238 1.94341 17.1997 1.99377 17.2663C2.04413 17.3328 2.10731 17.3886 2.17958 17.4304C2.25185 17.4721 2.33175 17.499 2.41457 17.5093C2.49738 17.5197 2.58143 17.5134 2.66176 17.4907C2.74209 17.4681 2.81708 17.4296 2.88228 17.3775C2.94749 17.3254 3.00161 17.2608 3.04143 17.1874C4.51331 14.6437 7.11487 13.1249 10 13.1249C12.8852 13.1249 15.4867 14.6437 16.9586 17.1874C16.9985 17.2608 17.0526 17.3254 17.1178 17.3775C17.183 17.4296 17.258 17.4681 17.3383 17.4907C17.4186 17.5134 17.5027 17.5197 17.5855 17.5093C17.6683 17.499 17.7482 17.4721 17.8205 17.4304C17.8927 17.3886 17.9559 17.3328 18.0063 17.2663C18.0566 17.1997 18.0932 17.1238 18.1137 17.0429C18.1342 16.962 18.1383 16.8778 18.1258 16.7953C18.1132 16.7127 18.0843 16.6336 18.0407 16.5624ZM5.62503 7.49993C5.62503 6.63464 5.88162 5.78877 6.36235 5.06931C6.84308 4.34984 7.52636 3.78909 8.32579 3.45796C9.12522 3.12682 10.0049 3.04018 10.8535 3.20899C11.7022 3.3778 12.4818 3.79448 13.0936 4.40634C13.7055 5.01819 14.1222 5.79774 14.291 6.64641C14.4598 7.49508 14.3731 8.37474 14.042 9.17417C13.7109 9.9736 13.1501 10.6569 12.4306 11.1376C11.7112 11.6183 10.8653 11.8749 10 11.8749C8.84009 11.8737 7.72801 11.4124 6.90781 10.5922C6.0876 9.77195 5.62627 8.65987 5.62503 7.49993Z" fill="#212121" />
          </svg>
          <?php echo esc_html__('By', 'utenzo') . ' ' . get_the_author(); ?>
        </a>
      </li>
    </ul>
    <?php
    return ob_get_clean();
  }
}

/* Post Category */
if (!function_exists('utenzo_post_category_render')) {
  function utenzo_post_category_render()
  {
    $post_id = get_the_ID();
    $categorys = get_the_terms($post_id, 'category');
    if ($categorys && !is_wp_error($categorys)) {
    ?>
      <div class="bt-post--category">
        <?php
        $category_links = array();
        foreach ($categorys as $category) {
          $category_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
        }
        echo implode(', ', $category_links);
        ?>
      </div>
    <?php
    }
  }
}
/* Post Content */
if (!function_exists('utenzo_post_content_render')) {
  function utenzo_post_content_render()
  {

    ob_start();

    if (is_single()) {
    ?>
      <div class="bt-post--content">
        <?php
        the_content();
        wp_link_pages(array(
          'before' => '<div class="page-links">' . esc_html__('Pages:', 'utenzo'),
          'after' => '</div>',
        ));
        ?>
      </div>
    <?php
    } else {
    ?>
      <div class="bt-post--excerpt">
        <?php echo get_the_excerpt(); ?>
      </div>
    <?php
    }

    return ob_get_clean();
  }
}

/* Post tag */
if (!function_exists('utenzo_tags_render')) {
  function utenzo_tags_render()
  {
    ob_start();
    if (has_tag()) {
    ?>
      <div class="bt-post-tags">
        <span><?php esc_html_e('Tag:', 'utenzo') ?></span>
        <?php
        if (has_tag()) {
          the_tags('', '', '');
        }
        ?>
      </div>
    <?php
    }
    return ob_get_clean();
  }
}

/* Post share */
if (!function_exists('utenzo_share_render')) {
  function utenzo_share_render()
  {

    $social_item = array();
    $social_item[] = '<li>
                        <a target="_blank" data-btIcon="fa fa-linkedin" data-toggle="tooltip" title="' . esc_attr__('Linkedin', 'utenzo') . '" href="https://www.linkedin.com/shareArticle?url=' . get_the_permalink() . '">
                          <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                            <path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/>
                          </svg>
                        </a>
                      </li>';
    $social_item[] = '<li>
                        <a target="_blank" data-btIcon="fa fa-facebook" data-toggle="tooltip" title="' . esc_attr__('Facebook', 'utenzo') . '" href="https://www.facebook.com/sharer/sharer.php?u=' . get_the_permalink() . '">
                          <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512">
                            <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/>
                          </svg>
                        </a>
                      </li>';
    $social_item[] = '<li>
                      <a target="_blank" data-btIcon="fa fa-twitter" data-toggle="tooltip" title="' . esc_attr__('Twitter', 'utenzo') . '" href="https://twitter.com/share?url=' . get_the_permalink() . '">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                          <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                        </svg>
                      </a>
                    </li>';
    $social_item[] = '<li>
                        <a target="_blank" data-btIcon="fa fa-pinterest" data-toggle="tooltip" title="' . esc_attr__('Pinterest', 'utenzo') . '" href="https://pinterest.com/pin/create/button/?url=' . get_the_permalink() . '&media=' . wp_get_attachment_url(get_post_thumbnail_id()) . '&description=' . get_the_title() . '">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="16" viewBox="0 0 13 16" fill="none">
                            <path d="M6.53967 0C3.2506 0 0 2.19271 0 5.74145C0 7.99827 1.26947 9.28056 2.03884 9.28056C2.3562 9.28056 2.53893 8.39578 2.53893 8.14574C2.53893 7.8476 1.77918 7.21287 1.77918 5.97226C1.77918 3.39486 3.74108 1.5676 6.28001 1.5676C8.4631 1.5676 10.0788 2.80821 10.0788 5.08748C10.0788 6.78972 9.39597 9.98261 7.18402 9.98261C6.3858 9.98261 5.70298 9.40558 5.70298 8.57851C5.70298 7.36675 6.54929 6.19345 6.54929 4.94322C6.54929 2.82103 3.53912 3.20572 3.53912 5.7703C3.53912 6.30886 3.60644 6.90512 3.84686 7.3956C3.40448 9.2998 2.50046 12.1369 2.50046 14.0988C2.50046 14.7046 2.58702 15.3009 2.64472 15.9068C2.75371 16.0286 2.69922 16.0158 2.86591 15.9549C4.4816 13.7429 4.42389 13.3102 5.1548 10.4154C5.5491 11.1655 6.56852 11.5694 7.37636 11.5694C10.7808 11.5694 12.31 8.25152 12.31 5.26059C12.31 2.07731 9.55946 0 6.53967 0Z" fill="#212121"/>
                          </svg>
                        </a>
                      </li>';

    ob_start();
    if (is_singular('post') && has_tag()) { ?>
      <div class="bt-post-share">
        <?php if (!empty($social_item)) {
          echo '<span>' . esc_html__('Share this post: ', 'utenzo') . '</span><ul>' . implode(' ', $social_item) . '</ul>';
        } ?>
      </div>

    <?php } elseif (!empty($social_item)) { ?>

      <div class="bt-post-share">
        <span><?php echo esc_html__('Share: ', 'utenzo'); ?></span>
        <ul><?php echo implode(' ', $social_item); ?></ul>
      </div>
    <?php }

    return ob_get_clean();
  }
}

/* Post Button */
if (!function_exists('utenzo_post_button_render')) {
  function utenzo_post_button_render($text)
  { ?>
    <div class="bt-post--button">
      <a href="<?php echo esc_url(get_permalink()) ?>">
        <span> <?php echo esc_html($text) ?> </span>
      </a>
    </div>
    <?php }
}
/* Book Now Button */
if (!function_exists('utenzo_service_button_book_now_render')) {
  function utenzo_service_button_book_now_render($text)
  {
    $site_infor = get_field('site_information', 'options') ?: '';
    if (!empty($site_infor) && isset($site_infor)) {
      if (!empty($site_infor['page_book_now'])) {
        $book_now = $site_infor['page_book_now'];
      } else {
        $book_now = '#';
      }
      if (!empty($site_infor['text_button_book_now'])) {
        $text_book_now = $site_infor['text_button_book_now'];
      } else {
        $text_book_now = $text;
      }
    ?>
      <div class="bt-post--button-booknow bt-button-hover">
        <a href="<?php echo esc_url($book_now); ?>" class="bt-primary-btn bt-button">
          <span class="bt-heading"> <?php echo esc_html($text_book_now) ?> </span>
          <?php echo utenzo_get_icon_svg_html('icon-arrow-right') ?>
        </a>
      </div>
    <?php }
  }
}

/* Author Icon */
if (!function_exists('utenzo_author_icon_render')) {
  function utenzo_author_icon_render()
  { ?>
    <div class="bt-post-author-icon">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
        <path d="M6.66634 5.83333C6.66634 7.67428 8.15876 9.16667 9.99967 9.16667C11.8406 9.16667 13.333 7.67428 13.333 5.83333C13.333 3.99238 11.8406 2.5 9.99967 2.5C8.15876 2.5 6.66634 3.99238 6.66634 5.83333Z" stroke="#C2A74E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        <path d="M9.99967 11.6667C13.2213 11.6667 15.833 14.2784 15.833 17.5001H4.16634C4.16634 14.2784 6.77801 11.6667 9.99967 11.6667Z" stroke="#C2A74E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
      <h4 class="bt-post-author-icon--name"> <?php echo esc_html__('By', 'utenzo') . ' ' . get_the_author(); ?> </h4>
    </div>
  <?php }
}
/* Author with avatar */
if (!function_exists('utenzo_author_w_avatar')) {
  function utenzo_author_w_avatar()
  {
    $author_id = get_the_author_meta('ID');
    if (function_exists('get_field')) {
      $avatar = get_field('avatar', 'user_' . $author_id);
    } else {
      $avatar = array();
    }
  ?>
    <div class="bt-post-author-w-avatar">
      <div class="bt-post-author-w-avatar--thumbnail">
        <?php
        if (!empty($avatar)) {
          echo '<img src="' . esc_url($avatar['url']) . '" alt="' . esc_attr($avatar['title']) . '" />';
        } else {
          echo get_avatar($author_id, 150);
        }
        ?>
      </div>

      <h4 class="bt-post-author-w-avatar--name"> <span><?php echo esc_html__('By ', 'utenzo') ?></span>
        <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>">
          <?php the_author(); ?>
        </a>
      </h4>
    </div>
  <?php }
}
/* Author */
if (!function_exists('utenzo_author_render')) {
  function utenzo_author_render()
  {
    $author_id = get_the_author_meta('ID');
    $desc = get_the_author_meta('description');

    if (function_exists('get_field')) {
      $avatar = get_field('avatar', 'user_' . $author_id);
      $job = get_field('job', 'user_' . $author_id);
      $socials = get_field('socials', 'user_' . $author_id);
    } else {
      $avatar = array();
      $job = '';
      $socials = array();
    }

    ob_start();
  ?>
    <div class="bt-post-author">
      <div class="bt-post-author--avatar">
        <?php
        if (!empty($avatar)) {
          echo '<img src="' . esc_url($avatar['url']) . '" alt="' . esc_attr($avatar['title']) . '" />';
        } else {
          echo get_avatar($author_id, 150);
        }
        ?>
      </div>
      <div class="bt-post-author--info">
        <h4 class="bt-post-author--name">
          <span class="bt-name">
            <?php the_author(); ?>
          </span>
          <?php
          if (!empty($job)) {
            echo '<span class="bt-label">' . $job . '</span>';
          }
          ?>
        </h4>
        <?php
        if (!empty($desc)) {
          echo '<div class="bt-post-author--desc">' . $desc . '</div>';
        }

        if (!empty($socials)) {
        ?>
          <div class="bt-post-author--socials">
            <?php
            foreach ($socials as $item) {
              if ($item['social'] == 'facebook') {
                echo '<a class="bt-' . esc_attr($item['social']) . '" href="' . esc_url($item['link']) . '" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512">
                          <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/>
                        </svg>
                      </a>';
              }

              if ($item['social'] == 'linkedin') {
                echo '<a class="bt-' . esc_attr($item['social']) . '" href="' . esc_url($item['link']) . '" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                          <path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/>
                        </svg>
                      </a>';
              }

              if ($item['social'] == 'twitter') {
                echo '<a class="bt-' . esc_attr($item['social']) . '" href="' . esc_url($item['link']) . '" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                          <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                        </svg>
                      </a>';
              }

              if ($item['social'] == 'google') {
                echo '<a class="bt-' . esc_attr($item['social']) . '" href="' . esc_url($item['link']) . '" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 640 512">
                          <path d="M386.061 228.496c1.834 9.692 3.143 19.384 3.143 31.956C389.204 370.205 315.599 448 204.8 448c-106.084 0-192-85.915-192-192s85.916-192 192-192c51.864 0 95.083 18.859 128.611 50.292l-52.126 50.03c-14.145-13.621-39.028-29.599-76.485-29.599-65.484 0-118.92 54.221-118.92 121.277 0 67.056 53.436 121.277 118.92 121.277 75.961 0 104.513-54.745 108.965-82.773H204.8v-66.009h181.261zm185.406 6.437V179.2h-56.001v55.733h-55.733v56.001h55.733v55.733h56.001v-55.733H627.2v-56.001h-55.733z"/>
                        </svg>
                      </a>';
              }
            }
            ?>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
    <?php
    return ob_get_clean();
  }
}


/* Related posts */
if (!function_exists('utenzo_related_posts')) {
  function utenzo_related_posts()
  {
    $post_id = get_the_ID();
    $cat_ids = array();
    $categories = get_the_category($post_id);

    if (!empty($categories) && !is_wp_error($categories)) {
      foreach ($categories as $category) {
        array_push($cat_ids, $category->term_id);
      }
    }

    $current_post_type = get_post_type($post_id);

    $query_args = array(
      'category__in'   => $cat_ids,
      'post_type'      => $current_post_type,
      'post__not_in'    => array($post_id),
      'posts_per_page'  => 2,
    );

    $list_posts = new WP_Query($query_args);

    ob_start();

    if ($list_posts->have_posts()) {
    ?>
      <div class="bt-related-posts">
        <div class="bt-related-posts--heading">
          <h4 class="bt-sub"><?php esc_html_e('From The Blog', 'utenzo'); ?></h4>
          <h2 class="bt-head"><?php esc_html_e('Related News ', 'utenzo'); ?><span><?php esc_html_e('& Articles', 'utenzo'); ?></span></h2>
        </div>
        <div class="bt-related-posts--list bt-image-effect">
          <?php
          while ($list_posts->have_posts()) : $list_posts->the_post();
            get_template_part('framework/templates/post', 'related');
          endwhile;
          wp_reset_postdata();
          ?>
        </div>
      </div>
    <?php
    }
    return ob_get_clean();
  }
}

//Comment Field Order
function utenzo_comment_fields_custom_order($fields)
{
  $comment_field = $fields['comment'];
  $author_field = $fields['author'];
  $email_field = $fields['email'];
  $cookies_field = $fields['cookies'];
  unset($fields['comment']);
  unset($fields['author']);
  unset($fields['email']);
  unset($fields['url']);
  unset($fields['cookies']);
  // the order of fields is the order below, change it as needed:
  $fields['author'] = $author_field;
  $fields['email'] = $email_field;
  $fields['comment'] = $comment_field;
  // done ordering, now return the fields:
  return $fields;
}
add_filter('comment_form_fields', 'utenzo_comment_fields_custom_order');

/* Custom comment list */
if (!function_exists('utenzo_custom_comment')) {
  function utenzo_custom_comment($comment, $args, $depth)
  {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
      $tag = 'div';
      $add_below = 'comment';
    } else {
      $tag = 'li';
      $add_below = 'div-comment';
    }
    ?>
    <<?php echo esc_html($tag); ?> <?php comment_class(empty($args['has_children']) ? 'bt-comment-item clearfix' : 'bt-comment-item parent clearfix') ?> id="comment-<?php comment_ID() ?>">
      <div class="bt-comment">
        <div class="bt-avatar">
          <?php
          if (function_exists('get_field')) {
            $avatar = get_field('avatar', 'user_' . $comment->user_id);
          } else {
            $avatar = array();
          }
          if (!empty($avatar)) {
            echo '<img src="' . esc_url($avatar['url']) . '" alt="' . esc_attr($avatar['title']) . '" />';
          } else {
            if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']);
          }


          ?>
        </div>
        <div class="bt-content">
          <h5 class="bt-name">
            <?php echo get_comment_author(get_comment_ID()); ?>
          </h5>
          <div class="bt-date">
            <?php echo get_comment_date(); ?>
          </div>
          <?php if ($comment->comment_approved == '0') : ?>
            <em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'utenzo'); ?></em>
          <?php endif; ?>
          <div class="bt-text">
            <?php comment_text(); ?>
          </div>
          <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
        </div>
      </div>
  <?php
  }
}
