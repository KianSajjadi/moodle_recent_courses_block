<?php

namespace block_view_recent_courses\output;

defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

class main implements renderable, templatable {

  public function __construct($course_urls) {
    foreach($course_urls as $course_url){
      $this->course_urls[] = $course_url;
    }
  }
  
  public function export_for_template(renderer_base $output) {
    $data = new \stdClass();
    foreach($this->course_urls as $course_url) {
      $data->course_urls[] = $course_url;
    }

    return $data;
  }

}