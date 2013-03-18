<?php
/**
 * Phergie Trivia Plugin (Ninestein)
 *
 * PHP version 5
 *
 * LICENSE
 *
 * You can do whatever you want with this file.
 *
 * @category  Phergie
 * @package   Phergie_Plugin_Ninestein
 * @author    Ryan Null <null@irchin.net>
 * @link      https://github.com/BIGjuevos/ninestein
 */

/**
 * Awesome trivia bot plugin
 *
 * @category  Phergie
 * @package   Phergie_Plugin_Jira
 * @author    Ryan Null <null@irchin.net>
 * @link      https://github.com/BIGjuevos/ninestein
 */
class Phergie_Plugin_Ninestein extends Phergie_Plugin_Abstract {
  private $_db;

  public function onLoad() {
    $this->_db = new Phergie_Plugin_Ninestein_Database();

    $this->loadSettings();
  }

  private function loadSettings() {
    //pull settings out of global array
  }
}
