<?php
/**
 * Awesome trivia bot plugin (user answer object)
 *
 * @category  Phergie
 * @package   Phergie_Plugin_Ninestein
 * @author    Ryan Null <null@irchin.net>
 * @link      https://github.com/BIGjuevos/ninestein
 */
class Phergie_Plugin_Ninestein_UserAnswer {
  private $_userId;

  private $_questionId;

  private $_correct;

  private $_createdAt;

  public function setCorrect ( $correct ) {
    $this->_correct = $correct;
  }

  public function getCorrect () {
    return $this->_correct;
  }

  public function setCreatedAt ( $createdAt ) {
    $this->_createdAt = $createdAt;
  }

  public function getCreatedAt () {
    return $this->_createdAt;
  }

  public function setQuestionId ( $questionId ) {
    $this->_questionId = $questionId;
  }

  public function getQuestionId () {
    return $this->_questionId;
  }

  public function setUserId ( $userId ) {
    $this->_userId = $userId;
  }

  public function getUserId () {
    return $this->_userId;
  }

}