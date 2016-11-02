(function ($) {
    const LOADER_APPEND_TYPE_BODY = 1;
    const LOADER_APPEND_TYPE_CONTENT = 2;
    const LOADER_APPEND_TYPE_BUTTON = 3;
    const LOADER_APPEND_TYPE_PREPEND_BUTTON = 4;

    var plugin = function () {
        var selfObj = this,
            paginationList = {};

        this.addPagination = function (params) {
            paginationList[params['id']] = params;

            selfObj.initPagination(params['id']);
        };

        this.getPaginationParams = function (id) {
            return paginationList[id];
        };

        this.initPagination = function (id) {
            var params = selfObj.getPaginationParams(id);

            if (!params)
                return false;

            $('#' + params.id).data('loading', false);

            $(document).on('click', '#' + params.id, function (e) {
                e.preventDefault();

                var $el = $(this),
                    urlList = $el.data('urls'),
                    $loaderParent = false;

                // Switch loader parent element
                switch (params.loaderAppendType) {
                    case LOADER_APPEND_TYPE_BODY:
                        $loaderParent = $('body');
                        break;
                    case LOADER_APPEND_TYPE_CONTENT:
                        $loaderParent = $(params.contentSelector);
                        break;
                    case LOADER_APPEND_TYPE_BUTTON:
                        $loaderParent = $el;
                        break;
                    case LOADER_APPEND_TYPE_PREPEND_BUTTON:
                        $loaderParent = $el;
                        break;
                }

                if ($el.data('loading')) {
                    return false;
                }

                if (urlList.length > 0) {
                    var urlToLoad = urlList[0],
                        $contentHolder = $(params.contentSelector);

                    $el.data('loading', true);

                    // Show Loader
                    if (params.loaderShow) {
                        var $loader = loaderShow(params.loaderTemplate, $loaderParent, params.loaderAppendType);
                    }

                    // Event OnLoad
                    if (params.onLoad) {
                        params.onLoad(params);
                    }

                    $.ajax({
                        url: urlToLoad,
                        type: 'GET',
                        success: function (responseHtml) {
                            // Hide Loader
                            if (params.loaderShow) {
                                loaderHide($loader);
                            }

                            var $page = $(responseHtml),
                                $content = $page.find(params.contentSelector);

                            $content.find(params.contentItemSelector).each(function (key, itemEl) {
                                $(itemEl).insertAfter($contentHolder.find(params.contentItemSelector + ':last'));
                            });

                            urlList.splice(0, 1);
                            $el.data('urls', urlList);

                            $el.data('loading', false);

                            // Event OnAfterLoad
                            if (params.onAfterLoad) {
                                params.onAfterLoad(params);
                            }

                            if (urlList.length == 0) {
                                $el.hide();

                                // Event OnFinished
                                if (params.onFinished) {
                                    params.onFinished(params);
                                }
                            }
                        },
                        error: function (e) {
                            console.log(e);

                            // Hide Loader
                            if (params.loaderShow) {
                                loaderHide($loader);
                            }

                            // Event OnError
                            if (params.onError) {
                                params.onError(params);
                            }
                        }
                    });
                } else {
                    $el.hide();

                    // Event OnFinished
                    if (params.onFinished) {
                        params.onFinished(params);
                    }
                }

                function loaderShow(template, $parent, appendType) {
                    var $loader = $(template);

                    switch (params.loaderAppendType) {
                        case LOADER_APPEND_TYPE_BODY:
                        case LOADER_APPEND_TYPE_CONTENT:
                            $loader.appendTo($parent);
                            break;
                        case LOADER_APPEND_TYPE_PREPEND_BUTTON:
                            $loader.insertBefore($parent);
                            break;
                        case LOADER_APPEND_TYPE_BUTTON:
                            $parent.html('');
                            $loader.appendTo($parent);
                            $('<span></span>').text(params.buttonText).appendTo($parent);
                            break;
                    }

                    return $loader;
                }

                function loaderHide($el) {
                    $el.remove();
                }
            });
        };
    };

    window.LoadMorePagination = new plugin();
})(jQuery);