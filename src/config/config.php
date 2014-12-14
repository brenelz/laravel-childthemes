<?php 

return [
	'switchByClass' => 'Brenelz\Childthemes\ThemeSwitcher\ByHost',
	'defaultTheme' => 'global2015',
	'availableThemes' => [
		'global2015'=> [
			'domains' => [
				'test.dev'
			],
			'variables' => [
				'siteName' => 'Global Auction Guide'
			],
		],
		'auctionbill2015' => [
			'domains' => [
				'globalauctionguide.dev'
			],
			'variables' => [
				'siteName' => 'Auction Bill'
			],
		],
	],
];