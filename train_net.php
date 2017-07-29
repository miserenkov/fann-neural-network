<?php
/**
 * Created by PhpStorm.
 * Author: Misha Serenkov
 * Email: mi.serenkov@gmail.com
 * Date: 29.07.2017 15:44
 */

$dataCount = (int)$argv[1];

$num_input = 256;
$num_output = $dataCount;
$num_layers = 3;
$num_neurons_hidden = 128;
$desired_error = 0.00001;
$max_epochs = 100000;
$epochs_between_reports = 1000;

$layers = [];

for ($i = 1; $i <= $num_layers; $i++) {
    if ($i === 1) {
        $layers[] = $num_input;
    } elseif ($i === $num_layers) {
        $layers[] = $num_output;
    } else {
        $layers[] = $num_neurons_hidden;
    }
}

$ann = fann_create_standard_array($num_layers, $layers);

if ($ann) {
    fann_set_activation_function_hidden($ann, FANN_SIGMOID_SYMMETRIC);
    fann_set_activation_function_output($ann, FANN_SIGMOID_SYMMETRIC);

    $filename = "companies.data";
    if (fann_train_on_file($ann, $filename, $max_epochs, $epochs_between_reports, $desired_error)) {
        fann_save($ann, "companies.net");
    }

    fann_destroy($ann);
}

