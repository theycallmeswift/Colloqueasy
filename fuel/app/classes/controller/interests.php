<?php
use Model\Interest;

class Controller_Interests extends Controller_Base
{
  public function before($data = null)
  {
    parent::before();
    self::ensure_logged_in_only();
  }

  public function action_index()
  {
    $data = array();
    $uid = self::current_user()->id;

    if(Input::get('submit', false))
    {
        $search = strtolower(Input::get('search', false));
        $friends_only = (Input::get('friends_only', false) === '1') ? true : false;

        $query = "SELECT interest.id, interest.name, interest.description";

        if($friends_only)
        {
          $query .= ", COUNT(friends.friend_id) as friend_count";
        }

        $query .= ", (SELECT COUNT(*) >= 1 FROM interested_in WHERE student_id = '$uid' GROUP BY interested_in.interest_id HAVING interest_id = interest.id) as is_interested";
        $query .= " FROM interest ";

        if($friends_only)
        {
          $query .= "LEFT JOIN interested_in ON interest.id = interested_in.interest_id ";
          $query .= "LEFT JOIN friends ON friends.friend_id = interested_in.student_id ";
        }

        if($friends_only || !empty($search))
        {
          $query .= "WHERE ";
        }

        if($friends_only)
        {
          $query .= "friends.student_id = '$uid' ";
        }

        if(!empty($search))
        {
          if($friends_only)
          {
            $query .= "AND ( ";
          }

          $search = explode(",", $search);
          if(count($search) > 1)
          {
            foreach($search as $key => $value)
            {
              if($key != 0) { $query .= "OR "; }
                $query .= "LOWER(interest.name) LIKE ".DB::escape("%$value%").
                " OR LOWER(interest.description) LIKE ".DB::escape("%$value%"). " ";
            }
          }
          else
          {
            $query .= "LOWER(interest.name) LIKE ".DB::escape("%".$search[0]."%").
              " OR LOWER(interest.description) LIKE ".DB::escape("%".$search[0]."%");
          }

          if($friends_only)
          {
            $query .= ") ";
          }
        }

        $data['friends_only'] = false;
        if($friends_only)
        {
          $query .= "GROUP BY interest.id, friends.student_id ";
          $data['friends_only'] = true;
        }
        $query .= "ORDER BY interest.name ASC";
        $data['interests'] = DB::query($query)->as_object()->execute();
    }
    else
    {
      $data['friends_only'] = false;
      $data['interests'] = Interest::find_all($uid);
    }
    $this->template->title = "Interests";
    $this->template->content = View::forge('interests/index', $data, false);

  }

  public function action_create($id = null)
  {
    $val = Validation::forge();
    $val->add('name', 'Name')->add_rule('required');
    $val->add('description', 'Description')->add_rule('required');

    if ($val->run())
    {
      $school = Interest::create_from_array(array(
        'name' => Input::post('name'),
        'description' => Input::post('description')
      ));

      Session::set_flash('success', 'Interest was successfully added!');
      Response::redirect('interests');
    }
    else
    {
      $errors = $val->error();
      Session::set_flash('error', $errors);
    }

    $this->template->title = "Interests";
    $this->template->content = View::forge('interests/create');

  }

  public function action_add_interest($id = 0)
  {
    $current_student = self::current_user();
    $target_interest = Interest::find_one_by_id($id);
    if(!$target_interest)
    {
      throw new HttpNotFoundException;
    }

    if(!Interest::is_interested($current_student->id, $target_interest->id))
    {
      Interest::add_interest($current_student->id, $target_interest->id);
      Session::set_flash('success', "You are now interested in $target_interest->name!");
    }
    else
    {
        Session::set_flash('error', 'You are already interested in that.');
    }
    Response::redirect('interests');
  }

  public function action_remove_interest($id = 0)
  {
    $current_student = self::current_user();
    $target_interest = Interest::find_one_by_id($id);
    if(!$target_interest)
    {
      throw new HttpNotFoundException;
    }

    if(Interest::is_interested($current_student->id, $target_interest->id))
    {
      Interest::remove_interest($current_student->id, $target_interest->id);
      Session::set_flash('success', "You are now not interested in $target_interest->name!");
    }
    else
    {
        Session::set_flash('error', 'You are not already interested in that.');
    }
    Response::redirect('interests');
  }
}
