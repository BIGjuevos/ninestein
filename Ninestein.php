<?php
/**
 * Phergie Trivia Plugin (Ninestein)
 *
 * PHP version 5
 *
 * LICENSE
 *
 * You can do whatever you want with Ninestein, just make sure you link back to me somehow.
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
  const MODE_OFF = 0;
  const MODE_ON = 1;
  const MODE_ASKING = 2;
  const MODE_WAITING = 3;
  
  private $_db_config = array();
  private $_config = array();
  private $_db;
  private $_mode;
  
  private $_question;
  private $_hint;
  
  private $_hintTime1;
  private $_hintTime2;
  private $_doneTime;
  private $_nextTime;

  public function onLoad() {
    //assert we have all we need
    $this->assertDependencies();

    //import global settings into scope
    $this->loadSettings();

    //start up database connection
    $this->_db = new Phergie_Plugin_Ninestein_Database($this->_db_config);
    
    $this->_mode = self::MODE_OFF;
  }
  
  public function onTick() {
    $time = time();
    
    if ( $this->_mode == self::MODE_WAITING && !is_null($this->_hintTime1) && $time >= $this->_hintTime1 ) {
      //issue first hint
      $this->_hint = Phergie_Plugin_Ninestein_Message::getHint($this->_question['answer']);
      $this->doPrivMsg($this->_config['channel'], $this->_hint );
      $this->_hintTime1 = null;
    } else if ( $this->_mode == self::MODE_WAITING && !is_null($this->_hintTime2) && $time >= $this->_hintTime2 ) {
      //issue second hint
      $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getHint($this->_question['answer'], $this->_hint) );
      $this->_hintTime2 = null;
    } else if ( $this->_mode == self::MODE_WAITING && !is_null($this->_doneTime) && $time >= $this->_doneTime ) {
      //issue second hint
      $this->doPrivMsg($this->_config['channel'], "Times Up" );
      $this->_doneTime = null;
      
      $this->missed();
    } else if ( $this->_mode == self::MODE_WAITING && !is_null($this->_nextTime) && $time >= $this->_nextTime ) {
      $this->_nextTime = null;
      
      $this->next();
    }
  }

  public function onPrivmsg() {
    //do some stuff depending on our mode
    switch ( $this->getEvent()->getArgument(1) ) {
      case "!start":
        if ( $this->_mode == self::MODE_OFF ) {
          $this->start();
        } else {
          $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getNoCanDo() );
        }
        break;
      case "!stop":
        if ( $this->_mode != self::MODE_OFF ) {
          $this->stop();
        } else {
          $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getNoCanDo() );
        }
        break;
      default:
        if (strtolower($this->getEvent()->getArgument(1)) == strtolower($this->_question['answer'])) {
          $this->correct($this->getEvent());
        }
    }
  }
  
  private function start() {
    $this->_mode = self::MODE_ON;
    
    $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getStart() );
    
    $this->ask();
  }
  
  private function missed() {
    $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getWrong() );
    $this->doPrivMsg($this->_config['channel'], "Answer: " . $this->_question['answer'] );
    
    $this->_nextTime = time() + 5;
  }
  
  private function correct(Phergie_Event_Request $ev) {
    $nick = $ev->getNick();
    $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getCorrect($nick) );
    
    $this->next();
  }
  
  private function next() {
    //reset our mode
    $this->_mode = self::MODE_ON;
    
    //reset our timers
    $this->_hintTime1 = null;
    $this->_hintTime2 = null;
    $this->_doneTime = null;
    $this->_nextTime = null;
    
    //ask again
    $this->ask();
  }
  
  private function stop() {
    $this->_mode = self::MODE_OFF;
    
    $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getStop() );
  }
  
  private function ask() {
    $this->_mode = self::MODE_ASKING;
    $this->_question = Phergie_Plugin_Ninestein_Question::fetch($this->_db->getDb());
    
    $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getAsk() );
    $this->doPrivMsg($this->_config['channel'], $this->_question['question']);
    
    $this->_mode = self::MODE_WAITING;
    
    $this->_hintTime1 = time() + $this->_config['time_limit_hint1'];
    $this->_hintTime2 = time() + $this->_config['time_limit_hint1'] + $this->_config['time_limit_hint1'];
    $this->_doneTime = time() + $this->_config['time_limit_hint1'] + $this->_config['time_limit_hint1'] + $this->_config['time_limit_done'];
    
      
    $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getTimeLimitAnswer($this->_doneTime - time()) );
  }
  
  public function hint1() {
    echo "OMG I GOT CALLED!\n";
    $this->doPrivMsg($this->_config['channel'], $this->_question['hint'] );
  }

  private function assertDependencies() {
  }

  private function loadSettings() {
    //database information
    $this->_db_config['username'] = $this->getConfig("ninestein.db.username");
    $this->_db_config['password'] = $this->getConfig("ninestein.db.password");
    $this->_db_config['hostname'] = $this->getConfig("ninestein.db.hostname");
    $this->_db_config['name'] = $this->getConfig("ninestein.db.name");
    
    $this->_config['channel'] = $this->getConfig("ninestein.channel");
    $this->_config['time_limit_hint1'] = $this->getConfig("ninestein.time_limit.hint1");
    $this->_config['time_limit_hint2'] = $this->getConfig("ninestein.time_limit.hint2");
    $this->_config['time_limit_done'] = $this->getConfig("ninestein.time_limit.done");
  }
}
