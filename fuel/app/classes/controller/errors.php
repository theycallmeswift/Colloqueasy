<?php

class Controller_Errors extends Controller_Base {

  public function action_404()
  {
    Session::set_flash("warning", "Woops! It looks like the page you were looking for was not found!");
    $this->template->title = 'Errors &raquo; 404';
    $this->template->content = View::forge('errors/404');
  }

  public function action_401()
  {
    Session::set_flash("error", "Uh oh! You are not authorzed to access that page!");
    $this->template->title = 'Errors &raquo; 401';
    $this->template->content = View::forge('errors/401');
  }

}
