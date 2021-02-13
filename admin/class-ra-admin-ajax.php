<?php

class RestrictAccessAdminAjax {
  
  public function ra_restrict_access() {

    $html = '';
    
    if (isset($_POST['post_id']) && is_super_admin()) {
      
      $post_id = (int) $_POST['post_id'];
      
      $term_slug = '';
  
      if (isset($_POST['restrict_to']) && $_POST['restrict_to']) {
        $term_slug = sanitize_title($_POST['restrict_to']);
      }
      
      if ($term_slug == 'logged_in') {

        update_post_meta($post_id, '_ra_protected', 1);
    
        $html .= '<div class="restrict-access-post-status restrict-access-post-status-protected">' . __('Protected', 'restrict-access') . '</div>';
        
      } elseif (!$term_slug) {

        update_post_meta($post_id, '_ra_protected', 0);
        
        $html .= '<div class="restrict-access-post-status"></div>';
        
      }

    }

    echo $html;
  }

}
