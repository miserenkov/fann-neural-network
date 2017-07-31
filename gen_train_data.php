<?php
/**
 * Created by PhpStorm.
 * Author: Misha Serenkov
 * Email: mi.serenkov@gmail.com
 * Date: 29.07.2017 15:28
 */
function generate_frequencies($text, &$firstLetter){
    // Удалим все кроме букв
    $text = preg_replace("/[^\p{L}\d]/iu", "", mb_convert_case($text, MB_CASE_LOWER));

    $firstLetter = substr($text, 1, 1);

    // Найдем параметры для расчета частоты
//    $total = mb_strlen($text);
    $total = 255;
    $data = count_chars($text);

    // Ну и сам расчет
    array_walk($data, function (&$item, $key, $total){
        $item = round($item/$total, 3);
    }, $total);

    return array_values($data);
}


$companies = explode("\n", file_get_contents('companies.csv'));

$dataCount = count($companies);

$preparedData = [];
for ($i = 0; $i < $dataCount; $i++) {
    $ident = '';
    $freq = generate_frequencies($companies[$i], $ident);
    $preparedData[$ident][] = $freq;
}

$getOutputTemplate = function ($length) {
    $outputTemplate = [];
    for($i = 0; $i < $length; $i++) {
        $outputTemplate[] = 0;
    }

    return $outputTemplate;
};


foreach ($preparedData as $char => $freqs) {
    file_put_contents("data/companies/$char.data", sprintf("%d 256 %d\n", count($freqs), count($freqs)));

    for($i = 1; $i <= count($freqs); $i++) {
        $company = $companies[array_rand($companies)];

        $output = $getOutputTemplate(count($freqs));

        $output[$i-1] = 1;

        file_put_contents("data/companies/$char.data", sprintf($i === count($freqs) ? "%s\n%s" : "%s\n%s\n", implode(' ', $freqs[$i-1]), implode(' ', $output)), FILE_APPEND);

    }
}