<?php
require '../../config.php';

$PAGE->set_context(context_system::instance());$PAGE->set_url('/');
$PAGE->requires->css('/blocks/cilp/css/raty.css');
echo $OUTPUT->header();

$type = optional_param('type', 'editing', PARAM_TEXT);
if ($type === 'editing') {


    //echo $OUTPUT->heading('DESCRIPTION FIELD');
    //$field = \local_dataforge\field::from_array([
    //    'type' => 'description',
    //    'default' => 'This is the field description',
    //]);
    //echo($field->render());
    //
    //echo $OUTPUT->heading('TEXT FIELD');
    //$field = \local_dataforge\field::from_array([
    //    'type' => 'text',
    //    'default' => 'Dave',
    //    'title' => 'Name',
    //]);
    //$field->uservalue = 'Conn';
    //echo($field->render());

    //echo $OUTPUT->heading('CHECKBOX FIELD');
    //$field = \local_dataforge\field::from_array([
    //    'type' => 'checkbox',
    //    'title' => 'My checkbox field',
    //    'options' => json_encode(['choices' => [123 => 'ABC', 234 => 'DEF', 345 => 'XYZ'], 'inline' => false]),
    //]);
    //$field->uservalue = '234,123';
    //echo($field->render());

    //echo $OUTPUT->heading('RADIO FIELD');
    //$field = \local_dataforge\field::from_array([
    //    'type' => 'radio',
    //    'title' => 'My radio field',
    //    'options' => json_encode(['choices' => [123 => 'ABC', 234 => 'DEF', 345 => 'XYZ'], 'inline' => false]),
    //]);
    //$field->uservalue = '345';
    //echo($field->render());

//    echo $OUTPUT->heading('DATE FIELD');
//    $field = \local_dataforge\field::from_array([
//        'type' => 'date',
//        'title' => 'My date field',
//        'options' => json_encode(['time' => true, 'min' => '2018-06-07 00:00', 'max' => '2018-06-08 00:00']),
//    ]);
//    $field->uservalue = '2018-06-07 00:05';
//    echo($field->render());

//    echo $OUTPUT->heading('EDITOR FIELD');
//    $field = \local_dataforge\field::from_array([
//        'type' => 'editor',
//        'title' => 'My editor field',
//        'instructions' => 'Type some stuff here',
//        'options' => json_encode(['rows' => 10]),
//    ]);
//    $field->uservalue = 'This is the <b>content</b> of the editor';
//    echo($field->render());

//    echo $OUTPUT->heading('SELECT FIELD (SINGLE)');
//    $field = \local_dataforge\field::from_array([
//        'type' => 'select',
//        'title' => 'My select menu field',
//        'instructions' => 'Type some stuff here',
//        'options' => json_encode(['choices' => [123 => 'ABC', 234 => 'DEF', 345 => 'XYZ']]),
//    ]);
//    $field->uservalue = '234';
//    echo($field->render());
//
//    echo $OUTPUT->heading('SELECT FIELD (MULTI)');
//    $field = \local_dataforge\field::from_array([
//        'type' => 'select',
//        'title' => 'My select menu field',
//        'instructions' => 'Type some stuff here',
//        'options' => json_encode(['choices' => [123 => 'ABC', 234 => 'DEF', 345 => 'XYZ'], 'multi' => true]),
//    ]);
//    $field->uservalue = '234,345';
//    echo($field->render());

//    echo $OUTPUT->heading('MATRIX FIELD');
//    $field = \local_dataforge\field::from_array([
//        'type' => 'matrix',
//        'title' => 'My matrix field',
//        'instructions' => 'Type some stuff here',
//        'options' => json_encode([
//            'rows' => [
//                ['row_id' => 1, 'row_name' => 'Row A'],
//                ['row_id' => 2, 'row_name' => 'Row B'],
//            ],
//            'columns' => [
//                ['col_id' => 1, 'col_name' => 'C1'],
//                ['col_id' => 2, 'col_name' => 'C2'],
//                ['col_id' => 3, 'col_name' => 'C3'],
//            ]
//        ]),
//    ]);
//    $field->uservalue = json_encode([1 => 1, 2 => 3]);
//    echo($field->render());

//    echo $OUTPUT->heading('FILE FIELD');
//    $field = \local_dataforge\field::from_array([
//        'type' => 'file',
//        'title' => 'My file upload',
//        'instructions' => 'Type some stuff here',
//        'options' => json_encode(['accepted_types' => ['image/jpg'], 'maxfiles' => 1]),
//    ]);
//    // todo - user data
//    echo($field->render());

    echo $OUTPUT->heading('RATING FIELD');
    $field = \local_dataforge\field::from_array([
        'type' => 'rating',
        'title' => 'My rating field',
        'instructions' => 'Type some stuff here',
        'options' => json_encode(['number' => 5]),
    ]);
    $field->uservalue = 3;
    echo($field->render());


}

echo $OUTPUT->footer();