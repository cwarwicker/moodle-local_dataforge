<?php
require '../../config.php';

$PAGE->set_context(context_system::instance());$PAGE->set_url('/');
$PAGE->requires->css('/blocks/cilp/css/raty.css');
echo $OUTPUT->header();

$type = optional_param('type', 'editing', PARAM_TEXT);
$method = $type === 'editing' ? 'render' : 'display';

    echo '<form action="" method="post">';

    for ($i = 1; $i <= 20; $i++) {
        try {
            $field = \local_dataforge\field::load($i);
            $field->load_user($USER->id);
            if (!empty($_POST)) {
                $field->save();
            }
            echo($field->$method());
        } catch (dml_missing_record_exception $e) {
            // do nothing
        }
    }

    echo '<input type="hidden" name="sesskey" value="' . sesskey() . '">';
    echo '<button>Test Save</button>';
    echo '</form>';

echo $OUTPUT->footer();