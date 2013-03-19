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
    //ensure our configuration is valid
    $this->_config = $config;

    if ( !$this->_config['ninestein.db.username'] )
      throw new Phergie_Plugin_Ninestein_Exception("database username not set.  Please set 'ninestein.db.username'");
    if ( !$this->_config['ninestein.db.password'] )
      throw new Phergie_Plugin_Ninestein_Exception("database password not set.  Please set 'ninestein.db.password'");
    if ( !$this->_config['ninestein.db.hostname'] )
      throw new Phergie_Plugin_Ninestein_Exception("database hostname not set.  Please set 'ninestein.db.hostname'");
    if ( !$this->_config['ninestein.db.name'] )
      throw new Phergie_Plugin_Ninestein_Exception("database name not set.  Please set 'ninestein.db.name'");

    //connect
    $this->connect();

    //ensure it worked
    if ( !$this->_db )
      throw new Phergie_Plugin_Ninestein_Exception('unable to connect to database with provided credentials.');
  }
  private function connect() {
    $this->_db = new mysqli(
      $this->_config['ninestein.db.hostname'],
      $this->_config['ninestein.db.username'],
      $this->_config['ninestein.db.password'],
      $this->_config['ninestein.db.name']
    );
  }
}