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

	public static function getIdByNick($nick, $db) {
		$sql = "
			SELECT * FROM user
			WHERE nick = '$nick' LIMIT 1";

    $user = $db->getDb()->query($sql);
		if ( !$user ) {
			$db->getDb()->ping();
			$user = $db->getDbh()->query($sql);
		}
		if ( $user->num_rows > 0 ) {
			$vals = $user->fetch_assoc();

			$model = new self();
			$model->setNick($vals['nick']);
			$model->setId($vals['id']);
			$model->setFirstSeen($vals['first_seen']);

			return $model;
		} else {
			return false;
		}
	}

	public static function create($nick, $db) {
		$sql = "
			INSERT INTO user VALUES(
				NULL,
				'{$nick}',
				NULL)";

		$db->q($sql);

		return self::getIdByNick($nick, $db);
	}
}
