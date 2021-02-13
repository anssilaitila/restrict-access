<?php

class RestrictAccessAdminMain {

  public function add_main_menu_item() {

    if (is_super_admin()) {
      add_menu_page('Restrict Access', 'Restrict Access', 'manage_options', 'restrict-access', [$this, 'ra_main_view']);
    }
    
  }

  public function ra_main_view() {
    echo '<h1>' . __('Restrict Access', 'restrict-access') . '</h1>';

    ?>

      <div class="restrict-access-examples">
        <p><?php echo __('Any kind of feedback is welcome. You may contact the author at', 'restrict-access') ?> <a href="https://www.restrictaccesspro.com" target="_blank">restrictaccesspro.com</a>.</p>
        <p><?php echo __('Tip: After you have restricted the access to a page, open the link in an incognito window to see the behaviour.', 'restrict-access') ?></p>
      </div>

      <div class="restrict-access-admin-section">
      
        <h2><?php echo __('How to use the plugin', 'restrict-access') ?></h2>
      
        <p>          
          <?php echo __('Below is a list of all pages on your site. If a page is green, all visitors have access to it.', 'restrict-access') ?>
        </p>

        <p>          
          <?php echo __('When you select "Logged in" from the dropdown, that page\'s status changes to "Protected" and it is accessible to logged in users only.', 'restrict-access') ?>
        </p>
        
      </div>

      <div>
        
        <?php
          $args = [
              'title_li' => null,
              'post_type' => 'page',
              'echo' => false,
              'post_status' => ['publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit'],
              'sort_column' => 'post_title',
              'walker' => new RA_Restrict_Access_Page_Walker(),
            ];
        ?>
        
        <ul class="restrict-access-page-tree">

          <li class="restrict-access-page-tree-titles">
          
            <span>
              <?php echo __('Page name', 'restrict-access') ?>
            </span>
            
            <div class="restrict-access-page-tree-item">
              <table class="restrict-access-page-tree-actions">
                <tbody>
                  <tr>
                    <td class="ra-page-modified">
                      <?php echo __('Page modified', 'restrict-access') ?>
                    </td>
                    <td class="ra-status">
                      <?php echo __('Status', 'restrict-access') ?>
                    </td>
                    <td class="ra-restrict-access-to">
                      <?php echo __('Restrict access to', 'restrict-access') ?>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <hr class="clear">
          </li>

          <?php echo wp_list_pages($args) ?>

        </ul>
        
      </div>
        
    <?php
  }
  
  public function update_db_check() {
  
    $installed_version = get_site_option('restrict_access_version');
    
    if ($installed_version != RESTRICT_ACCESS_VERSION) {
    
      // ...
    
      update_option('restrict_access_version', RESTRICT_ACCESS_VERSION);
    }
  
  }

}

