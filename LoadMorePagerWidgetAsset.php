<?php

namespace mranger\load_more_pager;

use yii\web\AssetBundle;

class LoadMorePagerWidgetAsset extends AssetBundle {
	public $sourcePath = __DIR__ . '/assets';

	public $js = [
		'js/load-more-pagination.js',
	];

	public $css = [
		'css/load-more-style.css',
	];

	public $depends = [
		'yii\web\JqueryAsset',
	];
}
