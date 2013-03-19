<?php
/**
 * Awesome trivia bot plugin (category object)
 *
 * @category  Phergie
 * @package   Phergie_Plugin_Ninestein
 * @author    Ryan Null <null@irchin.net>
 * @link      https://github.com/BIGjuevos/ninestein
 */
class Phergie_Plugin_Ninestein_Category {
  private $_id;

  private $_name;

  public function setId ( $id ) {
    $this->_id = $id;
  }

  public function getId () {
    return $this->_id;
  }

  public function setName ( $name ) {
    $this->_name = $name;
  }

  public function getName () {
    return $this->_name;
  }
}