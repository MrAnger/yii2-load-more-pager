<?php

namespace mranger\load_more_pager;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\web\JsExpression;

/**
 * @author MrAnger
 */
class LoadMorePager extends Widget {
	const LOADER_APPEND_TYPE_BODY = 1;
	const LOADER_APPEND_TYPE_CONTENT = 2;
	const LOADER_APPEND_TYPE_BUTTON = 3;
	const LOADER_APPEND_TYPE_PREPEND_BUTTON = 4;

	/**
	 * @var integer|string
	 */
	public $id;

	/**
	 * @var Pagination
	 */
	public $pagination;
	/**
	 * @var array
	 */
	public $options = ['class' => 'btn btn-default'];

	/**
	 * @var string
	 */
	public $template = '{button}';

	/**
	 * @var bool
	 */
	public $hideOnSinglePage = true;

	/**
	 * @var bool
	 */
	public $loaderShow = true;

	/**
	 * @var integer
	 */
	public $loaderAppendType = self::LOADER_APPEND_TYPE_BUTTON;

	/**
	 * @var string
	 */
	public $loaderTemplate = '<i class="load-more-loader"></i>';

	/**
	 * @var bool
	 */
	public $includeCssStyles = true;

	/**
	 * @var string
	 */
	public $contentSelector;

	/**
	 * @var string
	 */
	public $contentItemSelector;

	/**
	 * @var JsExpression
	 */
	public $onLoad;

	/**
	 * @var JsExpression
	 */
	public $onAfterLoad;

	/**
	 * @var JsExpression
	 */
	public $onFinished;

	/**
	 * @var JsExpression
	 */
	public $onError;

	/**
	 * @var string
	 */
	public $buttonText = 'Загрузить больше';

	public function init() {
		if ($this->pagination === null) {
			throw new InvalidConfigException('The "pagination" property must be set.');
		}

		if (!isset($this->id)) {
			throw new InvalidConfigException('The "id" property must be set.');
		} else {
			$this->options['id'] = $this->id;
		}

		if (!isset($this->contentSelector)) {
			throw new InvalidConfigException('The "contentSelector" property must be set.');
		}

		if (!isset($this->contentItemSelector)) {
			throw new InvalidConfigException('The "contentItemSelector" property must be set.');
		}
	}

	public function run() {
		$this->registerPlugin();

		$buttonHtml = $this->renderButton();

		echo str_replace('{button}', $buttonHtml, $this->template);
	}

	public function renderButton() {
		$pageCount = $this->pagination->getPageCount();
		if ($pageCount < 2 && $this->hideOnSinglePage) {
			return '';
		}

		$urls = [];

		list($beginPage, $endPage) = $this->getPageRange();
		for ($i = $beginPage; $i <= $endPage; ++$i) {
			$urls[] = $this->pagination->createUrl($i);
		}

		$this->options = array_merge($this->options, [
			'data-urls' => Json::encode($urls),
			'data-pjax' => 0,
		]);

		return Html::a($this->buttonText, '#', $this->options);
	}


	protected function registerPlugin() {
		$js = [];

		$view = $this->getView();

		$asset = LoadMorePagerWidgetAsset::register($view);

		if (!$this->includeCssStyles) {
			$asset->css = [];
		}

		$options = [
			'id'                  => $this->id,
			'contentSelector'     => $this->contentSelector,
			'contentItemSelector' => $this->contentItemSelector,
			'loaderShow'          => $this->loaderShow,
			'loaderAppendType'    => $this->loaderAppendType,
			'loaderTemplate'      => $this->loaderTemplate,
			'buttonText'          => $this->buttonText,
			'onLoad'              => (($this->onLoad !== null) ? $this->onLoad : null),
			'onAfterLoad'         => (($this->onAfterLoad !== null) ? $this->onAfterLoad : null),
			'onFinished'          => (($this->onFinished !== null) ? $this->onFinished : null),
			'onError'             => (($this->onError !== null) ? $this->onError : null),
		];

		$options = Json::encode($options);

		$js[] = "LoadMorePagination.addPagination($options);";

		$view->registerJs(implode("\n", $js));
	}

	/**
	 * @return array the begin and end pages that need to be displayed.
	 */
	protected function getPageRange() {
		$currentPage = $this->pagination->getPage();
		$pageCount = $this->pagination->getPageCount();

		return [$currentPage + 1, $pageCount - 1];
	}
}
