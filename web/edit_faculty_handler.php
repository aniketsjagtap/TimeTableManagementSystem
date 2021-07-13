<?php
namespace MRBS;

require "defaultincludes.inc";

use MRBS\Form\Form;

// Check the CSRF token.
Form::checkToken();

// Check the user is authorised for this page
checkAuthorised(this_page());

print_r($_POST);
// UPDATE THE DATABASE
// -------------------

if (empty($_POST['id']))
{
  throw new \Exception('$faculty is empty');
}

// Initialise the error array
$errors = array();

// Clean up the address list replacing newlines by commas and removing duplicates
$area_admin_email = clean_address_list($_POST['_email']);
// Validate email addresses
if (!validate_email_list($_POST['email']))
{
  $errors[] = 'invalid_email';
}

// Check that the time formats are correct (hh:mm).  They should be, because
// the HTML5 element or polyfill will force them to be, but just in case ...
// (for example if we are relying on a polyfill and JavaScript is disabled)
// Errors in the form data - go back to the form

if (!empty($errors))
{
  $query_string = "faculty=$_POST['id']";
  foreach ($errors as $error)
  {
    $query_string .= "&errors[]=$error";
  }
  location_header("edit_faculty.php?$query_string");
}

// Everything is OK, update the database

$sql = "UPDATE " . _tbl('faculty') . " SET ";
$sql_params = array();
$assign_array = array();
$assign_array[] = "name=?";
$sql_params[] = $_POST['faculty_name'];
$assign_array[] = "disabled=?";
$sql_params[] = $_POST['faculty_disabled'];
$assign_array[] = "email=?";
$sql_params[] = $_POST['faculty_email'];
$assign_array[] = "description=?";
$sql_params[] = $_POST['faculty_description'];

$sql .= implode(",", $assign_array) . " WHERE id=?";
$sql_params[] = $_POST['faculty_id'];
echo("****");
print_r($sql);
print_r($sql_params);

db()->command($sql, $sql_params);


// Go back to the admin page
location_header("admin.php?day=$day&month=$month&year=$year&area=$area");
