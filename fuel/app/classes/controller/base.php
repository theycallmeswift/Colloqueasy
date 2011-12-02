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

    // Authorize the user
    self::authorize();
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

  private function authorize()
  {
    $this->template->set_global('logged_in', self::is_logged_in());
    $this->template->set_global('owns_profile', false);
  }

  public function is_logged_in()
  {
    return true;
  }

  public function is_owner($id = 0)
  {
    return self::is_logged_in() && true;
  }

  public function owner_only($id = 0)
  {
    if(!self::is_owner($id))
    {
        Session::set_flash('error', 'You are not authorized to access that function');
        Response::redirect('students');
    }
    $this->template->set_global('owns_profile', true);
  }

  public function logged_in_only()
  {
    if(!self::is_logged_in())
    {
        Session::set_flash('error', 'You must be logged in to access that function.');
        Response::redirect('/');
    }
  }

}
