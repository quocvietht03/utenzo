<?php
$logo = get_field('logo');
$link = get_field('link');

?>
<article <?php post_class('bt-post'); ?>>
  <?php if(!empty($link)) echo '<a href="' . esc_url($link['url']) . '" target="' . esc_attr($link['target']) . '">'; ?>
    <div class="bt-post--inner">
      <?php
        if(!empty($logo)) {
          echo '<img src="' . esc_url($logo['url']) . '" alt="' . esc_attr($logo['title']) . '" />';
        }
      ?>
    </div>
  <?php if(!empty($link)) echo '</a>'; ?>
</article>
