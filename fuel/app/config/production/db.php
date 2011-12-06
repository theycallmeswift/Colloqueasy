<?php
/**
 * The production database settings.
 */

return array(
  'default' => array(
    'connection'  => array(
      'hostname'   => $_SERVER['DB1_HOST'],
      'port'       => $_SERVER['DB1_PORT'],
      'database'   => $_SERVER['DB1_NAME'],
      'username'   => $_SERVER['DB1_USER'],
      'password'   => $_SERVER['DB1_PASS'],
    ),
  ),
);
