<?php
return [
	'adminEmail' => 'admin@example.com',

	// Tema default yang akan digunakan jika tidak ada pengaturan yang dilakukan.
	'defaultTheme' => 'stackadmin',

	// konfigurasi untuk menu admin, dapat dikelola melalui RBAC manager.
	'mdm.admin.configs' => [
		'menuTable' => 'siks_menus',
	],

	'stackadmin' => [
        'bgLogin' => '@webpublic/siks/bg/samuel-zeller-JuFcQxgCXwA-unsplash.jpg',
        'search' => [
            'action' => '/archive/site/index',
            'attribute' => 'title',
        ],
	],
];
