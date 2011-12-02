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
    $header_data = array();

    if(self::is_logged_in())
    {
      $header_partial = 'shared/header/default';
      $header_data['fb_login_url'] = \Social\Facebook::instance()->getLogoutUrl();
      //\Social\Facebook::instance()->destroySession();
    }
    else
    {
      $fb_permissions = array(
        'scope' => 'email'
      );

      $header_partial = 'shared/header/default';
      $header_data['fb_login_url'] = \Social\Facebook::instance()->getLoginUrl($fb_permissions);
    }

    $this->template->header = View::forge($header_partial, $header_data);
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
    $logged_in = self::is_logged_in();
    $this->template->set_global('logged_in', $logged_in);
    $this->template->set_global('owns_profile', false);

    // If the user isn't logged in but they are authorized with
    // facebook, then they need to create an account.
    if (!$logged_in && self::is_logged_in_with_facebook() && Uri::string() != "students/create")
    {
      Response::redirect("students/create");
    }
  }

  public function is_logged_in()
  {
    // Check if the user is logged in with Facebook
    $facebook_id = \Social\Facebook::instance()->getUser();
    if($facebook_id)
    {
      $user = \Model\Student::find_one_by_facebook_id($facebook_id);
    }
    else
    {
      $user = false;
    }
    return $user ? true : false;
  }

  public function is_logged_in_with_facebook()
  {
    return \Social\Facebook::instance()->getUser() ? true : false;
  }

  public function is_owner($id = 0)
  {
    return self::is_logged_in() && true;
  }

  public function ensure_owner_only($id = 0)
  {
    if(!self::is_owner($id))
    {
        Session::set_flash('error', 'You are not authorized to access that function');
        Response::redirect('students');
    }
    $this->template->set_global('owns_profile', true);
  }

  public function ensure_logged_in_with_facebook_only()
  {
    if(!self::is_logged_in_with_facebook())
    {
      $fb_login_url = \Social\Facebook::instance()->getLoginUrl();
      Response::redirect($fb_login_url);
    }
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
