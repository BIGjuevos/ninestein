<?php
/**
 * Awesome trivia bot plugin (answer object)
 *
 * @category  Phergie
 * @package   Phergie_Plugin_Ninestein
 * @author    Ryan Null <null@irchin.net>
 * @link      https://github.com/BIGjuevos/ninestein
 */
class Phergie_Plugin_Ninestein_Answer {
  private $_id;

  private $_answer;

  private $_questionId;

  public function setAnswer ( $answer ) {
    $this->_answer = $answer;
  }

  public function getAnswer () {
    return $this->_answer;
  }

  public function setId ( $id ) {
    $this->_id = $id;
  }

  public function getId () {
    return $this->_id;
  }

  public function setQuestionId ( $questionId ) {
    $this->_questionId = $questionId;
  }

  public function getQuestionId () {
    return $this->_questionId;
  }
}
