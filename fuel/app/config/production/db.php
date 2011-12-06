<?php
/**
 * The production database settings.
 */

return array(
  'default' => array(
    'connection'  => array(
      'host'       => $_SERVER['DB1_HOST'],
      'database'   => $_SERVER['DB1_NAME'],
      'username'   => $_SERVER['DB1_USER'],
      'password'   => $_SERVER['DB1_PASS'],
    ),
  ),
);
