<?php

class Controller_Errors extends Controller_Template {

  public function action_404()
  {
    $this->template->title = 'Errors &raquo; 404';
    $this->template->content = View::forge('errors/404');
  }

}
