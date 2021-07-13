<?php
namespace MRBS;

use MRBS\Form\Form;
use MRBS\Form\ElementDiv;
use MRBS\Form\ElementInputCheckbox;
use MRBS\Form\ElementInputNumber;
use MRBS\Form\ElementInputSubmit;
use MRBS\Form\ElementFieldset;
use MRBS\Form\ElementLegend;
use MRBS\Form\ElementP;
use MRBS\Form\ElementSelect;
use MRBS\Form\ElementSpan;
use MRBS\Form\FieldButton;
use MRBS\Form\FieldDiv;
use MRBS\Form\FieldInputCheckbox;
use MRBS\Form\FieldInputCheckboxGroup;
use MRBS\Form\FieldInputRadioGroup;
use MRBS\Form\FieldInputEmail;
use MRBS\Form\FieldInputNumber;
use MRBS\Form\FieldInputSubmit;
use MRBS\Form\FieldInputText;
use MRBS\Form\FieldInputTime;
use MRBS\Form\FieldSelect;
use MRBS\Form\FieldSpan;
use MRBS\Form\FieldTextarea;

require "defaultincludes.inc";
require_once "mrbs_sql.inc";


function get_fieldset_errors(array $errors)
{
  $fieldset = new ElementFieldset();
  $fieldset->addLegend('')
           ->setAttribute('class', 'error');

  foreach ($errors as $error)
  {
    $element = new ElementP();
    $element->setText(get_vocab($error));
    $fieldset-> addElement($element);
  }

  return $fieldset;
}


function get_fieldset_general(array $data)
{
  global $timezone, $auth;

  $fieldset = new ElementFieldset();
//  $fieldset->addLegend(get_vocab('general_settings'));

  // Area name
  $field = new FieldInputText();
  $field->setLabel(get_vocab('name'))
        ->setControlAttributes(array('id'        => 'course_name',
                                     'name'      => 'course_name',
                                     'required'  => true,
                                     'maxlength' => maxlength('course.course_name'),
                                     'value'     => $data['name']));
  $fieldset->addElement($field);

  // Status - Enabled or Disabled
  $options = array('0' => get_vocab('enabled'),
    '1' => get_vocab('disabled'));
  $value = ($data['disabled']) ? '1' : '0';
  $field = new FieldInputRadioGroup();
  $field->setAttribute('id', 'status')
    ->setLabel(get_vocab('status'))
    ->setLabelAttributes(array('title' => get_vocab('disabled_course_note')))
    ->addRadioOptions($options, 'course_disabled', $value, true);
  $fieldset->addElement($field);
	
  // Area name
  $field = new FieldInputText();
  $field->setLabel(get_vocab('description'))
        ->setControlAttributes(array('id'        => 'course_description',
                                     'name'      => 'course_description',
                                     'required'  => true,
                                     'maxlength' => maxlength('course.course_description'),
                                     'value'     => $data['description']));
  $fieldset->addElement($field);

  return $fieldset;
}

function get_fieldset_submit_buttons()
{
  $fieldset = new ElementFieldset();

  // The back and submit buttons
  $field = new FieldInputSubmit();

  $back = new ElementInputSubmit();
  $back->setAttributes(array('value'      => get_vocab('backadmin'),
                             'formaction' => multisite('admin.php')));
  $field->addLabelClass('no_suffix')
        ->addLabelElement($back)
        ->setControlAttribute('value', get_vocab('change'));
  $fieldset->addElement($field);

  return $fieldset;
}

// Check the user is authorised for this page
checkAuthorised(this_page());
$context = array(
  'view'      => $view,
  'view_all'  => $view_all,
  'year'      => $year,
  'month'     => $month,
  'day'       => $day,
  'area'      => isset($area) ? $area : null,
  'room'      => isset($room) ? $room : null,
  'course'   => isset($course) ? $course : null
);

print_header($context);
print_r($_GET['course']);
// Get the details for this area
if (!isset($_GET['course']) || is_null($data = get_course_details($_GET['course'])))
{
  fatal_error(get_vocab('invalid_course'));
}
print_r($data);

$errors = get_form_var('errors', 'array');

// Generate the form
$form = new Form();

$attributes = array('id'     => 'edit_course',
                    'class'  => 'standard',
                    'action' => multisite('edit_course_handler.php'),
                    'method' => 'post');

$form->setAttributes($attributes)
     ->addHiddenInput('course_id', $data['id']);

$outer_fieldset = new ElementFieldset();

$outer_fieldset->addLegend(get_vocab('editcourse'))
               ->addElement(get_fieldset_errors($errors))
               ->addElement(get_fieldset_general($data))
              // ->addElement(get_fieldset_times())
              // ->addElement(get_fieldset_periods())
              // ->addElement(get_fieldset_booking_policies())
              // ->addElement(get_fieldset_confirmation_settings())
              // ->addElement(get_fieldset_approval_settings())
              // ->addElement(get_fieldset_privacy_settings())
              // ->addElement(get_fieldset_privacy_display())
               ->addElement(get_fieldset_submit_buttons());
$form->addElement($outer_fieldset);

$form->render();

//getAllFaculty();


print_footer();
