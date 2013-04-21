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
  public static function fetch(mysqli $db) {
    //do a fast random selection
    $sql = "SELECT *
            FROM question AS r1 JOIN
              (SELECT (RAND() *
                (SELECT MAX(id)
                  FROM question)) AS id)
                    AS r2
           WHERE r1.id >= r2.id
           ORDER BY r1.id ASC
           LIMIT 1";
    
    $item = $db->query($sql);
    return $item->fetch_assoc();
  }
}
