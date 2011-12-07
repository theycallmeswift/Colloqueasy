<?php

namespace Model;

class Interest
{
  protected static $_table_name = 'interest';

  /**
   * find_all()
   *
   * Finds all the interests in the interests table
   *
   * SELECT * FROM `interest` ORDER BY name ASC
   */
  public static function find_all($uid = 0)
  {
    return \DB::query("
      SELECT interest.id, 
      interest.name, 
      interest.description, 
      (SELECT COUNT(*) >= 1 FROM interested_in WHERE student_id = '$uid' GROUP BY interested_in.interest_id HAVING interest_id = interest.id) as is_interested
      FROM `interest` ORDER BY name ASC")->as_object()->execute();
  }

  /**
   * find_one_by_id()
   *
   * Finds the interest with the supplied ID or returns false
   *
   * SELECT * FROM `interest` WHERE `id` = '1234' LIMIT 1
   */
  public static function find_one_by_id($id = null)
  {
    $id = \DB::escape($id);
    $result = \DB::query("SELECT * FROM `interest` WHERE `id` = $id LIMIT 1")->as_object()->execute();
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
   * INSERT INTO `interest` ('name', 'description', ...)
   * VALUES ('Programming', 'Lorem ipsum', ...)
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

    return \DB::query("INSERT INTO `interest` $columns VALUES $values")->execute();
  }

  /**
   * is_interested()
   *
   * Returns true if a user is interested, otherwise false
   *
   * SELECT COUNT(*) FROM `interested_in` WHERE `student_id` = '1234' AND `interest_id` = '5678'
   */
  public static function is_interested($id = 0, $interest_id = 0)
  {
    $id = \DB::escape($id);
    $interest_id = \DB::escape($interest_id);

    $result = \DB::query("SELECT COUNT(*) as count FROM `interested_in` WHERE `student_id` = $id AND `interest_id` = $interest_id")->as_object()->execute();
    if ($result[0]->count != 1)
    {
      return false;
    }
    return true;
  }

  /**
   * find_interests_for()
   *
   * Returns the interests for a given user
   *
   * SELECT * FROM interested_in
   * LEFT JOIN interest ON interested_in.interest_id = interest.id 
   * WHERE interested_in.student_id = '123'
   */
  public static function find_interests_for($id = 0)
  {
    $id = \DB::escape($id);
    return \DB::query("SELECT * FROM interested_in
      LEFT JOIN interest ON interested_in.interest_id = interest.id 
      WHERE interested_in.student_id = $id")->as_object()->execute();
  }

  /**
   * add_interest()
   *
   * Creates an interest
   *
   * INSERT INTO `interests` (student_id, interest_id) VALUES ('1234, '5678')
   */
  public static function add_interest($id = 0, $interest_id = 0)
  {
    $id = \DB::escape($id);
    $interest_id = \DB::escape($interest_id);

    return \DB::query("INSERT INTO `interested_in` (student_id, interest_id) VALUES ($id, $interest_id)")->execute();
  }

  /**
   * remove_interest()
   *
   * Deletes an interest
   *
   * DELETE FROM `interests` WHERE `student_id` = '1234' AND `interest_id` = '5678' LIMIT 1
   */
  public static function remove_interest($id = 0, $interest_id = 0)
  {
    $id = \DB::escape($id);
    $interest_id = \DB::escape($interest_id);

    return \DB::query("DELETE FROM `interested_in` WHERE `student_id` = $id AND `interest_id` = $interest_id LIMIT 1")->execute();
  }
}
