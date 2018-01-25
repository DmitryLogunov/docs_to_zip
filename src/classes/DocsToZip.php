require './helpers/MySQLAdapter.php';
require './helpers/FileNames.php';
require './helpers/String.php';
require './helpers/Logger.php';

<?php
class DocsToZip {
  var $tender_number, $archive_file_name, $attachments;
  var $db, $errors, $logger;
  var $ATTACHMENTS_PATH, $ZIP_DOCUMENTS_PATH;
  var $VALID_EXTENTIONS = array('7z','bmp','cer','doc','docx','dump','htm','html','jpeg','jpg','ods','odt','page','pdf','png','ppt','pptx','rar','rtf','tif','tiff','vsd','xls','xlsb','xlsm','xlsx','xlt','xml','xps','zip');

  function DocsToZip($archive_file_name, $config) {
    $this->logger = new Logger($config->get('LOGS_PATH'));

    $this->errors = array();

    $parts_archive_file_name = explode('_', $archive_file_name);
    if (count($parts_archive_file_name) == 0 ) {
      $this->errors[] = 'not_correct_archive_file_name';
      return;
    }

    $this->tender_number = $parts_archive_file_name[0];
    $this->archive_file_name = $archive_file_name;
    $this->attachments = array();

    $this->ATTACHMENTS_PATH = $config->get('ATTACHMENTS_PATH');
    $this->ZIP_DOCUMENTS_PATH = $config->get('ZIP_DOCUMENTS_PATH');

    $db_settings = array('DB_HOST' => $config->get('DB_HOST'),
                         'DB_USER' => $config->get('DB_USER'),
                         'DB_PASSWORD' => $config->get('DB_PASSWORD'),
                         'DB_NAME' => $config->get('DB_NAME'));
    $this->db = new MySQLAdapter($db_settings);
  }

  function perform() {
    if ( $this->_state() )  {
      if ( $this->_perform() ) {
        $response = array('status' => 'success',
                          'info' => 'Succefully created new archive '.$this->ZIP_DOCUMENTS_PATH.$archive_file_name);
      }
      else $response = array('status' => 'error', 'info' => 'Error creating zip archive');
    }
    else $response = array('status' => 'error', 'info' => 'Error initialization');

    return json_encode($response);
  }

  private

  function _perform() {
    if (! $this->_get_attachments()) return false;
    if (! $this->_create_zip()) return false;
    return true;
  }

  function _state() {
    if( count($this->errors) > 0 ) return false;
    return true;
  }

  function _get_sql($type, $dispatcherID = 0) {
    switch($type) {
      case 'dispatchers': 
        return "SELECT 
                 `registry`.email
                FROM `tenders`.`offersAttachments` 
                INNER JOIN `tenders`.`tenderOffers` ON 
                  `offersAttachments`.`tenderOffersID` = `tenderOffers`.`offerID`
                INNER JOIN `registry` ON `registry`.id = `tenderOffers`.`registryID` 
                WHERE `tenderOffers`.`lotID` = '".$this->tender_number."' GROUP BY `registry`.email";
      case 'attachments_dispatcher':
        return "SELECT 
                  `registry`.`companyName` as dispatcherCompanyName,
                  `offersAttachments`.`fileID`,
                  `offersAttachments`.`fileType`,
                  `offersAttachments`.`originalName`
                FROM `tenders`.`offersAttachments` 
                INNER JOIN `tenders`.`tenderOffers` ON 
                  `offersAttachments`.`tenderOffersID` = `tenderOffers`.`offerID`
                INNER JOIN `registry` ON 
                  `registry`.id = `tenderOffers`.`registryID` 
                WHERE `tenderOffers`.`lotID` = '".$this->tender_number."' 
                AND `registry`.`email` = '".$dispatcherID."'";                       
    }
  }
  
  function _get_attachments() {
    try {
      $query = $this->_get_sql('dispatchers');

      if(!$dispatchers = $db->get_result_query_as_array($query)) return false;

      foreach ($dispatchers as $key => $dispatcher) {
        $query = $this->_get_sql('attachments_dispatcher', $dispatcher['email']);

        if(!$attachments = $db->get_result_query_as_array($query)) return false;

        foreach ($attachments as $key => $attachment) {
          $this->attachments[$dispatcher['email']][] = $attachment; 
        }
      }
      return true;
    } catch( Exception $e ) { $this->errors[] = $e; return false; }
  }

  function _create_zip() {
    $zip = new ZipArchive();  
    if(! $zip->open( $this->ZIP_DOCUMENTS_PATH.$this->archive_file_name, ZipArchive::CREATE)) return false;
    if(! count($this->attachments) > 0 ) return false;
    $parts_archive_file_name = explode('.', $this->archive_file_name);
    $root_folder = 
      ( count($parts_archive_file_name) > 0 ? 
        $parts_archive_file_name[0] : 
        'Заявки конкурса '.$this->tender_number );
    $zip->addEmptyDir(iconv('UTF-8', 'CP866', $root_folder));
    $dispatchers_folders = array();
    foreach ($this->attachments as $dispatcherID => $attachments) {
      $str = new String($attachments[0]['dispatcherCompanyName']);
      $folder = $str->clear();
      if(! in_array($dispatcherID, $dispatchers_folders)) {
        $dispatchers_folders[] =  $dispatcherID;
        $zip->addEmptyDir(iconv('UTF-8', 'CP866',  $root_folder.'/'.$folder));
      }
      $this->_add_dispather_attachments_to_zip($zip, $attachments, $root_folder.'/'.$folder);
    } 
    $zip->close();
    return true;
  }

  function _add_dispather_attachments_to_zip(&$zip, $attachments, $folder = '') {
    if ($folder != '') $folder .= '/';
    $attached_files = array();
    foreach ($attachments as $index => $attachment) {
      $attachment_source = $this->ATTACHMENTS_PATH.$attachment['fileID'].'.'.$attachment['fileType'];
      $attachment_file_name = $attachment['originalName'];
      $attachment_file_type = $attachment['fileType'];
      if(! key_exists($attachment_file_name, $attached_files)) { $attached_files[$attachment_file_name] = 0; }
      else {  $attached_files[$attachment_file_name] += 1; }
      $copy_number = $attached_files[$attachment_file_name];

      $files_names = new FileNames($this->VALID_EXTENTIONS);
      $attachment_file_name = $files_names->get_unique_file_name($attachment_file_name,
                                                                 $attachment_file_type,
                                                                 $copy_number);
      $attachment_file_name = iconv('UTF-8', 'CP866', $folder.$attachment_file_name);
      $zip->addFile($attachment_source, $attachment_file_name);
    }
  }
}
?>