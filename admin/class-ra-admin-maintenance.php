<?php

class RestrictAccessAdminMaintenance {

  public function actions() {

    if (!get_option('restrict-access-sc')) {
      add_option('restrict-access-sc', md5(uniqid(rand(), true)), '', 'yes');
    }

  }

}
