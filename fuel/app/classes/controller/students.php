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
    $data['students'] = Student::find_all();
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
    $val = Validation::forge();

    $val->add('first_name', 'First name')->add_rule('required');
    $val->add('last_name', 'Last name')->add_rule('required');
    $val->add('email', 'Email')->add_rule('required')->add_rule('valid_email');
    $val->add('gender', 'Gender')->add_rule('required')->add_rule('match_pattern', '/^(male|female)$/');

    if ($val->run())
    {
        $student = Student::create_from_array(array(
          'first_name' => Input::post('first_name'),
          'last_name' => Input::post('last_name'),
          'email' => Input::post('email'),
          'gender' => Input::post('gender'),
          'created_at' => Date::forge()->get_timestamp(),
        ));

        Session::set_flash('success', 'Created student #' . $student->id);
        Response::redirect('students');
    }
    else
    {
      $errors = $val->error();
      Session::set_flash('error', $errors);
    }

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

    Response::redirect('students');
  }
}
