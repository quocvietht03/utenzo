<?php
$avatar = get_field('avatar');
$desc = get_field('description');
$rating = get_field('rating');
$package = get_field('package');
?>
<article <?php post_class('bt-post'); ?>>


  <div class="bt-post--inner">
    <div class="bt-post--rating">
      <?php
      if (!empty($rating)) {
        for ($i = 1; $i <= 5; $i++) {
          if ($i <= $rating) {
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 18 17" fill="none">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M4.95601 9.87663L2.08257 7.74337C1.54023 7.35441 1.7781 6.57222 2.46633 6.48404L6.54179 6.3212L8.28219 2.4081C8.4035 2.19122 8.65009 2.05469 8.92046 2.05469C9.19084 2.05469 9.43743 2.19193 9.55874 2.4081L11.2991 6.3212L15.3746 6.48404C16.0628 6.57222 16.3007 7.35441 15.7584 7.74337L12.8849 9.87663L13.6691 14.0415C13.7595 14.6096 13.1014 15.0348 12.5297 14.7781L8.92046 12.721L5.31122 14.7774C4.73875 15.0341 4.08145 14.6089 4.17184 14.0408L4.95601 9.87663Z" fill="#FFB600"/>
                </svg>';
          } else {
            echo '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="17" viewBox="0 0 18 17" fill="none">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M4.95601 9.87663L2.08257 7.74337C1.54023 7.35441 1.7781 6.57222 2.46633 6.48404L6.54179 6.3212L8.28219 2.4081C8.4035 2.19122 8.65009 2.05469 8.92046 2.05469C9.19084 2.05469 9.43743 2.19193 9.55874 2.4081L11.2991 6.3212L15.3746 6.48404C16.0628 6.57222 16.3007 7.35441 15.7584 7.74337L12.8849 9.87663L13.6691 14.0415C13.7595 14.6096 13.1014 15.0348 12.5297 14.7781L8.92046 12.721L5.31122 14.7774C4.73875 15.0341 4.08145 14.6089 4.17184 14.0408L4.95601 9.87663Z" fill="#CDCDCD"/>
                </svg>';
          }
        }
      }
      ?>
    </div>
    <?php
    if (!empty($desc)) {
      echo '<div class="bt-post--desc">' . $desc . '</div>';
    }
    ?>
    <div class="bt-post--title">
      <?php the_title(); ?>
      <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_10935_2819)">
          <path d="M6.875 11.0801L8.75 12.9551L13.125 8.58008" stroke="#3DAB25" stroke-width="1.5"
            stroke-linecap="round" stroke-linejoin="round" />
          <path
            d="M10 17.9551C14.1421 17.9551 17.5 14.5972 17.5 10.4551C17.5 6.31294 14.1421 2.95508 10 2.95508C5.85786 2.95508 2.5 6.31294 2.5 10.4551C2.5 14.5972 5.85786 17.9551 10 17.9551Z"
            stroke="#3DAB25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </g>
        <defs>
          <clipPath id="clip0_10935_2819">
            <rect width="20" height="20" fill="white" transform="translate(0 0.455078)" />
          </clipPath>
        </defs>
      </svg>

    </div>
  </div>
  <div class="bt-post--infor">
    <div class="bt-post--avatar">
      <?php
      if (!empty($avatar)) {
        echo '<img src="' . esc_url($avatar['url']) . '" alt="' . esc_attr($avatar['title']) . '" />';
      }
      ?>
    </div>
    <div class="bt-post--meta">
      <?php 
      if ( !empty($package) ) {
        echo '<div class="bt-post--package">'. $package .'</div>';
      }
      ?>
      <div class="bt-post--date"><?php echo get_the_date();?></div>
    </div>
  </div>
</article>