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
    'signature' => 'AThqkBCZ6WZtY3WrMV28o7SM/vq6OIVF9qiVbELN4e/Ux59Lb5LFFnEuTq6bHa5pRvaPIkQGABXdpIrNLaeJdQ==',
];

return json_encode($callback);
