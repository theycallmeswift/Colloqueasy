<?php
use Model\Company;

class Controller_Companies extends Controller_Base
{
  public function before($data = null)
  {
    parent::before();
    self::ensure_logged_in_only();
  }

  public function action_index()
  {
    $data['companies'] = Company::find_all();
    $data['state_counts'] = \Model\Company::companies_by_state_count();
    $this->template->title = "Companies";
    $this->template->content = View::forge('companies/index', $data, false);

  }

  public function action_search()
  {
    $data = array();

    $data['employees'] = array();
    if (Input::get('submit', false))
    {
      $friends_only = (Input::get('friends_only', false) === '1') ? true : false;
      $name = strtolower(Input::get('name', ''));
      $city = strtolower(Input::get('city', ''));
      $state = strtolower(Input::get('state', ''));
      $position = strtolower(Input::get('position', ''));

      $query = "SELECT * FROM company, employers, students ";

      if($friends_only)
      {
        $query .= "LEFT JOIN friends ON friends.friend_id = students.id ";
      }

      $query .= "WHERE company.id = employers.company_id AND students.id = employers.student_id ";

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
            $query .= "LOWER(company.name) LIKE ".DB::escape("%$value%")." ";
          }
          $query .= ") ";
        }
        else
        {
          $query .= "AND LOWER(company.name) LIKE ".DB::escape("%".$name[0]."%")." ";
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
            $query .= "LOWER(company.city) LIKE ".DB::escape("%$value%")." ";
          }
          $query .= ") ";
        }
        else
        {
          $query .= "AND LOWER(company.city) LIKE ".DB::escape("%".$city[0]."%")." ";
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
            $query .= "LOWER(company.state) LIKE ".DB::escape("%$value%")." ";
          }
          $query .= ") ";
        }
        else
        {
          $query .= "AND LOWER(company.state) LIKE ".DB::escape("%".$state[0]."%")." ";
        }
      }

      if(!empty($position))
      {
        $position = explode(",", $position);
        if(count($position) > 1)
        {
          $query .= "AND (";
          foreach($position as $key => $value)
          {
            if($key != 0) { $query .= "OR "; }
            $query .= "LOWER(employers.position) LIKE ".DB::escape("%$value%")." ";
          }
          $query .= ") ";
        }
        else
        {
          $query .= "AND LOWER(employers.position) LIKE ".DB::escape("%".$position[0]."%")." ";
        }
      }
      $data['employees'] = \DB::query($query)->as_object()->execute();
    }

    $this->template->title = "Companies";
    $this->template->content = View::forge('companies/search', $data, false);

  }

  public function action_view($id = null)
  {
    $data['company'] = Company::find_one_by_id($id);

    if(!$data['company'])
    {
      throw new HttpNotFoundException;
    }

    $current_user = self::current_user();

    $val = Validation::forge();

    $val->add('position', 'Position')->add_rule('required');

    if ($val->run())
    {
      $start_date = Input::post('start_year', '2000') . "-" . Input::post('start_month', '01') . "-01";
      $end_date = Input::post('end_year', '2000') . "-" . Input::post('end_month', '01') . "-01";

      Company::create_employers_from_array(array(
        'company_id' => $data['company']->id,
        'student_id' => $current_user->id,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'position' => Input::post('position'),
      ));
      Session::set_flash('success', 'Successfully added employer!');
    }
    else
    {
      $errors = $val->error();
      Session::set_flash('error', $errors);
    }

    $data['employers'] = Company::find_employers_for($current_user->id, $data['company']->id);
    $data['employees'] = Company::get_employees($data['company']->id);
    $data['friends'] = Company::get_employees($data['company']->id, $current_user->id);

    $this->template->title = "Company";
    $this->template->content = View::forge('companies/view', $data, false);

  }

  public function action_create($id = null)
  {
    $val = Validation::forge();
    $val->add('name', 'Name')->add_rule('required');
    $val->add('city', 'City')->add_rule('required');
    $val->add('state', 'State')->add_rule('required');

    if ($val->run())
    {
      $school = Company::create_from_array(array(
        'name' => Input::post('name'),
        'city' => Input::post('city'),
        'state' => Input::post('state')
      ));

      Session::set_flash('success', 'Company was successfully added!');
      Response::redirect('companies');
    }
    else
    {
      $errors = $val->error();
      Session::set_flash('error', $errors);
    }

    $this->template->title = "Companies";
    $this->template->content = View::forge('companies/create');

  }

}
