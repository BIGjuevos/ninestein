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
  private $_db_config = array();
  private $_db;

  public function onLoad() {
    $this->loadSettings();

    $this->_db = new Phergie_Plugin_Ninestein_Database($this->_db_config);
  }

  private function loadSettings() {
    //database information
    $this->_db_config['username'] = $this->getConfig("ninestein.db.username");
    $this->_db_config['password'] = $this->getConfig("ninestein.db.password");
    $this->_db_config['hostname'] = $this->getConfig("ninestein.db.hostname");
    $this->_db_config['name'] = $this->getConfig("ninestein.db.name");
  }
}
