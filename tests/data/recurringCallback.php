<?php

$callback = [
    'project_id' => 123,
    'recurring' => [
        'id' => 321,
        'status' => 'active',
        'type' => 'Y',
        'currency' => 'EUR',
        'exp_year' => '2025',
        'exp_month' => '12',
        'period' => 'D',
        'time' => '11',
    ],
    'signature' => 'efg6jmyFLj4skf91Np+XBIQnACXOiBYiRlKxSKaR9YpW5M4UWqOYRQD1tvuYRnsbAs3rym2AYUYvMvt1C26k/g==',
];

return json_encode($callback);
