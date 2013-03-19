<?php
/**
 * Awesome trivia bot plugin (question object)
 *
 * @category  Phergie
 * @package   Phergie_Plugin_Ninestein
 * @author    Ryan Null <null@irchin.net>
 * @link      https://github.com/BIGjuevos/ninestein
 */
class Phergie_Plugin_Ninestein_Question {
  private $_id;

  private $_question;

  private $_categoryId;

  private $_story;

  public function setCategoryId ( $categoryId ) {
    $this->_categoryId = $categoryId;
  }

  public function getCategoryId () {
    return $this->_categoryId;
  }

  public function setId ( $id ) {
    $this->_id = $id;
  }

  public function getId () {
    return $this->_id;
  }

  public function setQuestion ( $question ) {
    $this->_question = $question;
  }

  public function getQuestion () {
    return $this->_question;
  }

  public function setStory ( $story ) {
    $this->_story = $story;
  }

  public function getStory () {
    return $this->_story;
  }
}
