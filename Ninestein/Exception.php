<?php
/**
 * Awesome trivia bot plugin (user object)
 *
 * @category  Phergie
 * @package   Phergie_Plugin_Ninestein
 * @author    Ryan Null <null@irchin.net>
 * @link      https://github.com/BIGjuevos/ninestein
 */
class Phergie_Plugin_Ninestein_Exception extends Exception {
  public function __construct($message, $code = 0, Exception $previous = null) {
    $message = "[Ninestein Trivia Plugin] " . $message;

    parent::__construct($message, $code, $previous);
  }
}
