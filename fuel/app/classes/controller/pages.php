<?php

class Controller_Pages extends Controller_Base {

  public function action_index()
  {
    $this->template->title = 'Pages &raquo; Index';
    $this->template->content = View::forge('pages/index');
  }

  public function action_about()
  {
    $this->template->title = 'Pages &raquo; About';
    $this->template->content = View::forge('pages/about');
  }

  public function action_contact()
  {
    $this->template->title = 'Pages &raquo; Contact';
    $this->template->content = View::forge('pages/contact');
  }

}
