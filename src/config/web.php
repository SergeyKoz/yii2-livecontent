<?php
$config = [
    'modules' => [
        'livecontent' => [
            'class' => 'ssoft\livecontent\Module',
            'editorOptions' => [
                'clientOptions' => [
                    'autoParagraph' => true
                ],
            ]
        ]
    ],
];

$config['bootstrap'][] = 'livecontent';

return $config;
