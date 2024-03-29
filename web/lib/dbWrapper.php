<?php
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once "$ROOT/settings/config.php";

function getDB()
{
  global $dbHost;
  global $dbName;
  global $dbUser;
  global $dbPass;
  $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
  return $conn;
}

class Database
{


  static function updateField($table, $field, $where, $is, $value)
  {

    $conn = getDB();
    $stmt = $conn->prepare("UPDATE $table SET  $field = ? WHERE ($where = ?)");
    $stmt->bind_param("ss", $value, $is);
    $stmt->execute();
    $stmt->close();
    $conn->close();
  }




  static function select($table, $where, $is)
  {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT * FROM $table WHERE $where = ?");
    $stmt->bind_param("s", $is);
    $stmt->execute();
    $result = $stmt->get_result();
    $res = [];
    while ($row = $result->fetch_assoc()) {
      $res[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $res;
  }

  static function executeStmt($sql, string $types = "", array $values = [])
  {
    $conn = getDB();
    $stmt = $conn->prepare("$sql");
    if ($types != "") {
      $stmt->bind_param($types, ...$values);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $lastId = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    if (!is_bool($result)) {
      $res = [];
      while ($row = $result->fetch_assoc()) {
        $res[] = $row;
      }
      return $res;
    } else {
      return $lastId;
    }
  }



  static function selectField($table, $field, $where, $is)
  {
    $conn = getDB();
    $stmt = $conn->prepare("SELECT $field from $table WHERE $where = ?");
    $stmt->bind_param("s", $is);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    return $result->fetch_assoc()[$field];
  }

  static function executeUserSelect($sql)
  {
    // Check if no other harmfull statements exist
    if (Database::isUserSelectSane($sql)) {
      $returnAssoc = [];
      $conn = getDB();
      $result = $conn->query($sql);
      while ($row = $result->fetch_assoc()) {
        array_push($returnAssoc, $row);
      }

      return $returnAssoc;
    } else {
      return false;
    }
  }

  static function isUserSelectSane($sql)
  {
    return preg_match(
      '/INSERT|UPDATE|DELETE|RENAME|DROP|CREATE|TRUNCATE|ALTER|COMMIT|ROLLBACK|MERGE|CALL|EXPLAIN|LOCK|GRANT|REVOKE|SAVEPOINT|TRANSACTION|SET/mi',
      $sql
    ) == 0;
  }
}
