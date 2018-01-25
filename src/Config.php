<?php
  define(DB_HOST, $_SERVER['DB_HOST']);
  define(DB_USER, $_SERVER['DB_USER']);
  define(DB_PASSWORD, $_SERVER['DB_PASSWORD']);
  define(DB_NAME, 'tenders');

  define(ROOT_MODULE_PATH, $_SERVER['DOCUMENT_ROOT']);
  define(LIB_PATH, ROOT_MODULE_PATH.'/lib');
  define(DOCUMENTS_PATH, ROOT_MODULE_PATH.'/docs');
  define(ATTACHMENTS_PATH, PATH_DOCUMENTS.'/attachments');
  define(ZIP_DOCUMENTS_PATH, PATH_DOCUMENTS.'/tmpZip');
  define(LOGS_PATH, ROOT_MODULE_PATH.'/logs');
  define(CLASSES_PATH, ROOT_MODULE_PATH.'/classes');

  class Config {
    var $params = array('test_param' => 'test_value',
                        'DB_HOST' => DB_HOST,
                        'DB_USER' => DB_USER,
                        'DB_PASSWORD' => DB_PASSWORD,
                        'DB_NAME' => DB_NAME,
                        'ROOT_MODULE_PATH' => ROOT_MODULE_PATH,
                        'LIB_PATH' => LIB_PATH,
                        'DOCUMENTS_PATH'=> DOCUMENTS_PATH,
                        'ATTACHMENTS_PATH' => ATTACHMENTS_PATH,
                        'ZIP_DOCUMENTS_PATH' => ZIP_DOCUMENTS_PATH,
                        'LOGS_PATH' => LOGS_PATH,
                        'CLASSES_PATH' => CLASSES_PATH);

    function get($constantName) {
      if(!array_key_exists($constantName, $this->params)) { return; }
      return $this->params[$constantName];
    }
  }
?>  
