<?php
class FileNames {
  var $valid_extentions;

  function FileNamesTools($valid_extentions) {
    $this->valid_extentions = $valid_extentions;
  }

  function get_unique_file_name($file_name, $file_type, $copy_number) {
    $file_name_without_ext = $this->_get_file_name_without_ext($file_name, $file_type);
    $ext = $this->get_file_name_ext($file_name, $file_type);
    if ( $copy_number == 0 ) return $file_name_without_ext.".".$ext;

    return $file_name_without_ext.".".$copy_number.".".$ext;
  }

  function get_file_name_ext($file_name, $file_type)
  {
    $parts_file_name = explode('.', $file_name);
    $num_parts = count($parts_file_name);
    $ext = $num_parts > 1 ? $parts_file_name[$num_parts-1] : $file_type;

    return ( in_array($ext, $this->valid_extentions) ? $ext : $file_type );
  }

  function get_file_name_without_ext($file_name, $file_type)
  {
    $parts_file_name = explode('.', $file_name);
    $num_parts = count($parts_file_name);
    $ext = $num_parts > 1 ? $parts_file_name[$num_parts-1] : $file_type;
    $ext = in_array($ext, $this->valid_extentions) ? $ext : $file_type;
    $file_name_cut_ext = $file_name;

    if($num_parts > 1 && $parts_file_name[$num_parts-1] == $ext){
      $parts_file_name_cut_ext = array();
      if(is_array($parts_file_name) && $num_parts > 0){
       for($i=0; $i < $num_parts-1; $i++) {
        $parts_file_name_cut_ext[$i] = $parts_file_name[$i];
       }
       $file_name_cut_ext =  implode('.', $parts_file_name_cut_ext);
      }
    }

    return $file_name_cut_ext;
  }
}
?>