<?php
use Model\Student;

class Controller_Students extends Controller_Base
{

  /**
   * Index Action
   *
   * Lists all the students
   */
  public function action_index()
  {
    $data['students'] = Student::get_profile_summaries();
    $this->template->title = "Students";
    $this->template->content = View::forge('students/index', $data, false);
  }

  /**
   * View Action
   *
   * Shows the profile of one student
   */
  public function action_view($id = null)
  {
    $data['student'] = Student::find_one_by_id($id);

    if(!$data['student'])
    {
      throw new HttpNotFoundException;
    }

    if(self::is_owner($id))
    {
      $this->template->set_global('owns_profile', true);
    }

    $current_student = self::current_user();
    $data['are_friends'] = Student::are_friends($current_student->id, $data['student']->id);
    $data['are_in_relationship'] = Student::are_in_relationship($current_student->id, $data['student']->id);

    $data['friends'] = Student::get_friends($id);
    $data['schools'] = \Model\School::find_education_for($id);

    $this->template->title = "Student";
    $this->template->content = View::forge('students/view', $data, false);
  }

  /**
   * Create Action
   *
   * Shows the form to create a new student, also handles validation
   * and insertion into the database.
   */
  public function action_create($id = null)
  {
    if(self::is_logged_in())
    {
      $id = self::current_user()->id;
      Response::redirect("students/edit/$id");
    }

    self::ensure_logged_in_with_facebook_only();
    try {
      // Proceed knowing you have a logged in user who's authenticated.
      $student = (object) \Social\Facebook::instance()->api('/me');
      $this->template->set_global('student', $student, false);
    } catch (FacebookApiException $e) {
      // Don't do anything. Facebook being stupid.
    }

    $val = Validation::forge();

    $val->add('first_name', 'First name')->add_rule('required');
    $val->add('last_name', 'Last name')->add_rule('required');
    $val->add('email', 'Email')->add_rule('required')->add_rule('valid_email');
    $val->add('gender', 'Gender')->add_rule('required')->add_rule('match_pattern', '/^(male|female)$/');

    if ($val->run())
    {
        $student = Student::create_from_array(array(
          'facebook_id' => \Social\Facebook::instance()->getUser(),
          'first_name' => Input::post('first_name'),
          'last_name' => Input::post('last_name'),
          'email' => Input::post('email'),
          'gender' => Input::post('gender'),
          'created_at' => Date::forge()->get_timestamp(),
          'bio' => Input::post('bio'),
        ));

        Session::set_flash('success', 'Created student #' . $student->id);
        Response::redirect('students');
    }
    else
    {
      $errors = $val->error();
      Session::set_flash('error', $errors);
    }

    $this->template->set_global('bio', Input::post("bio", ""), false);
    $this->template->title = "Students";
    $this->template->content = View::forge('students/create');
  }

  /**
   * Edit Action
   *
   * Shows the form to edit an existing student, also handles validation
   * and updating in the database.
   */
  public function action_edit($id = null)
  {
    self::ensure_owner_only($id);

    $student = Student::find_one_by_id($id);

    if ($student)
    {

      $val = Validation::forge();

      $val->add('first_name', 'First name')->add_rule('required');
      $val->add('last_name', 'Last name')->add_rule('required');
      $val->add('email', 'Email')->add_rule('required')->add_rule('valid_email');
      $val->add('gender', 'Gender')->add_rule('required')->add_rule('match_pattern', '/^(male|female)$/');

      if ($val->run())
      {
        Student::update_from_array($id, array(
          'first_name' => Input::post('first_name'),
          'last_name' => Input::post('last_name'),
          'email' => Input::post('email'),
          'gender' => Input::post('gender'),
          'updated_at' => Date::forge()->get_timestamp(),
          'bio' => Input::post('bio'),
        ));

        Session::set_flash('success', 'Updated student #' . $id);
        Response::redirect('students');

      }
      else
      {
        $errors = $val->error();
        Session::set_flash('error', $errors);
      }
    }
    else
    {
      throw new HttpNotFoundException;
    }

    $this->template->set_global('student', $student, false);
    $this->template->set_global('bio', $student->bio, false);
    $this->template->title = "Students";
    $this->template->content = View::forge('students/edit');
  }


  /**
   * Delete Action
   *
   * Handles deleting existing students.
   */
  public function action_delete($id = null)
  {
    self::ensure_owner_only($id);

    if ($student = Student::find_one_by_id($id))
    {
      if(Student::delete_one_by_id($id) == 1)
      {
        Session::set_flash('success', 'Deleted student #' . $id);
      }
      else
      {
        Session::set_flash('error', 'Could not delete student #' . $id);
      }
    }
    else
    {
      throw new HttpNotFoundException;
    }

    Response::redirect('logout');
  }


  /**
   * Add Friend Action
   *
   * Handles adding a friendship
   */
  public function action_add_friend($id = 0)
  {
    self::ensure_logged_in_only();
    $current_student = self::current_user();
    $target_student = Student::find_one_by_id($id);
    if(!$target_student)
    {
      throw new HttpNotFoundException;
    }

    if(!Student::are_friends($current_student->id, $target_student->id))
    {
      Student::add_friendship($current_student->id, $target_student->id);
      Session::set_flash('success', "You are now friends with $target_student->first_name!");
    }
    else
    {
        Session::set_flash('error', 'You are already friends with that student');
    }
    Response::redirect('students');
  }

  /**
   * Remove Friend Action
   *
   * Handles removinging a friendship
   */
  public function action_remove_friend($id = 0)
  {
    self::ensure_logged_in_only();
    $current_student = self::current_user();
    $target_student = Student::find_one_by_id($id);
    if(!$target_student)
    {
      throw new HttpNotFoundException;
    }

    if(Student::are_friends($current_student->id, $target_student->id))
    {
      Student::remove_friendship($current_student->id, $target_student->id);
      Session::set_flash('success', "You are now NOT friends with $target_student->first_name!");
    }
    else
    {
        Session::set_flash('error', 'You are not already friends with that student');
    }
    Response::redirect('students');
  }

  /**
   * Add Relationship Action
   *
   * Handles adding a relationship
   */
  public function action_add_relationship($id = 0)
  {
    self::ensure_logged_in_only();
    $current_student = self::current_user();
    $target_student = Student::find_one_by_id($id);
    if(!$target_student)
    {
      throw new HttpNotFoundException;
    }

    if(!Student::are_in_relationship($current_student->id, $target_student->id))
    {
      Student::add_relationship($current_student->id, $target_student->id);
      Session::set_flash('success', "You are now in a relationship with $target_student->first_name!");
    }
    else
    {
        Session::set_flash('error', 'You are already in a relationship with that student');
    }
    Response::redirect('students');
  }

  /**
   * Remove Relationship Action
   *
   * Handles removinging a relationship
   */
  public function action_remove_relationship($id = 0)
  {
    self::ensure_logged_in_only();
    $current_student = self::current_user();
    $target_student = Student::find_one_by_id($id);
    if(!$target_student)
    {
      throw new HttpNotFoundException;
    }

    if(Student::are_friends($current_student->id, $target_student->id))
    {
      Student::remove_relationship($current_student->id, $target_student->id);
      Session::set_flash('success', "You are now NOT in a relationship with $target_student->first_name!");
    }
    else
    {
        Session::set_flash('error', 'You are not already in a relationship with that student');
    }
    Response::redirect('students');
  }

}
