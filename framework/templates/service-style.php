<?php
$post_id = get_the_ID();
$service_types = get_field('service_types', $post_id);
$img = get_field('image_template_2', get_the_ID());
?>
<article <?php post_class('bt-post'); ?>>
  <div class="bt-post--inner">
    <div class="bt-post--image">
      <a href="<?php the_permalink(); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="352" height="249" viewBox="0 0 352 249" fill="none">
          <path d="M351.024 0.710938H0.30127C5.96086 29.9623 26.3697 54.9589 50.5515 70.3824C64.1003 78.8918 79.1925 84.9194 94.6277 88.465C110.063 92.1879 126.527 91.8334 141.448 97.1518C151.395 100.697 157.569 106.902 163.743 115.412C168.202 121.439 172.833 126.403 179.35 129.949C192.899 137.217 202.503 135.799 221.711 134.026C239.719 132.253 258.756 137.749 275.048 146.613C289.798 154.591 299.573 165.05 307.977 179.942C317.238 196.606 317.753 216.462 328.9 232.063C336.618 242.699 342.964 246.777 351.196 248.904V0.710938H351.024Z" fill="#C1DCFB" />
        </svg>
        <?php if (!empty($img['url'])) { ?>
          <img src="<?php echo esc_url($img['url']) ?>" alt="">
        <?php } ?>
      </a>
    </div>
    <div class="bt-post--infor">
      <?php echo utenzo_post_title_render(); ?>
      <?php if (!empty($service_types)): ?>
        <ul class="bt-service-types">
          <?php foreach ($service_types as $type): ?>
            <li class="bt-type-item"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M20.9191 7.02146C21.2999 7.42148 21.2843 8.05445 20.8843 8.43524L9.32875 19.4352C9.13141 19.6231 8.86597 19.7223 8.5938 19.7099C8.32164 19.6975 8.06631 19.5746 7.88685 19.3696L3.4424 14.2927C3.07862 13.8772 3.12059 13.2454 3.53614 12.8816C3.95169 12.5178 4.58346 12.5598 4.94724 12.9753L8.70494 17.2678L19.5053 6.98664C19.9054 6.60585 20.5383 6.62144 20.9191 7.02146Z" fill="#2D77DC" />
              </svg><?php echo esc_html($type['name']) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

  </div>
</article>