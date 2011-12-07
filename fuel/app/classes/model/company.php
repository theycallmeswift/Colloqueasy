<?php

namespace Model;

class Company
{
  protected static $_table_name = 'company';

  /**
   * find_all()
   *
   * Finds all the companies in the companies table
   * Ordered by state.
   *
   * SELECT * FROM `company` ORDER BY state ASC
   */
  public static function find_all()
  {
    return \DB::query("SELECT * FROM `company` ORDER BY state ASC")->as_object()->execute();
  }

  /**
   * companies_by_state_count()
   *
   * Finds the count of companies for each state
   *
   * SELECT state, COUNT(*) as count FROM `company` GROUP BY state ORDER BY state ASC
   */
  public static function companies_by_state_count()
  {
    return \DB::query("SELECT state, COUNT(*) as count FROM `company` GROUP BY state ORDER BY state ASC")->as_object()->execute();
  }

  /**
   * find_one_by_id()
   *
   * Finds the company with the supplied ID or returns false
   *
   * SELECT * FROM `company` WHERE `id` = '1234' LIMIT 1
   */
  public static function find_one_by_id($id = null)
  {
    $id = \DB::escape($id);
    $result = \DB::query("SELECT * FROM `company` WHERE `id` = $id LIMIT 1")->as_object()->execute();
    if (count($result) != 1)
    {
      return false;
    }
    return $result[0];
  }

  /**
   * create_from_array()
   *
   * Takes in an array of keys and values. Generates an INSERT
   * statement from them accordingly.
   *
   * INSERT INTO `company` ('name', 'city', 'state', ...)
   * VALUES ('crowdtap', 'New Brunswick', 'New Jersey', ...)
   */
  public static function create_from_array($attributes = array())
  {
    $columns = "(";
    $values = "(";

    foreach($attributes as $column => $value)
    {
      $columns .= "$column,";
      $values  .= \DB::escape($value).",";
    }
    $columns[strlen($columns)-1] = ")";
    $values[strlen($values)-1] = ")";

    return \DB::query("INSERT INTO `company` $columns VALUES $values")->execute();
  }

  /**
   * create_employers_from_array()
   *
   * Takes in an array of keys and values. Generates an INSERT
   * statement from them accordingly.
   *
   * INSERT INTO `employers` ('student_id', 'company_id', 'start_date, ...)
   * VALUES ('123', '456', '2008-09-01', ...)
   */
  public static function create_employers_from_array($attributes = array())
  {
    $columns = "(";
    $values = "(";

    foreach($attributes as $column => $value)
    {
      $columns .= "$column,";
      $values  .= \DB::escape($value).",";
    }
    $columns[strlen($columns)-1] = ")";
    $values[strlen($values)-1] = ")";

    return \DB::query("INSERT INTO `employers` $columns VALUES $values")->execute();
  }

  /**
   * find_employers_for()
   *
   * Finds the employers for the student with the supplied ID and company
   * with the supplied id. If no company id is supplied, return all companies
   * the user has worked for.
   *
   * SELECT * FROM `employers` WHERE `student_id` = '1234' AND `company_id` = '567' LIMIT 1
   */
  public static function find_employers_for($student_id = 0, $company_id = false)
  {
    $student_id = \DB::escape($student_id);
    if($company_id)
    {
      $company_id = \DB::escape($company_id);
      $query = "SELECT * FROM `employers` WHERE `student_id` = $student_id AND `company_id` = $company_id LIMIT 1";
      $result = \DB::query($query)->as_object()->execute();
      if (count($result) != 1)
      {
        return false;
      }
      return $result[0];
    }
    else
    {
      $query = "SELECT * FROM `employers`, `company` WHERE employers.company_id = company.id AND employers.student_id = $student_id ORDER BY employers.start_date ASC";
      $result = \DB::query($query)->as_object()->execute();
      return $result;
    }
  }

  /**
   * get_employees()
   *
   * Returns the employees for a given company
   * If the second parameter contains an id,
   * it only finds people who worked there who are
   * friends with that id.
   *
   * SELECT * FROM employers
   * LEFT JOIN students ON employers.student_id = students.id 
   * WHERE employers.company_id = '123'
   *
   * - OR -
   *
   * SELECT * FROM employers
   * LEFT JOIN friends ON employers.student_id = friends.friend_id
   * LEFT JOIN students ON friends.friend_id = students.id 
   * WHERE employers.company_id = '123'
   * AND friends.student_id = '456'
   */
  public static function get_employees($company_id = 0, $student_id = false)
  {
    $company_id = \DB::escape($company_id);
    if($student_id)
    {
      $student_id = \DB::escape($student_id);
      $query = "SELECT * FROM employers
      LEFT JOIN friends ON employers.student_id = friends.friend_id
      LEFT JOIN students ON friends.friend_id = students.id 
      WHERE employers.company_id = $company_id AND friends.student_id = $student_id";
    }
    else
    {
      $query = "SELECT * FROM employers
      LEFT JOIN students ON employers.student_id = students.id
      WHERE employers.company_id = $company_id";
    }
    return \DB::query($query)->as_object()->execute();
  }
}
