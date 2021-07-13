<?php
namespace MRBS;

require "defaultincludes.inc";

use MRBS\Form\Form;

// Check the CSRF token.
Form::checkToken();

// Check the user is authorised for this page
checkAuthorised(this_page());

print_r($_POST);

$sql = "UPDATE " . _tbl('course') . " SET ";
$sql_params = array();
$assign_array = array();
$assign_array[] = "name=?";
$sql_params[] = $_POST['course_name'];
$assign_array[] = "disabled=?";
$sql_params[] = $_POST['course_disabled'];
$assign_array[] = "description=?";
$sql_params[] = $_POST['course_description'];

$sql .= implode(",", $assign_array) . " WHERE id=?";
$sql_params[] = $_POST['course_id'];
echo("****");
print_r($sql);
print_r($sql_params);

db()->command($sql, $sql_params);


// Go back to the admin page
location_header("admin.php?day=$day&month=$month&year=$year&area=$area");
