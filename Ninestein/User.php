<?php
/**
 * Awesome trivia bot plugin (user object)
 *
 * @category  Phergie
 * @package   Phergie_Plugin_Ninestein
 * @author    Ryan Null <null@irchin.net>
 * @link      https://github.com/BIGjuevos/ninestein
 */
class Phergie_Plugin_Ninestein_User {
  private $_id;

  private $_nick;

  private $_firstSeen;

  public function setFirstSeen ( $firstSeen ) {
    $this->_firstSeen = $firstSeen;
  }

  public function getFirstSeen () {
    return $this->_firstSeen;
  }

  public function setId ( $id ) {
    $this->_id = $id;
  }

  public function getId () {
    return $this->_id;
  }

  public function setNick ( $nick ) {
    $this->_nick = $nick;
  }

  public function getNick () {
    return $this->_nick;
  }
}
