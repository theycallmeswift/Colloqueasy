<?php

namespace Model;

class Message
{
  protected static $_table_name = 'messages';

  /**
   * find_all_for_receiver()
   *
   * Finds all the messages in the messages table where
   * the user with the supplied id is the reciever
   *
   * SELECT * FROM `messages` WHERE receiver_id = '1234'
   */
  public static function find_all_for_receiver($id = 0)
  {
    $id = \DB::escape($id);
    return \DB::query("SELECT * FROM `messages` WHERE receiver_id = $id")->as_object()->execute();
  }

  /**
   * find_all_for_sender()
   *
   * Finds all the messages in the messages table where
   * the user with the supplied id is the sender
   *
   * SELECT * FROM `messages` WHERE sender_id = '1234'
   */
  public static function find_all_for_sender($id = 0)
  {
    $id = \DB::escape($id);
    return \DB::query("SELECT * FROM `messages` WHERE sender_id = $id")->as_object()->execute();
  }

  /**
   * unread_messages_count()
   *
   * Finds the count of unread messages for a given user
   * the user with the supplied id is the sender
   *
   * SELECT COUNT(*) FROM `messages` WHERE receiver_id = '1234' && has_read = '0'
   */
  public static function unread_messages_count($id = 0)
  {
    $id = \DB::escape($id);
    $result = \DB::query("SELECT COUNT(*) AS total FROM `messages` WHERE receiver_id = $id && has_read = '0'")->as_object()->execute();
    return (int) $result[0]->total;
  }

  /**
   * mark_as_read()
   *
   * Marks the message with the given id as read.
   *
   * UPDATE `messages` SET `has_read` = '1' WHERE id = '1234'
   */
  public static function mark_as_read($id = 0)
  {
    $id = \DB::escape($id);
    return \DB::query("UPDATE `messages` SET `has_read` = '1' WHERE id = $id")->as_object()->execute();
  }

  /**
   * find_one_by_id()
   *
   * Finds the message with the supplied ID or returns false
   *
   * SELECT * FROM `messages` WHERE `id` = '1234' LIMIT 1
   */
  public static function find_one_by_id($id = null)
  {
    $id = \DB::escape($id);
    $result = \DB::query("SELECT * FROM `messages` WHERE `id` = $id LIMIT 1")->as_object()->execute();
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
   * INSERT INTO `messages` ('sender_id', 'receiver_id', 'subject', ...)
   * VALUES ('1234', '5678', 'Hey! Whats up?', ...)
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

    return \DB::query("INSERT INTO `messages` $columns VALUES $values")->execute();
  }

  /**
   * delete_one_by_id()
   *
   * Deletes the message with the supplied ID
   *
   * DELETE FROM `messagess` WHERE `id` = '1234' LIMIT 1
   */
  public static function delete_one_by_id($id = null)
  {
    $result = \DB::query("DELETE FROM `messages` WHERE `id` = '$id' LIMIT 1")->as_object()->execute();
    return $result;
  }


}
