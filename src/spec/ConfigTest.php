<?php
require_once '../config.php';

class ConfigTest extends \PHPUnit\Framework\TestCase {
  public function testGet() {
     $c = new Config();
     $this->assertEquals('test_value', $c->get('test_param'));
  }
}
?>