<?php
use Model\School;

class Controller_Schools extends Controller_Base
{
  public function before($data = null)
  {
    parent::before();
    self::ensure_logged_in_only();
  }

  public function action_index()
  {
    $data['schools'] = School::find_all();
    $data['state_counts'] = \Model\School::schools_by_state_count();
    $this->template->title = "Schools";
    $this->template->content = View::forge('schools/index', $data, false);

  }

  public function action_view($id = null)
  {
    $data['school'] = School::find_one_by_id($id);

    if(!$data['school'])
    {
      throw new HttpNotFoundException;
    }

    $current_user = self::current_user();

    $val = Validation::forge();

    $val->add('degree', 'Degree')->add_rule('required');
    $val->add('major', 'Major')->add_rule('required');

    if ($val->run())
    {
      $start_date = Input::post('start_year', '2000') . "-" . Input::post('start_month', '01') . "-01";
      $end_date = Input::post('end_year', '2000') . "-" . Input::post('end_month', '01') . "-01";

      School::create_education_from_array(array(
        'school_id' => $data['school']->id,
        'student_id' => $current_user->id,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'degree' => Input::post('degree'),
        'major' => Input::post('major')
      ));
      Session::set_flash('success', 'Successfully added education!');
    }
    else
    {
      $errors = $val->error();
      Session::set_flash('error', $errors);
    }

    $data['education'] = School::find_education_for($current_user->id, $data['school']->id);
    $data['attendees'] = School::get_attendees($data['school']->id);
    $data['friends'] = School::get_attendees($data['school']->id, $current_user->id);

    $this->template->title = "School";
    $this->template->content = View::forge('schools/view', $data, false);

  }

  public function action_create($id = null)
  {
    $val = Validation::forge();
    $val->add('name', 'Name')->add_rule('required');
    $val->add('city', 'City')->add_rule('required');
    $val->add('state', 'State')->add_rule('required');

    if ($val->run())
    {
      $school = School::create_from_array(array(
        'name' => Input::post('name'),
        'city' => Input::post('city'),
        'state' => Input::post('state')
      ));

      Session::set_flash('success', 'School was successfully added!');
      Response::redirect('schools');
    }
    else
    {
      $errors = $val->error();
      Session::set_flash('error', $errors);
    }

    $this->template->title = "Schools";
    $this->template->content = View::forge('schools/create');

  }

}
