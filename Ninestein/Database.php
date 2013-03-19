<?php
/**
 * Awesome trivia bot plugin (database interface)
 *
 * @category  Phergie
 * @package   Phergie_Plugin_Ninestein
 * @author    Ryan Null <null@irchin.net>
 * @link      https://github.com/BIGjuevos/ninestein
 */
class Phergie_Plugin_Ninestein_Database {
  private $_db;

  private $_config;

  public function __construct($config) {
    $this->_config = $config;
  }
}