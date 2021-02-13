<?php

class RA_Restrict_Access_Page_Walker extends Walker_Page {

  public function start_lvl(&$output, $depth = 0, $args = array()) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class='children' role='menu'>\n";
  }

  public function start_el(&$output, $page, $depth = 0, $args = array(), $current_page = 0) {

    $time = $page->post_modified;
    $date_format = empty($args['date_format']) ? '' : $args['date_format'];
    $status = get_post_meta($page->ID, '_ra_protected', true) ? 'protected' : '';

    if ($depth) {
      $indent = str_repeat("\t", $depth);
    } else {
      $indent = '';
    }

    $css_class = array('page_item', 'page-item-' . $page->ID);

    if (isset($args['pages_with_children'][$page->ID])) {
      $css_class[] = 'page_item_has_children';
    }

    if (!empty($current_page)) {

      $_current_page = get_post($current_page);

      if (in_array($page->ID, $_current_page->ancestors)) {
        $css_class[] = 'current_page_ancestor';
      }

      if ($page->ID == $current_page) {
        $css_class[] = 'current_page_item';
      } elseif ($_current_page && $page->ID == $_current_page->post_parent) {
        $css_class[] = 'current_page_parent';
      }

    } elseif ($page->ID == get_option('page_for_posts')) {

      $css_class[] = 'current_page_parent';

    }

    /**
     * Filter the list of CSS classes to include with each page item in the list.
     *
     * @since 2.8.0
     *
     * @see wp_list_pages()
     *
     * @param array   $css_class    An array of CSS classes to be applied
     *                             to each list item.
     * @param WP_Post $page         Page data object.
     * @param int     $depth        Depth of page, used for padding.
     * @param array   $args         An array of arguments.
     * @param int     $current_page ID of the current page.
     */
    $css_classes = implode(' ', apply_filters('page_css_class', $css_class, $page, $depth, $args, $current_page));

    if ('' === $page->post_title) {
      $page->post_title = sprintf(__( '#%d (no title)'), $page->ID);
    }

    $args['link_before'] = empty($args['link_before']) ? '' : $args['link_before'];
    $args['link_after'] = empty($args['link_after']) ? '' : $args['link_after'];

    $a_class = 'status-' . $status;

    $output .= $indent . sprintf(
        '<li class="%s"><a href="%s" class="' . $a_class . '" target="_blank">%s%s%s</a>',
        $css_classes,
        get_permalink($page->ID),
        $args['link_before'],
        apply_filters('the_title', $page->post_title, $page->ID),
        $args['link_after']
      );
      
      $output .= '' .

        '<div class="restrict-access-page-tree-item">' .             
        '<table class="restrict-access-page-tree-actions">' . 
        '<tr>' .
        
        '<td class="ra-content-page-modified">' .
          mysql2date($date_format, $time) .
        '</td>' .  

        '<td class="restrict-access-post-' . $page->ID . '">' .
          '<div class="restrict-access-post-status restrict-access-post-status-' . $status . '">' . ucfirst($status) . '</div>' . 
        '</td>' .
      
        '<td>' .
          '<form class="restrict-access-page-update">' .
            '<select class="restrict-access-select ra_select_v2" data-post-id="' . $page->ID . '">' .
              '<option value=""></option>' .
              '<option ' . ($status == 'protected' ? 'selected' : '') . ' value="logged_in">' . __('Logged in', 'restrict-access') . '</option>' .
            '</select>' . 
          '</form>' .
        '</td>' .
        '</tr>' . 
        '</table>' .
       
        '</div>' . 
        '<hr class="clear" />';

  }
    
}
