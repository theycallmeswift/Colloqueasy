<?php

namespace Model;

class School
{
  protected static $_table_name = 'schools';

  /**
   * find_all()
   *
   * Finds all the schools in the schoolss table
   * Ordered by state.
   *
   * SELECT * FROM `schools` ORDER BY state ASC
   */
  public static function find_all()
  {
    return \DB::query("SELECT * FROM `schools` ORDER BY state ASC")->as_object()->execute();
  }

  /**
   * schools_by_state_count()
   *
   * Finds the count of schools for each state
   *
   * SELECT state, COUNT(*) as count FROM `schools` GROUP BY state ORDER BY state ASC
   */
  public static function schools_by_state_count()
  {
    return \DB::query("SELECT state, COUNT(*) as count FROM `schools` GROUP BY state ORDER BY state ASC")->as_object()->execute();
  }

  /**
   * find_one_by_id()
   *
   * Finds the school with the supplied ID or returns false
   *
   * SELECT * FROM `schools` WHERE `id` = '1234' LIMIT 1
   */
  public static function find_one_by_id($id = null)
  {
    $id = \DB::escape($id);
    $result = \DB::query("SELECT * FROM `schools` WHERE `id` = $id LIMIT 1")->as_object()->execute();
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
   * INSERT INTO `schools` ('name', 'city', 'state', ...)
   * VALUES ('rutgers', 'New Brunswick', 'New Jersey', ...)
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

    return \DB::query("INSERT INTO `schools` $columns VALUES $values")->execute();
  }

  /**
   * create_education_from_array()
   *
   * Takes in an array of keys and values. Generates an INSERT
   * statement from them accordingly.
   *
   * INSERT INTO `education` ('student_id', 'school_id', 'start_date, ...)
   * VALUES ('123', '456', '2008-09-01', ...)
   */
  public static function create_education_from_array($attributes = array())
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

    return \DB::query("INSERT INTO `education` $columns VALUES $values")->execute();
  }

  /**
   * find_education_for()
   *
   * Finds the education for the student with the supplied ID and school
   * with the supplied id. If no school id is supplied, return all schools
   * the user has attended.
   *
   * SELECT * FROM `education` WHERE `student_id` = '1234' AND `school_id` = '567' LIMIT 1
   */
  public static function find_education_for($student_id = 0, $school_id = false)
  {
    $student_id = \DB::escape($student_id);
    if($school_id)
    {
      $school_id = \DB::escape($school_id);
      $query = "SELECT * FROM `education` WHERE `student_id` = $student_id AND `school_id` = $school_id LIMIT 1";
      $result = \DB::query($query)->as_object()->execute();
      if (count($result) != 1)
      {
        return false;
      }
      return $result[0];
    }
    else
    {
      $query = "SELECT * FROM `education`, `schools` WHERE education.school_id = schools.id AND education.student_id = $student_id ORDER BY education.start_date ASC";
      $result = \DB::query($query)->as_object()->execute();
      return $result;
    }
  }

  /**
   * get_attendees()
   *
   * Returns the attendees for a given school
   * If the second parameter contains an id,
   * it only finds people who attended who are
   * friends with that id.
   *
   * SELECT * FROM education
   * LEFT JOIN students ON education.student_id = students.id 
   * WHERE eductaion.school_id = '123'
   *
   * - OR -
   *
   * SELECT * FROM education
   * LEFT JOIN friends ON education.student_id = friends.friend_id
   * LEFT JOIN students ON friends.friend_id = students.id 
   * WHERE eductaion.school_id = '123'
   * AND friends.student_id = '456'
   */
  public static function get_attendees($school_id = 0, $student_id = false)
  {
    $school_id = \DB::escape($school_id);
    if($student_id)
    {
      $student_id = \DB::escape($student_id);
      $query = "SELECT * FROM education
      LEFT JOIN friends ON education.student_id = friends.friend_id
      LEFT JOIN students ON friends.friend_id = students.id 
      WHERE education.school_id = $school_id AND friends.student_id = $student_id";
    }
    else
    {
      $query = "SELECT * FROM education
      LEFT JOIN students ON education.student_id = students.id
      WHERE education.school_id = $school_id";
    }
    return \DB::query($query)->as_object()->execute();
  }
}
