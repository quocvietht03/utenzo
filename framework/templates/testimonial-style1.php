<?php
$avatar = get_field('avatar');
$job = get_field('job');
$desc = get_field('description');
$signature = get_field('signature');
$rating = get_field('rating');
?>
<article <?php post_class('bt-post'); ?>>


  <div class="bt-post--inner">
    <div class="bt-post--rating">
      <?php
      if (!empty($rating)) {
        for ($i = 1; $i <= 5; $i++) {
          if ($i <= $rating) {
            echo  '<span class="bt-filled"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path d="M22.4335 10.3123L17.7618 14.8662L18.8651 21.2981C18.9131 21.5794 18.7976 21.8636 18.5666 22.0316C18.4361 22.1269 18.2808 22.1749 18.1256 22.1749C18.0063 22.1749 17.8863 22.1464 17.7768 22.0886L12.0004 19.0519L6.22478 22.0879C5.97278 22.2214 5.66604 22.1996 5.43504 22.0309C5.20404 21.8629 5.08854 21.5786 5.13654 21.2974L6.23978 14.8655L1.56735 10.3123C1.36336 10.1128 1.28911 9.81433 1.37761 9.54359C1.4661 9.27284 1.70085 9.07409 1.9836 9.03284L8.44024 8.09536L11.3277 2.24395C11.5804 1.73171 12.4204 1.73171 12.6732 2.24395L15.5606 8.09536L22.0173 9.03284C22.3 9.07409 22.5348 9.27209 22.6233 9.54359C22.7118 9.81508 22.6375 10.1121 22.4335 10.3123Z" fill="#C2A74E"/>
</svg></span>';
          } else {
            echo '<span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
<path d="M22.4335 10.3123L17.7618 14.8662L18.8651 21.2981C18.9131 21.5794 18.7976 21.8636 18.5666 22.0316C18.4361 22.1269 18.2808 22.1749 18.1256 22.1749C18.0063 22.1749 17.8863 22.1464 17.7768 22.0886L12.0004 19.0519L6.22478 22.0879C5.97278 22.2214 5.66604 22.1996 5.43504 22.0309C5.20404 21.8629 5.08854 21.5786 5.13654 21.2974L6.23978 14.8655L1.56735 10.3123C1.36336 10.1128 1.28911 9.81433 1.37761 9.54359C1.4661 9.27284 1.70085 9.07409 1.9836 9.03284L8.44024 8.09536L11.3277 2.24395C11.5804 1.73171 12.4204 1.73171 12.6732 2.24395L15.5606 8.09536L22.0173 9.03284C22.3 9.07409 22.5348 9.27209 22.6233 9.54359C22.7118 9.81508 22.6375 10.1121 22.4335 10.3123Z" fill="#CDCDCD"/>
</svg></span>';
          }
        }
      }
      ?>
    </div>
    <?php if (!empty($desc)) { ?>
      <div class="bt-post--desc-wrap">
        <div class="bt-post--quote-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="82" height="82" viewBox="0 0 82 82" fill="none">
            <g opacity="0.3">
              <path d="M0 51.2336H20.8444L14.0179 65.2203V71.8156L38.5236 51.2336V12.71H0L0 51.2336Z" fill="#CCCCCC" />
              <path d="M50.5518 19.9768V51.2336H67.4634L61.9235 62.5824V67.9313L81.8061 51.2336V19.9768H50.5518Z" fill="#CCCCCC" />
            </g>
          </svg>
        </div>
        <?php

        echo '<div class="bt-post--desc">' . $desc . '</div>';
        ?>
      </div>
    <?php
    }
    ?>
    <div class="bt-post--infor">
      <div class="bt-post--avatar">
        <?php
        if (!empty($avatar)) {
          echo '<img src="' . esc_url($avatar['url']) . '" alt="' . esc_attr($avatar['title']) . '" />';
        }
        ?>
      </div>
      <div class="bt-post--title-wrap">
        <div class="bt-post--signature">
          <?php
          if (!empty($signature)) {
            echo '<img src="' . esc_url($signature['url']) . '" alt="' . esc_attr($signature['title']) . '" />';
          }
          ?>
        </div>
        <?php
        if (!empty($job)) {
          echo '<div class="bt-post--title-job">' . get_the_title() . ' - ' . $job . '</div>';
        }
        ?>
      </div>
    </div>
  </div>

</article>