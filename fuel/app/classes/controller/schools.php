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

  public function action_search()
  {
    $data = array();

    $data['students'] = array();
    if (Input::get('submit', false))
    {
      $friends_only = (Input::get('friends_only', false) === '1') ? true : false;
      $name = strtolower(Input::get('name', ''));
      $city = strtolower(Input::get('city', ''));
      $state = strtolower(Input::get('state', ''));
      $degree = strtolower(Input::get('degree', ''));
      $major = strtolower(Input::get('major', ''));

      $query = "SELECT * FROM schools, education, students ";

      if($friends_only)
      {
        $query .= "LEFT JOIN friends ON friends.friend_id = students.id ";
      }

      $query .= "WHERE schools.id = education.school_id AND students.id = education.student_id ";

      if($friends_only)
      {
        $uid = self::current_user()->id;
        $query .= "AND friends.student_id = '$uid' ";
      }

      if(!empty($name))
      {
        $name = explode(",", $name);
        if(count($name) > 1)
        {
          $query .= "AND (";
          foreach($name as $key => $value)
          {
            if($key != 0) { $query .= "OR "; }
            $query .= "LOWER(schools.name) LIKE ".DB::escape("%$value%")." ";
          }
          $query .= ") ";
        }
        else
        {
          $query .= "AND LOWER(schools.name) LIKE ".DB::escape("%".$name[0]."%")." ";
        }
      }

      if(!empty($city))
      {
        $city = explode(",", $city);
        if(count($city) > 1)
        {
          $query .= "AND (";
          foreach($city as $key => $value)
          {
            if($key != 0) { $query .= "OR "; }
            $query .= "LOWER(schools.city) LIKE ".DB::escape("%$value%")." ";
          }
          $query .= ") ";
        }
        else
        {
          $query .= "AND LOWER(schools.city) LIKE ".DB::escape("%".$city[0]."%")." ";
        }
      }

      if(!empty($state))
      {
        $state = explode(",", $state);
        if(count($state) > 1)
        {
          $query .= "AND (";
          foreach($state as $key => $value)
          {
            if($key != 0) { $query .= "OR "; }
            $query .= "LOWER(schools.state) LIKE ".DB::escape("%$value%")." ";
          }
          $query .= ") ";
        }
        else
        {
          $query .= "AND LOWER(schools.state) LIKE ".DB::escape("%".$state[0]."%")." ";
        }
      }

      if(!empty($degree))
      {
        $degree = explode(",", $degree);
        if(count($degree) > 1)
        {
          $query .= "AND (";
          foreach($degree as $key => $value)
          {
            if($key != 0) { $query .= "OR "; }
            $query .= "LOWER(schools.degree) LIKE ".DB::escape("%$value%")." ";
          }
          $query .= ") ";
        }
        else
        {
          $query .= "AND LOWER(schools.degree) LIKE ".DB::escape("%".$degree[0]."%")." ";
        }
      }

      if(!empty($major))
      {
        $major = explode(",", $major);
        if(count($major) > 1)
        {
          $query .= "AND (";
          foreach($major as $key => $value)
          {
            if($key != 0) { $query .= "OR "; }
            $query .= "LOWER(schools.major) LIKE ".DB::escape("%$value%")." ";
          }
          $query .= ") ";
        }
        else
        {
          $query .= "AND LOWER(schools.major) LIKE ".DB::escape("%".$major[0]."%")." ";
        }
      }

      $data['students'] = \DB::query($query)->as_object()->execute();
    }

    $this->template->title = "Schools";
    $this->template->content = View::forge('schools/search', $data, false);

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
