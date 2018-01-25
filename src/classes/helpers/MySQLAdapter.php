<?php
class MySQLAdapter {
  var $dbHost, $dbUser, $dbPassword, $dbName;

  function MySQLAdapter($dbConfig) {
    if( !array_key_exists('DB_HOST', $dbConfig) ||
        !array_key_exists('DB_USER', $dbConfig) ||
        !array_key_exists('DB_PASSWORD', $dbConfig) ||
        !array_key_exists('DB_NAME', $dbConfig) ) { return; }

    $this->dbHost = $dbConfig['DB_HOST'];
    $this->dbUser = $dbConfig['DB_USER'];
    $this->dbPassword = $dbConfig['DB_PASSWORD'];
    $this->dbName = $dbConfig['DB_NAME'];
  }

  function get_result_query_as_array($query) {
    $link = $this->_connect_db();

    if ( $link->connect_errno )  return false;

    $link->query('SET NAMES utf8');

    if(! $mysqli_result =  $link->query($query)) return  $this->_close_db($link);
    $result = array();
    while ($row = $mysqli_result->fetch_assoc()) { $result[] = $row; }
    $this->_close_db($link);

    return $result;
  }

  private

  function _connect_db() {
    try {
      return new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);
    } catch( Exception $e ) {
      return false;
    }
  }

  function _close_db($link) {
    if(!$link) return false;
    try {
      $link-> close();
      return true;
    } catch( Exception $e ) {
      return false;
    }
  }
}
?>