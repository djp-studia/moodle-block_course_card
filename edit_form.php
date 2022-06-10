<?php

class block_course_card_edit_form extends block_edit_form {

    protected function specific_definition($mform) {
        global $DB;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Card Group Title Configuration
        $mform->addElement('text', 'config_title', "Group Title", 'size=50');
        $mform->setDefault('config_title', 'Course Group');
        $mform->setType('config_title', PARAM_RAW);

        // Card Group Description
        $mform->addElement('textarea', 'config_desc', "Group Description", 'wrap="virtual" rows="10" cols="50"');
        $mform->setDefault('config_desc', '');
        $mform->setType('config_desc', PARAM_RAW);

        // Card Group Title Configuration
        $mform->addElement('text', 'config_limit', "Number of Courses", 'type=number');
        $mform->setDefault('config_limit', 10);
        $mform->setType('config_limit', PARAM_INT);

        // course id list
        $mform->addElement('text', 'config_course_list', "Course List", array("placeholder" => "List of Course ID separated with comma, eg: 123,456,789", "size" => "100"));
        $mform->setType('config_course_list', PARAM_RAW);

        // choose course by category
        $category_select = array("" => "Select Category");
        foreach ($DB->get_records("course_categories", null, "", "id,name") as $cat) {
            $category_select[$cat->id] = $cat->name;
        }
        $mform->addElement('select', 'config_category', "List Course by Category", $category_select);
        $mform->setType('config_category', PARAM_RAW);

        // Card Group Title Configuration
        $mform->addElement('text', 'config_link', "Show All Link", 'size=50');
        $mform->setType('config_link', PARAM_RAW);

        // theme
        $theme = array(
            "rose" => "Rose",
            "sky" => "Sky",
            "emerald" => "Emerald",
            "purple" => "Purple"
        );
        $mform->addElement('select', 'config_theme', "Color Theme", $theme);
        $mform->setType('config_theme', PARAM_RAW);

        // logo
        $icon = array(
            "academic" => "Academic",
            "collection" => "Collection",
            "cursor" => "Cursor",
            "forward" => "Forward",
            "globe" => "Globe",
            "library" => "Library",
            "presentation" => "Presentation"
        );
        $mform->addElement('select', 'config_icon', "Icon", $icon);
        $mform->setType('config_icon', PARAM_RAW);
    }
}