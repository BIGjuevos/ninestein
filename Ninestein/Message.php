<?php
/**
 * Awesome trivia bot plugin (messages object)
 *
 * @category  Phergie
 * @package   Phergie_Plugin_Ninestein
 * @author    Ryan Null <null@irchin.net>
 * @link      https://github.com/BIGjuevos/ninestein
 */
class Phergie_Plugin_Ninestein_Message {
  public static function getStart() {
    $messages = array(
        "What is up homies!?",
        "I'M ALIVE!",
        "Let's pley shall we?",
        "This week on \"Are You Smarter Than A PHP IRC Bot?\", we have... YOU!",
        "I want to play some trivia, don't you?!",
    );
    
    return $messages[array_rand($messages)];
  }
  
  public static function getAsk() {
    $messages = array(
        "Here Comes Another Question",
    );
    
    return $messages[array_rand($messages)];
  }
  
  public static function getHint($answer) {
    $parts = str_split($answer);
    $response = "";
    
    foreach ( $parts as $letter ) {
      if ( $letter == " " ) {
        $response .= " ";
      } else if ( rand(1,3) == 2 ) {
        $response .= $letter;
      } else {
        $response .= "*";
      }
    }
    
    return $response;
  }
  
  public static function getTimeLimitHint1($x) {
    $messages = array(
        "You have $x seconds before the first hint.",
    );
    
    return $messages[array_rand($messages)];
  }
  
  public static function getTimeLimitHint2($x) {
    $messages = array(
        "You have $x seconds before the second hint.",
    );
    
    return $messages[array_rand($messages)];
  }
  
  public static function getTimeLimitAnswer($x) {
    $messages = array(
        "You have $x seconds before you must answer.",
    );
    
    return $messages[array_rand($messages)];
  }
  
  public static function getWrong() {
    $messages = array(
        "Wow you suck at this.  I get the points. Sit here and study the correct answer.",
        "Were you sleeping?  I get the points. Sit here and study the correct answer.",
    );
    
    return $messages[array_rand($messages)];
  }
  
  public static function getStop() {
    $messages = array(
        "Fine give up, see if I care!",
        "What a sore loser.",
        "Oh, I see the only way for you to win is to kill me?",
        "OK, fine I get you're point, I'm stopping!",
    );
    
    return $messages[array_rand($messages)];
  }
  
  public static function getNoCanDo() {
    $messages = array(
        "I'm sorry Dave, I can't do that right now.",
        "Did you read the manual?",
        "Ya, ummm, no.  Not gonna happen.",
    );
    
    return $messages[array_rand($messages)];
  }
}
