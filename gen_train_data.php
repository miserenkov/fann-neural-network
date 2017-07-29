<?php
/**
 * Created by PhpStorm.
 * Author: Misha Serenkov
 * Email: mi.serenkov@gmail.com
 * Date: 29.07.2017 15:28
 */
function generate_frequencies($text){
    // Удалим все кроме букв
    $text = preg_replace("/[^\p{L}\d]/iu", "", mb_convert_case($text, MB_CASE_LOWER));

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


$companies = explode("\n", file_get_contents('companies.csv'));

$dataCount = (int)$argv[1];

$outputTemplate = [];
for($i = 0; $i < $dataCount; $i++) {
    $outputTemplate[] = 0;
}

@unlink('companies.trained');
@unlink('companies.data');

file_put_contents("companies.data", sprintf("%d 256 %d\n", $dataCount, $dataCount));
for($i = 1; $i <= $dataCount; $i++) {
    $company = $companies[$i - 1];

    file_put_contents('companies.trained', $company."\n", FILE_APPEND);

    $output = $outputTemplate;

    $output[$i-1] = 1;

    $freq = generate_frequencies($company);

    file_put_contents("companies.data", sprintf($i === $dataCount ? "%s\n%s" : "%s\n%s\n", implode(' ', $freq), implode(' ', $output)), FILE_APPEND);
}