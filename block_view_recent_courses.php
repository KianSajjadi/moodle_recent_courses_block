<?php


class block_view_recent_courses extends block_base {
    const SITE_COURSE_ID = 1;
    const YES = 1;

    public function init() {
        $this->title = get_string('pluginname', 'block_view_recent_courses');
    }

    public function get_content() {

        if ($this->content !== null) {
            return $this->content;
        }

        $course_urls = $this->get_course_urls();
        $renderable = new block_view_recent_courses\output\main($course_urls);
        $renderer = $this->page->get_renderer('block_view_recent_courses');
        $this->content = new stdClass;
        $this->content->text = $renderer->render($renderable);

        return $this->content;
    }

    private function get_most_recent_viewed_course_ids() {
        global $DB, $USER;

        $course_ids = [];
        $records = $DB->get_records('logstore_standard_log', ['eventname' => '\core\event\course_viewed', 'userid' => $USER->id], 'timecreated desc');
        foreach ($records as $record) {
            if (count($course_ids) === 3) {
                return $course_ids;
            }

            if ($record->courseid == self::SITE_COURSE_ID) {
                continue;
            }

            if (!in_array($record->courseid, $course_ids)) {
                $course_ids[] = $record->courseid;
            }
        }
        return $course_ids;
    }

    private function get_course_names($course_id) {
        global $DB;

        if ($this->config->short_instead_full == self::YES) {
            return $DB->get_field_sql("SELECT shortname FROM {course} WHERE id IN ('$course_id')");
        } else {
            return $DB->get_field_sql("SELECT fullname FROM {course} WHERE id IN ('$course_id')");
        }

    }

    private function get_course_urls(){
        $course_urls = [];
        $course_ids = $this->get_most_recent_viewed_course_ids();
        $epoch_course_times = $this->get_course_last_visited_epoch_time(); 

        if (empty($course_ids)) {
            return [];
        }

        if (count($course_urls) === 3) {
            return $course_urls;
        }

        foreach ($course_ids as $course_id) {
            $course_name = $this->get_course_names($course_id);
            $human_readable_time = date('Y-m-d H:i:s', $epoch_course_times[$course_id-2]);
            $url_name = "$course_name" . "  -  " . "Last Visited:" . " " . "$human_readable_time";
            $course_url = html_writer::link(
                new moodle_url('http:localhost/course/view.php', ['id' => $course_id]),
                "$url_name"
            );

            if(!in_array($course_url, $course_urls)) {
                $course_urls[] = $course_url;
            }

        }

        return $course_urls;
    }

    private function get_course_last_visited_epoch_time() {
        global $DB, $USER;

        $epoch_course_times = [];
        $records = $DB->get_records('logstore_standard_log', ['eventname' => '\core\event\course_viewed', 'userid' => $USER->id], 'timecreated desc');
        foreach ($records as $record) {
            if (count($epoch_course_times) === 3) {
                return $epoch_course_times;
            }

            if ($record->timecreated == self::SITE_COURSE_ID) {
                continue;
            }

            if (!in_array($record->timecreated, $epoch_course_times)) {
                $epoch_course_times[] = $record->timecreated;
            }
        }
        return $epoch_course_times;
    }

}