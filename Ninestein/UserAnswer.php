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

	private $_core;

	private $_points;

	public function __construct($core) {
		$this->_core = $core;
	}

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

	public function setPoints($points) {
		$this->_points = $points;
	}

	public function save() {
		$sql = "
			INSERT INTO user_answer VALUES(
				NULL,
				{$this->_userId},
				{$this->_questionId},
				{$this->_correct},
				{$this->_points},
				CURRENT_TIMESTAMP)";

		$this->_core->getDb()->q($sql);
	}

	/**
	 * @params int $duration in number of seconds ago
	 */
	public static function getScoreByNick($nick, $db, $duration = NULL) {
		if ($duration != NULL) {
			$durationWhere = "AND ua.created_at >= '" . date("Y-m-d H:i:s", time() - $duration) . "'";
		} else {
			$durationWhere = "";
		}
		$sql = "
			SELECT SUM(ua.points) AS num
			FROM user_answer ua
			LEFT JOIN user u ON u.id = ua.user_id
			WHERE u.nick = '$nick' $durationWhere
			LIMIT 1";

    $res = $db->getDb()->query($sql);
		if ( !$res) {
			$db->getDb()->ping();
			$res = $db->getDb()->query($sql);
		}

		if (!$res) {
			return 0;
		} else {
			$row = $res->fetch_assoc();
			return $row['num'];
		}
	}
}
