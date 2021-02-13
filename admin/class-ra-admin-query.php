<?php

class RestrictAccessAdminQuery {

  public function restrict_access_request($request) {
  
    if (isset($request['pagename'])) {
  
      $uri = $request['pagename'];
      $post_id = url_to_postid($uri);
      $post = get_post($post_id); 
      
      $protected = get_post_meta($post_id, '_ra_protected', true);
      
      if ($protected) {
        
        if (!is_user_logged_in()) {
          auth_redirect();
          exit;
        }
        
      }
      
    }
  
    return $request;
  }

}
