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

	private $_points;

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

			$this->_points = ceil($this->_points / 2);
    } else if ( $this->_mode == self::MODE_WAITING && !is_null($this->_hintTime2) && $time >= $this->_hintTime2 ) {
      //issue second hint
      $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getHint($this->_question['answer'], $this->_hint) );
      $this->_hintTime2 = null;

			$this->_points = ceil($this->_points / 2);
    } else if ( $this->_mode == self::MODE_WAITING && !is_null($this->_doneTime) && $time >= $this->_doneTime ) {
      //issue beating
      $this->doPrivMsg($this->_config['channel'], "\x037Times Up\x15" );
      $this->_doneTime = null;
      
      $this->missed();
    } else if ( $this->_mode == self::MODE_WAITING && !is_null($this->_nextTime) && $time >= $this->_nextTime ) {
      $this->_nextTime = null;
      
      $this->next();
    }
  }

  public function onPrivmsg() {
		$nick = $this->getEvent()->getNick();
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
			case "!help":
				$this->help();
				break;
			case "!score":
				$this->score($nick);
				break;
			case "!top":
				$this->top($nick);
				break;
      default:
        if (strtolower($this->getEvent()->getArgument(1)) == strtolower($this->_question['answer'])) {
          $this->correct($this->getEvent());
        } else {
					$this->charMatch(strtolower($this->getEvent()->getArgument(1)), strtolower($this->_question['answer']));
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
    $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getCorrect($nick, $this->_points) );

		$ua = new Phergie_Plugin_Ninestein_UserAnswer($this);
		$ua->setCorrect(1);
		$user = Phergie_Plugin_Ninestein_User::getIdByNick($nick, $this->_db);
		if (!$user) {
			$user = Phergie_Plugin_Ninestein_User::create($nick, $this->_db);
		}
		$ua->setUserId( $user->getId() );
		$ua->setQuestionId( $this->_question['id'] );
		$ua->setPoints( $this->_points );

		$ua->save();
    
    $this->next();
  }

	private function charMatch($guess, $answer) {
		$guessLetters = str_split($guess);
		$realLetters = str_split($answer);
		$hintParts = str_split($this->_hint);

		foreach ($guessLetters as $id => $char) {
			if ( !isset($realLetters[$id]) ) {
				break;
			}
			if ( strtolower($char) == strtolower($realLetters[$id]) ) {
				$hintParts[$id] = $realLetters[$id];
			}
		}

		$this->_hint = implode("", $hintParts);

		$this->doPrivMsg($this->_config['channel'], $this->_hint );
	}
  
  private function next() {
    //reset our mode
    $this->_mode = self::MODE_ON;
    
    //reset our timers
    $this->_hintTime1 = null;
    $this->_hintTime2 = null;
    $this->_doneTime = null;
    $this->_nextTime = null;
		$this->_hint = "";
    
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
    $this->doPrivMsg($this->_config['channel'], "\x036" . $this->_question['question'] . "\x16");
    
    $this->_mode = self::MODE_WAITING;
    
    $this->_hintTime1 = time() + $this->_config['time_limit_hint1'];
    $this->_hintTime2 = time() + $this->_config['time_limit_hint1'] + $this->_config['time_limit_hint1'];
    $this->_doneTime = time() + $this->_config['time_limit_hint1'] + $this->_config['time_limit_hint1'] + $this->_config['time_limit_done'];
    
    $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getTimeLimitAnswer($this->_doneTime - time()) );

		$this->_points = rand(5,15);
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

	public function getDb() {
		return $this->_db;
	}

	protected function help() {
		$this->doPrivMsg($this->_config['channel'], "Help: It's easy really!" );
		$this->doPrivMsg($this->_config['channel'], "I ask questions, you try to answer them." );
		$this->doPrivMsg($this->_config['channel'], "If you match a letter, I show you." );
		$this->doPrivMsg($this->_config['channel'], "You get hints every so often." );
		$this->doPrivMsg($this->_config['channel'], "Commands:" );
		$this->doPrivMsg($this->_config['channel'], "!start - starts the trivia game" );
		$this->doPrivMsg($this->_config['channel'], "!stop - stops the trivia game" );
		$this->doPrivMsg($this->_config['channel'], "!score - gives you your scores for all time and past week" );
		//$this->doPrivMsg($this->_config['channel'], "!top - gives you the top 5 people of all time and the past week" );
	}

	protected function score($nick) {
		$alltime = Phergie_Plugin_Ninestein_UserAnswer::getScoreByNick($nick, $this->_db, NULL);
		$week = Phergie_Plugin_Ninestein_UserAnswer::getScoreByNick($nick, $this->_db, 86400 * 7);

    $this->doPrivMsg($this->_config['channel'], Phergie_Plugin_Ninestein_Message::getScore($nick, $alltime, $week) );
	}
}
