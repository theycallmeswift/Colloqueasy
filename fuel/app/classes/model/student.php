<?php

namespace Model;

class Student
{
  protected static $_table_name = 'students';

  /**
   * find_all()
   *
   * Finds all the students in the students table
   *
   * SELECT * FROM `students`
   */
  public static function find_all()
  {
    return \DB::query("SELECT * FROM `students`")->as_object()->execute();
  }

  /**
   * find_one_by_id()
   *
   * Finds the student with the supplied ID or throws a 404
   *
   * SELECT * FROM `students` WHERE `id` = '1234' LIMIT 1
   */
  public static function find_one_by_id($id = null)
  {
    $id = \DB::escape($id);
    $result = \DB::query("SELECT * FROM `students` WHERE `id` = $id LIMIT 1")->as_object()->execute();
    if (count($result) != 1)
    {
      return false;
    }
    return $result[0];
  }

  /**
   * find_one_by_facebook_id()
   *
   * Finds the student with the supplied facebook ID or returns false
   *
   * SELECT * FROM `students` WHERE `facebook_id` = '1234' LIMIT 1
   */
  public static function find_one_by_facebook_id($id = null)
  {
    $id = \DB::escape($id);
    $result = \DB::query("SELECT * FROM `students` WHERE `facebook_id` = $id LIMIT 1")->as_object()->execute();
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
   * INSERT INTO `students` ('first_name', 'last_name', 'email', ...)
   * VALUES ('John', 'Doe', 'john@example.com', ...)
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

    return \DB::query("INSERT INTO `students` $columns VALUES $values")->execute();
  }

  /**
   * update_from_array()
   *
   * Takes in an id and array of keys and values. Generates an UPDATE
   * statement from them accordingly.
   *
   * UPDATE `students` 
   * SET `first_name` = 'john',
   *     `last_name`  = 'doe',
   *     `email`      = 'john@example.com'
   *     , ...
   * WHERE `id` = '2'
   */
  public static function update_from_array($id, $attributes = array())
  {
    $column_values = "";
    foreach($attributes as $column => $value)
    {
      $column_values .= "`$column` = ".\DB::escape($value).",";
    }
    $column_values[strlen($column_values)-1] = " ";

    return \DB::query("UPDATE `students` SET $column_values WHERE `id` = '$id'")->execute();
  }

  /**
   * delete_one_by_id()
   *
   * Deletes the student with the supplied ID
   *
   * DELETE FROM `students` WHERE `id` = '1234' LIMIT 1
   */
  public static function delete_one_by_id($id = null)
  {
    $result = \DB::query("DELETE FROM `students` WHERE `id` = '$id' LIMIT 1")->as_object()->execute();
    return $result;
  }

  /**
   * get_friends()
   *
   * Returns the friends for a given user
   *
   * SELECT * FROM friends
   * LEFT JOIN students ON friends.friend_id = students.id 
   * WHERE friends.student_id = '123'
   */
  public static function get_friends($id = 0)
  {
    $id = \DB::escape($id);
    return \DB::query("SELECT * FROM friends
      LEFT JOIN students ON friends.friend_id = students.id 
      WHERE friends.student_id = $id")->as_object()->execute();

  }

  /**
   * are_friends()
   *
   * Returns true if two users are friends, otherwise false
   *
   * SELECT COUNT(*) FROM `friends` WHERE `student_id` = '1234' AND `friend_id` = '5678'
   */
  public static function are_friends($id = 0, $friend_id = 0)
  {
    $id = \DB::escape($id);
    $friend_id = \DB::escape($friend_id);

    $result = \DB::query("SELECT COUNT(*) as count FROM `friends` WHERE `student_id` = $id AND `friend_id` = $friend_id")->as_object()->execute();
    if ($result[0]->count != 1)
    {
      return false;
    }
    return true;
  }

  /**
   * add_friendship()
   *
   * Creates a friendship between two users
   *
   * INSERT INTO `friends` (student_id, friend_id) VALUES ('1234, '5678')
   */
  public static function add_friendship($id = 0, $friend_id = 0)
  {
    $id = \DB::escape($id);
    $friend_id = \DB::escape($friend_id);

    return \DB::query("INSERT INTO `friends` (student_id, friend_id) VALUES ($id, $friend_id)")->execute();
  }

  /**
   * remove_friendship()
   *
   * Deletes a friendship between two users
   *
   * DELETE FROM `friends` WHERE `student_id` = '1234' AND `friend_id` = '5678' LIMIT 1
   */
  public static function remove_friendship($id = 0, $friend_id = 0)
  {
    $id = \DB::escape($id);
    $friend_id = \DB::escape($friend_id);

    return \DB::query("DELETE FROM `friends` WHERE `student_id` = $id AND `friend_id` = $friend_id LIMIT 1")->execute();
  }
}
