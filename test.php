<?php
require '../../config.php';

$PAGE->set_context(context_system::instance());$PAGE->set_url('/');
$PAGE->requires->css('/blocks/cilp/css/raty.css');
echo $OUTPUT->header();
echo $OUTPUT->heading('DESCRIPTION FIELD');
$field = \local_dataforge\field::from_array([
    'type' => 'description',
    'default' => 'This is the field description',
]);
echo($field->render());

echo $OUTPUT->heading('TEXT FIELD');
$field = \local_dataforge\field::from_array([
    'type' => 'text',
    'default' => 'Dave',
    'title' => 'Name',
]);
$field->uservalue = 'Conn';
echo($field->render());