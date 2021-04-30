<?php

class ControllerModuleCourses{
    public static function index(){
        $data = array();
        $courses = ModelModuleCourses::get();
        $data['count'] = count($courses);
        $data['courses'] = $courses;

        return $data;
    }
}

class ModelModuleCourses{
    public static function get(){
        global $db;
        $data = array();
        $popularCourses = $db->get_results("SELECT playlists.id, playlists.title, playlists.price, playlists.description, playlists.picture, playlists.views, USER.name AS author, USER.avatar AS authorAvatar FROM .vibe_playlists AS playlists INNER JOIN vibe_users AS USER ON playlists.owner = USER.id AND playlists.price IS NOT NULL AND playlists.price <> 0 ORDER BY playlists.views DESC LIMIT 7");
        foreach ($popularCourses as $course){
            $data_course = array();
            $data_course['id'] = $course->id;
            $data_course['title'] = $course->title;
            $data_course['price'] = $course->price;
            $data_course['views'] = $course->views;
            $data_course['picture'] = $course->picture;
            $data[] = $data_course;
        }
        return $data;
    }
}