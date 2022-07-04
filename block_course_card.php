<?php

class block_course_card extends block_base
{
    public function init()
    {
        $this->title = "Block Course Card";

    }

    public function get_course_image($courseObj)
    {
        global $CFG;
        $course = new core_course_list_element($courseObj);

        $imageurl = '';
        foreach ($course->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                $imagepath = '/' . $file->get_contextid() .
                    '/' . $file->get_component() .
                    '/' . $file->get_filearea() .
                    $file->get_filepath() .
                    $file->get_filename();
                $imageurl = file_encode_url($CFG->wwwroot . '/pluginfile.php', $imagepath, false);
            }
        }
        if ($imageurl == "") {
            $imageurl = "";
        }

        if ($imageurl == '') {
            return new moodle_url('/blocks/course_card/dist/images/course.png');
        }

        return $imageurl;
    }

    public function get_course_participant($courseId) {
        global $DB;
        $result = $DB->get_record_sql("SELECT COUNT(1) total
        FROM {user_enrolments} A
        JOIN {enrol} B ON A.enrolid = B.id
        WHERE userid IS NOT NULL
        AND B.courseid = $courseId");

        return number_format($result->total, 0, ",", ".");
    }

    public function get_content()
    {
        global $OUTPUT;
        global $DB;

        $this->page->requires->css('/blocks/course_card/lib.css');
        $this->page->requires->css('/blocks/course_card/dist/css/splide.min.css');
        $this->page->requires->js(new moodle_url('/blocks/course_card/dist/js/splide.min.js'), true);
        $this->page->requires->js(new moodle_url('/blocks/course_card/lib.js'));

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->footer = '';

        // get title
        if(empty($this->config->title)){
            $this->title = "Course Card Group";
        } else {
            $this->title = $this->config->title;
        }

        // Add logic here to define your template data or any other content.
        $sql = "SELECT A.id,
               A.fullname course_name,
               B.name category
        FROM {course} A
        JOIN {course_categories} B ON A.category = B.id
        WHERE A.id > 1 ";

        // if category is set, filter course by category limit 10
        if(!empty($this->config->category)) {
            $category = $this->config->category;
            if($category != "") {
                $sql .= "AND A.category = $category";
            }
        } else if (!empty($this->config->course_list)){
            $course_list = $this->config->course_list;
            $course_list = preg_replace('/[^\d,]/i', '', $course_list);
            $sql .= "AND A.id in ($course_list)";
        }

        if(empty($this->config->limit)) {
            $limit = 10;
        } else {
            $limit = $this->config->limit;
        }
        $sql .= " ORDER BY A.id DESC LIMIT $limit";

        $courses = $DB->get_records_sql($sql);

        foreach ($courses as $course) {
            // check jp tag
            $tags = array_values(core_tag_tag::get_item_tags_array("core", "course", $course->id));
            if (in_array("1 JP", $tags)) {
                $course->has_jp = true;
            } else {
                $course->no_jp = true;
            }

            // get course url and image url
            $course->image_url = $this->get_course_image($course);
            $course->participant = $this->get_course_participant($course->id);
            $course->url = new moodle_url("/course/view.php", array("id" => $course->id));
        }

        $data = array(
            "courses" => array_values($courses),
            "title" => $this->title
        );

        if(!empty($this->config->desc)){
            $data["desc"] = $this->config->desc;
        }

        if(!empty($this->config->link)){
            $data["link"] = $this->config->link;
        }

        if(!empty($this->config->theme)){
            $theme = $this->config->theme;
        } else {
            $theme = "rose";
        }

        if(!empty($this->config->icon)){
            $data[$this->config->icon] = true;
        } else {
            $data["academic"] = true;
        }

    //    echo "<pre>";
    //    print_r($data);
    //    echo "</pre>";

        $this->content->text = $OUTPUT->render_from_template("block_course_card/content-tw-$theme", $data);

        return $this->content;
    }

    public function applicable_formats()
    {
        return [
            'site-index' => true
        ];
    }

    public function instance_allow_multiple()
    {
        return true;
    }

    public function hide_header()
    {
        return true;
    }
}