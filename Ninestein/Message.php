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
    
    return "\x039" . $messages[array_rand($messages)] . "\x15";
  }
  
  public static function getAsk() {
    $messages = array(
        "Here Comes Another Question",
    );
    
    return "\x0311" . $messages[array_rand($messages)] . "\x15";
  }
  
  public static function getHint($answer, $last = "") {
    $parts = str_split($answer);
    $lastParts = str_split($last);
    $response = "";
    
    foreach ( $parts as $index => $letter ) {
      if ( $last == "" ) {
        if ( $letter == " " ) {
          $response .= " ";
        } else if ( rand(1,4) == 2 ) {
          $response .= $letter;
        } else {
          $response .= "*";
        }
      } else {
        if ( $lastParts[$index] != "*" ) {
          $response .= $lastParts[$index];
        } else if ( $letter == " " ) {
          $response .= " ";
        } else if ( $lastParts[$index] == "*" && rand(1,2) == 2 ) {
          $response .= $letter;
        } else {
          $response .= "*";
        }
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
    
    return "\x0311" . $messages[array_rand($messages)] . "\x15";
  }
  
  public static function getWrong() {
    $messages = array(
        "Wow you suck at this. Sit here and study the correct answer.",
        "Were you sleeping? Sit here and study the correct answer.",
				"How did you not know that?",
				"I thought you were supposed to be smart!",
				"Well, at least I knew the answer to that",
    );
    
    return "\x034" . $messages[array_rand($messages)] . "\x15";
  }
  
  public static function getCorrect($n, $p) {
    $messages = array(
        "Will someone get $n a prize?  He just got a question right! How about $p points?",
        "About time $n. You get a measly $p points.",
        "Took you long enough $n! Here's $p pity points.",
    );
    
    return "\x033" . $messages[array_rand($messages)] . "\x15";
  }

  public static function getScore($n, $alltime, $week) {
    $messages = array(
			"$n has $week points this week, and $alltime all-time!",
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
    
    return "\x034" . $messages[array_rand($messages)] . "\x15";
  }
  
  public static function getNoCanDo() {
    $messages = array(
        "I'm sorry Dave, I can't do that right now.",
        "Did you read the manual?",
        "Ya, ummm, no.  Not gonna happen.",
    );
    
    return "\x034" . $messages[array_rand($messages)] . "\x15";
  }
}
