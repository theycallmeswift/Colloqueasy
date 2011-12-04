<?php
use Model\Message;

class Controller_Messages extends Controller_Base {

  public function before($data = null)
  {
    parent::before();
    self::ensure_logged_in_only();
  }
  /**
   * Index Action
   *
   * Lists all the messages sent and received for the current user
   */
  public function action_index()
  {
    $data['messages'] = Message::find_all_for_receiver(self::current_user()->id);
    $data['sent_messages'] = Message::find_all_for_sender(self::current_user()->id);
    $this->template->title = "Messages";
    $this->template->content = View::forge('messages/index', $data, false);
  }

  public function action_view($id = null)
  {
    $data['message'] = Message::find_one_by_id($id);
    $uid = self::current_user()->id;

    if(!$data['message'] || ($data['message']->receiver_id != $uid && $data['message']->sender_id != $uid))
    {
      throw new HttpNotFoundException;
    }

    if($data['message']->has_read == 0 && self::current_user()->id == $data['message']->receiver_id)
    {
      Message::mark_as_read($data['message']->id);
    }

    $this->template->title = "Message";
    $this->template->content = View::forge('messages/view', $data, false);

  }

  public function action_create($id = null)
  {
    $val = Validation::forge();
    $val->add('receiver_id', 'Receiver')->add_rule('required');
    $val->add('subject', 'Message subject')->add_rule('required');
    $val->add('body', 'Message body')->add_rule('required');

    if ($val->run())
    {
      $message = Message::create_from_array(array(
        'sender_id' => self::current_user()->id,
        'receiver_id' => Input::post('receiver_id'),
        'receiver_id' => Input::post('receiver_id'),
        'subject' => Input::post('subject'),
        'body' => Input::post('body'),
        'date' => Date::forge()->get_timestamp(),
        'has_read' => 0,
      ));

      Session::set_flash('success', 'Message was successfully sent!');
      Response::redirect('messages');
    }
    else
    {
      $errors = $val->error();
      Session::set_flash('error', $errors);
    }

    $data = array();
    $students = \Model\Student::find_all();
    foreach($students as $student)
    {
      $data["$student->id"] = "$student->first_name $student->last_name";
    }

    $this->template->set_global('students', $data, false);

    $this->template->title = "Messages";
    $this->template->content = View::forge('messages/create');

  }

  /**
   * Delete Action
   *
   * Handles deleting existing messages.
   */
  public function action_delete($id = null)
  {

    if ($message = Message::find_one_by_id($id))
    {
      self::ensure_owner_only($message->receiver_id);

      if(Message::delete_one_by_id($id) == 1)
      {
        Session::set_flash('success', 'Deleted message #' . $id);
      }
      else
      {
        Session::set_flash('error', 'Could not delete message #' . $id);
      }
    }
    else
    {
      throw new HttpNotFoundException;
    }

    Response::redirect('messages');
  }


}
