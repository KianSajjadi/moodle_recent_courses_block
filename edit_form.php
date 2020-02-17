<?php
 
class block_view_recent_courses_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // A sample string variable with a default value.
        $mform->addElement('selectyesno', 'config_short_instead_full', get_string('course_shortname_instead_of_fullname', 'block_view_recent_courses'));
 
    }
}