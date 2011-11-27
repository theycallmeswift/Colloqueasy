<?php

class Controller_Base extends Controller_Template {

  public function before($data = null)
  {
    // Call the parent constructor for templating.
    parent::before();

    // Add jquery
    Asset::js('jquery-1.7.1.min.js', array(), 'jquery');

    // Add bootstrap assets
    self::add_bootstrap_assets();

    // Add the custom css
    Asset::css('custom.css', array(), 'custom_styles');

    // Load the shared layout files
    $this->template->header = View::forge('shared/header/default');
    $this->template->flash  = View::forge('shared/flash');
    $this->template->footer = View::forge('shared/footer/default');
  }

  private function add_bootstrap_assets()
  {
    $bootstrap_css = (Fuel::$env == Fuel::PRODUCTION) ? "bootstrap.min.css" : "bootstrap.css";
    Asset::css($bootstrap_css, array(), 'bootstrap_css');

    $bootstrap_js = array(
      'bootstrap-alerts.js',
      'bootstrap-dropdown.js',
      'bootstrap-tabs.js',
      'bootstrap-buttons.js',
      'bootstrap-modal.js',
      'bootstrap-scrollspy.js',
      'bootstrap-twipsy.js',
      'bootstrap-popover.js',
    );
    Asset::js($bootstrap_js, array(), 'bootstrap_js');
  }

}
