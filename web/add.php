<?php
namespace MRBS;

use MRBS\Form\Form;

require "defaultincludes.inc";
require_once "mrbs_sql.inc";


// Check the CSRF token
Form::checkToken();

// Check the user is authorised for this page
checkAuthorised(this_page());

// Get non-standard form variables
$name = get_form_var('name', 'string', null, INPUT_POST);
$description = get_form_var('description', 'string', null, INPUT_POST);
$capacity = get_form_var('capacity', 'int', null, INPUT_POST);
$room_admin_email = get_form_var('room_admin_email', 'string', null, INPUT_POST);
$type = get_form_var('type', 'string', null, INPUT_POST);

// This file is for adding new areas/rooms
$error = '';

// First of all check that we've got an area or room name
if (!isset($name) || ($name === ''))
{
  $error = "empty_name";
}

// we need to do different things depending on if it's a room
// or an area
elseif ($type == "area")
{
  $area = mrbsAddArea($name, $error);
}

elseif ($type == "room")
{
  $room = mrbsAddRoom($name, $area, $error, $description, $capacity, $room_admin_email);
}
elseif($type == "faculty")
{
//	print_r($_POST);
  //echo($name.":".$faculty_email.":".$status.":".$faculty_description);
  $faculty = mrbsAddFaculty($_POST['name'], $_POST['faculty_email'], '1', $_POST['faculty_description']);
}
elseif($type == "division")
{
  $division = mrbsAddDivision($_POST['name'], $_POST['division_email'], '1', $_POST['division_description']);
}
elseif($type == "course")
{
  $course = mrbsAddCourse($_POST['name'], '1', $_POST['course_description']);
}

$returl = "admin.php?area=$area" . (!empty($error) ? "&error=$error" : "");
location_header($returl);

