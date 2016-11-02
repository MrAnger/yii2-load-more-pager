yii2 load-more-pagination widget
===========================
Это виджет пагинации для Yii 2 версии. Делал его для себя. Главной его особенностью является то, что его верстку можно полностью кастомизировать, а еще самое главное, это то, что виджет пагинации продолжит свою работу даже после того, как его код (верстка/эллемент) будет обновлен по аяксу/пжаксу, так как привязка на событие клика по кнопке идет не на конкретный эллемент, а на селектор (jQuery.on).

## Установка

```
php composer.phar require --prefer-dist mranger/yii2-load-more-pager "*"
```

или

```json
"mranger/yii2-load-more-pager": "*"
```


## Использование

```php
ListView::widget([
	'id'           => 'comment-list',
	'dataProvider' => $dataProvider,
	'options'      => [
	    'tag'   => 'ol',
		'class' => 'commentlist',
	],
	'itemOptions'  => [
		'tag' => 'li',
	],
	'pager'        => [
		'class' => 'mranger\load_more_pager\LoadMorePager',
		'id' => 'comment-list-pagination',
		'contentSelector' => '#comment-list',
        'contentItemSelector' => '.comment:not(.even)',
	],
])
```
Следует заметить, что обязательно нужно указать уникальный id пагинатора, так как иначе правильная работа не гарантируется.


## Настройки

```php
'pager' => [
	'class'               => 'mranger\load_more_pager\LoadMorePager',
	'id'                  => 'comment-list-pagination',
	'buttonText'          => 'Больше комментариев', // Текст на кнопке пагинации
    'template'            => '<div class="text-center">{button}</div>', // Шаблон вывода кнопки пагинации
    'contentSelector'     => '#comment-list', // Селектор контента
    'contentItemSelector' => '.comment:not(.even)', // Селектор эллементов контента
    'includeCssStyles'    => true, // Подключать ли CSS стили виджета, или вы оформите пагинацию сами
    'loaderShow'          => true, // Отображать ли индикатор загрузки
    'loaderAppendType'    => LoadMorePager::LOADER_APPEND_TYPE_BUTTON, // Тот эллемент, к которому будет прикреплен индикатор загрузки. Варианты: тег body, после контента, перед кнопкой пагинации, внутри кнопки пагинации
    'loaderTemplate'      => '<i class="load-more-loader"></i>', // Шаблон индикатора загрузки
    'options'             => [], // Массив опций кнопки паганации
    'onLoad'              => null, // Событие javascript которое будет вызываться в момент начала загрузки новых эллементов, обработчик должен быть описан через JsExpression, в функцию будет передаваться объект с настройками пагинатора, которые вы указали при инициализации
    'onAfterLoad'         => null, // Событие javascript которое будет вызываться в момент окончания загрузки новых эллементов
    'onFinished'          => null, // Событие javascript которое будет вызываться в момент, когда все страницы паганации були загружены
    'onError'             => null, // Событие javascript которое будет вызываться в момент, когда произошла ошибка при загрузке новых эллементов
],
```