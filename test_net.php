<?php
/**
 * Created by PhpStorm.
 * Author: Misha Serenkov
 * Email: mi.serenkov@gmail.com
 * Date: 29.07.2017 15:47
 */

function generate_frequencies($text){
    // Удалим все кроме букв
    $text = preg_replace("/[^\p{L}]/iu", "", mb_convert_case($text, MB_CASE_LOWER));

    // Найдем параметры для расчета частоты
    $total = mb_strlen($text);
//    $total = 255;
    $data = count_chars($text);

    // Ну и сам расчет
    array_walk($data, function (&$item, $key, $total){
        $item = round($item/$total, 3);
    }, $total);

    return array_values($data);
}

$testPhrase = $argv[1];



$ann = fann_create_from_file("companies.net");

if ($ann) {
    $input = generate_frequencies($testPhrase);
    $calc_out = fann_run($ann, $input);
    printf("test (%s)\n", $testPhrase);
    var_dump($calc_out);
    fann_destroy($ann);
} else {
    die("Invalid file format" . PHP_EOL);
}
