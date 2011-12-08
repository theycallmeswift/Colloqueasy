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
   * get_profile_summaries()
   *
   * Returns the basic info for a user along with a friends count
   *
   * SELECT students.id,
   * students.email, 
   * students.first_name,
   * students.last_name,
   * students.gender,
   * COUNT(friends.friend_id) as friend_count
   * FROM students, friends
   * WHERE students.id = friends.student_id
   * GROUP BY friends.student_id
   */
  public static function get_profile_summaries()
  {
    return \DB::query("SELECT students.id,
      students.email, 
      students.first_name,
      students.last_name,
      students.gender,
      COUNT(friends.friend_id) as friend_count
      FROM students, friends
      WHERE students.id = friends.student_id
      GROUP BY friends.student_id")->as_object()->execute();
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
  
  /**
  * find_pending_friends()
  *
  * Finds all people who have friended the user but the user has not friended
  *
  *
  **/
  public static function find_pending_friends($id = 0)
  {
	  $id=\DB::escape($id);
	  
	  return \DB::query("SELECT * FROM students S WHERE S.id IN  (Select F.student_id FROM friends F WHERE F.friend_id = " . $id . " AND NOT EXISTS 
	  (SELECT * FROM friends F1 WHERE F1.friend_id = F.student_id and F1.student_id = " . $id ."))" )->as_object()->execute();
	  //this only gives you friend ids and student ids
  }
  

  /**
   * are_in_relationship()
   *
   * Returns true if two users are in a relationship, otherwise false
   *
   * SELECT COUNT(*) FROM `relationships` WHERE `initiator_id` = '1234' AND `acceptor_id` = '5678'
   */
  public static function are_in_relationship($id = 0, $relation_id= 0)
  {
    $id = \DB::escape($id);
    $relation_id = \DB::escape($relation_id);

    $result = \DB::query("SELECT COUNT(*) as count FROM `relationships` WHERE `initiator_id` = $id AND `acceptor_id` = $relation_id")->as_object()->execute();
    if ($result[0]->count != 1)
    {
      return false;
    }
    return true;
  }

  /**
   * add_relationship()
   *
   * Creates a relationship between two users
   *
   * INSERT INTO `relationships` (initiator_id, acceptor_id) VALUES ('1234, '5678')
   */
  public static function add_relationship($id = 0, $relation_id = 0)
  {
    $id = \DB::escape($id);
    $relation_id = \DB::escape($relation_id);

    return \DB::query("INSERT INTO `relationships` (initiator_id, acceptor_id) VALUES ($id, $relation_id)")->execute();
  }

  /**
   * remove_relationship()
   *
   * Deletes a relationship between two users
   *
   * DELETE FROM `relationship` WHERE `initiator_id` = '1234' AND `acceptor_id` = '5678' LIMIT 1
   */
  public static function remove_relationship($id = 0, $relation_id = 0)
  {
    $id = \DB::escape($id);
    $relation_id = \DB::escape($relation_id);

    return \DB::query("DELETE FROM `relationships` WHERE `initiator_id` = $id AND `acceptor_id` = $relation_id LIMIT 1")->execute();
  }

  /**
   * get_relationships()
   *
   * Returns the relationships for a given user
   *
   * SELECT * FROM relationships
   * LEFT JOIN students ON relationships.acceptor_id = students.id 
   * WHERE relationships.initiator_id = '123'
   */
  public static function get_relationships($id = 0)
  {
    $id = \DB::escape($id);
    return \DB::query("SELECT * FROM relationships
      LEFT JOIN students ON relationships.acceptor_id = students.id 
      WHERE relationships.initiator_id = $id")->as_object()->execute();
  }
}
