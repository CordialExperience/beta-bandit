<?php

require("../src/Cordial/BetaBandit.php");

use Cordial\BetaBandit;

if (!$options = json_decode($argv[1],true)) {
    $options = [
        'a' => [
            'impressions' => 0,
            'conversions' => 0,
            'simulation' => .03
        ],
        'b' => [
            'impressions' => 0,
            'conversions' => 0,
            'simulation' => .1
        ]
    ];
} else {
    foreach ($options as $rec => &$data) {
        if (!isset($data['impressions'])) {
            $data['impressions'] = 0;
        }
        if (!isset($data['conversions'])) {
            $data['conversions'] = 0;
        }
    }
}

for ($x=0; $x< 1000; $x++) {
    $rec = BetaBandit::getVariant($options);
    $options[$rec]['impressions']++;
    echo $rec;
    if (mt_rand(1,100) <= $options[$rec]['simulation'] * 100) {
        $options[$rec]['conversions']++;
        echo $rec;
    }
    echo "\n";
    usleep(150000);
}
