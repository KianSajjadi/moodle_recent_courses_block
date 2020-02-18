<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


namespace block_view_recent_courses\output;
defined('MOODLE_INTERNAL') || die();

use renderable;
use renderer_base;
use templatable;

class main implements renderable, templatable {

  public function export_for_template(renderer_base $output) {
    //$course_urls = array_values($block_view_recent_courses->course_urls);
    foreach ($block_view_recent_courses->course_urls as $course_url) {
      $course_urls[] = $course_url->to_record();
    }
    
    $title = $block_view_recent_courses->title;
    var_dump($block_view_recent_courses->course_urls);

    return [
      'title' => $title,
      'course_urls' => $course_urls
    ];
  }

}