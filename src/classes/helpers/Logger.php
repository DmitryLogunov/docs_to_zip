<?php
class Logger {
  var $logs_path;

  function Logger($logs_path) {
    $this->logs_path = $logs_path;
  }

  function info($file_log, $label, $log_info)  {
      $fp = fopen($this->logs_path.$file_log, 'a+');
      fwrite($fp, "\n".$label.": ".$log_info);
      fclose($fp);
  }
}
?>