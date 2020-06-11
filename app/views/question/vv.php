<!DOCTYPE html>
<!-- saved from url=(0041)http://www.freejs.net/demo/524/index.html -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>jQuery fSelect Plugin Demo</title>
<link href="./jQuery fSelect Plugin Demo_files/css" rel="stylesheet" type="text/css">
<link href="./jQuery fSelect Plugin Demo_files/fSelect.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="./jQuery fSelect Plugin Demo_files/demo.css">
<style>
body { font-family:'Open Sans';}
.container { margin:10px auto; max-width:640px;}
</style>
</head>

<body>
<h1 class="logo"><a href="http://www.freejs.net/" title="freejs首页"><img src="./jQuery fSelect Plugin Demo_files/logo.png" height="47" width="500" alt="freejs首页"></a></h1>
<div id="main_demo">
    <div align="center">
      <h2><a href="http://www.freejs.net/article_biaodan_524.html">select 下拉框多选，用select代替checkbox</a></h2></div>
  <div align="center" style="padding:20px;"><script type="text/javascript"><!--
    google_ad_client = "pub-4490194096475053";
    /* 728x90, 创建于 08-12-8 */
    google_ad_slot = "0403648181";
    google_ad_width = 728;
    google_ad_height = 90;
    //-->
    </script>
  <script type="text/javascript" src="./jQuery fSelect Plugin Demo_files/f.txt">
    </script></div>
<div class="container">
<div class="fs-wrap multiple"><div class="fs-label-wrap"><div class="fs-label">C++, C#, Object C</div><span class="fs-arrow"></span></div><div class="fs-dropdown"><div class="fs-search"><input type="search" placeholder="Search"></div><div class="fs-options"><div class="fs-optgroup"><div class="fs-optgroup-label">Languages</div><div class="fs-option selected" data-value="cp" data-index="0"><span class="fs-checkbox"><i></i></span><div class="fs-option-label">C++</div></div><div class="fs-option selected" data-value="cs" data-index="1"><span class="fs-checkbox"><i></i></span><div class="fs-option-label">C#</div></div><div class="fs-option selected" data-value="oc" data-index="2"><span class="fs-checkbox"><i></i></span><div class="fs-option-label">Object C</div></div><div class="fs-option" data-value="c" data-index="3"><span class="fs-checkbox"><i></i></span><div class="fs-option-label">C</div></div></div><div class="fs-optgroup"><div class="fs-optgroup-label">Scripts</div><div class="fs-option" data-value="js" data-index="4"><span class="fs-checkbox"><i></i></span><div class="fs-option-label">JavaScript</div></div><div class="fs-option" data-value="php" data-index="5"><span class="fs-checkbox"><i></i></span><div class="fs-option-label">PHP</div></div><div class="fs-option" data-value="asp" data-index="6"><span class="fs-checkbox"><i></i></span><div class="fs-option-label">ASP</div></div><div class="fs-option" data-value="jsp" data-index="7"><span class="fs-checkbox"><i></i></span><div class="fs-option-label">JSP</div></div></div></div></div><select class="demo hidden" multiple="multiple">
    <optgroup label="Languages">
        <option value="cp">C++</option>
        <option value="cs">C#</option>
        <option value="oc">Object C</option>
        <option value="c">C</option>
    </optgroup>
    <optgroup label="Scripts">
        <option value="js">JavaScript</option>
        <option value="php">PHP</option>
        <option value="asp">ASP</option>
        <option value="jsp">JSP</option>
    </optgroup>
</select></div>
</div>
<script src="./jQuery fSelect Plugin Demo_files/jquery-1.11.3.min.js"></script>

<script>
(function($) {

$.fn.fSelect = function(options) {

    if (typeof options == 'string' ) {
        var settings = options;
    }
    else {
        var settings = $.extend({
            placeholder: 'Select some options',
            numDisplayed: 3,
            overflowText: '{n} selected',
            searchText: 'Search',
            showSearch: true
        }, options);
    }


    /**
     * Constructor
     */
    function fSelect(select, settings) {
        this.$select = $(select);
        this.settings = settings;
        this.create();
    }


    /**
     * Prototype class
     */
    fSelect.prototype = {
        create: function() {
            var multiple = this.$select.is('[multiple]') ? ' multiple' : '';
            this.$select.wrap('<div class="fs-wrap' + multiple + '"></div>');
            this.$select.before('<div class="fs-label-wrap"><div class="fs-label">' + this.settings.placeholder + '</div><span class="fs-arrow"></span></div>');
            this.$select.before('<div class="fs-dropdown hidden"><div class="fs-options"></div></div>');
            this.$select.addClass('hidden');
            this.$wrap = this.$select.closest('.fs-wrap');
            this.reload();
        },

        reload: function() {
            if (this.settings.showSearch) {
                var search = '<div class="fs-search"><input type="search" placeholder="' + this.settings.searchText + '" /></div>';
                this.$wrap.find('.fs-dropdown').prepend(search);
            }
            var choices = this.buildOptions(this.$select);
            this.$wrap.find('.fs-options').html(choices);
            this.reloadDropdownLabel();
        },

        destroy: function() {
            this.$wrap.find('.fs-label-wrap').remove();
            this.$wrap.find('.fs-dropdown').remove();
            this.$select.unwrap().removeClass('hidden');
        },

        buildOptions: function($element) {
            var $this = this;

            var choices = '';
            $element.children().each(function(i, el) {
                var $el = $(el);

                if ('optgroup' == $el.prop('nodeName').toLowerCase()) {
                    choices += '<div class="fs-optgroup">';
                    choices += '<div class="fs-optgroup-label">' + $el.prop('label') + '</div>';
                    choices += $this.buildOptions($el);
                    choices += '</div>';
                }
                else {
                    var selected = $el.is('[selected]') ? ' selected' : '';
                    choices += '<div class="fs-option' + selected + '" data-value="' + $el.prop('value') + '"><span class="fs-checkbox"><i></i></span><div class="fs-option-label">' + $el.html() + '</div></div>';
                }
            });

            return choices;
        },

        reloadDropdownLabel: function() {
            var settings = this.settings;
            var labelText = [];

            this.$wrap.find('.fs-option.selected').each(function(i, el) {
                labelText.push($(el).find('.fs-option-label').text());
            });

            if (labelText.length < 1) {
                labelText = settings.placeholder;
            }
            else if (labelText.length > settings.numDisplayed) {
                labelText = settings.overflowText.replace('{n}', labelText.length);
            }
            else {
                labelText = labelText.join(', ');
            }

            this.$wrap.find('.fs-label').html(labelText);
            this.$select.change();
        }
    }


    /**
     * Loop through each matching element
     */
    return this.each(function() {
        var data = $(this).data('fSelect');

        if (!data) {
            data = new fSelect(this, settings);
            $(this).data('fSelect', data);
        }

        if (typeof settings == 'string') {
            data[settings]();
        }
    });
}


/**
 * Events
 */
window.fSelect = {
    'active': null,
    'idx': -1
};

function setIndexes($wrap) {
    $wrap.find('.fs-option:not(.hidden)').each(function(i, el) {
        $(el).attr('data-index', i);
        $wrap.find('.fs-option').removeClass('hl');
    });
    $wrap.find('.fs-search input').focus();
    window.fSelect.idx = -1;
}

function setScroll($wrap) {
    var $container = $wrap.find('.fs-options');
    var $selected = $wrap.find('.fs-option.hl');

    var itemMin = $selected.offset().top + $container.scrollTop();
    var itemMax = itemMin + $selected.outerHeight();
    var containerMin = $container.offset().top + $container.scrollTop();
    var containerMax = containerMin + $container.outerHeight();

    if (itemMax > containerMax) { // scroll down
        var to = $container.scrollTop() + itemMax - containerMax;
        $container.scrollTop(to);
    }
    else if (itemMin < containerMin) { // scroll up
        var to = $container.scrollTop() - containerMin - itemMin;
        $container.scrollTop(to);
    }
}

$(document).on('click', '.fs-option', function() {
    var $wrap = $(this).closest('.fs-wrap');

    if ($wrap.hasClass('multiple')) {
        var selected = [];

        $(this).toggleClass('selected');
        $wrap.find('.fs-option.selected').each(function(i, el) {
            selected.push($(el).attr('data-value'));
        });
    }
    else {
        var selected = $(this).attr('data-value');
        $wrap.find('.fs-option').removeClass('selected');
        $(this).addClass('selected');
        $wrap.find('.fs-dropdown').hide();
    }

    $wrap.find('select').val(selected);
    $wrap.find('select').fSelect('reloadDropdownLabel');
});

$(document).on('keyup', '.fs-search input', function(e) {
    if (40 == e.which) {
        $(this).blur();
        return;
    }

    var $wrap = $(this).closest('.fs-wrap');
    var keywords = $(this).val();

    $wrap.find('.fs-option, .fs-optgroup-label').removeClass('hidden');

    if ('' != keywords) {
        $wrap.find('.fs-option').each(function() {
            var regex = new RegExp(keywords, 'gi');
            if (null === $(this).find('.fs-option-label').text().match(regex)) {
                $(this).addClass('hidden');
            }
        });

        $wrap.find('.fs-optgroup-label').each(function() {
            var num_visible = $(this).closest('.fs-optgroup').find('.fs-option:not(.hidden)').length;
            if (num_visible < 1) {
                $(this).addClass('hidden');
            }
        });
    }

    setIndexes($wrap);
});

$(document).on('click', function(e) {
    var $el = $(e.target);
    var $wrap = $el.closest('.fs-wrap');

    if (0 < $wrap.length) {
        if ($el.hasClass('fs-label')) {
            window.fSelect.active = $wrap;
            var is_hidden = $wrap.find('.fs-dropdown').hasClass('hidden');
            $('.fs-dropdown').addClass('hidden');

            if (is_hidden) {
                $wrap.find('.fs-dropdown').removeClass('hidden');
            }
            else {
                $wrap.find('.fs-dropdown').addClass('hidden');
            }

            setIndexes($wrap);
        }
    }
    else {
        $('.fs-dropdown').addClass('hidden');
        window.fSelect.active = null;
    }
});

$(document).on('keydown', function(e) {
    var $wrap = window.fSelect.active;

    if (null === $wrap) {
        return;
    }
    else if (38 == e.which) { // up
        e.preventDefault();

        $wrap.find('.fs-option').removeClass('hl');

        if (window.fSelect.idx > 0) {
            window.fSelect.idx--;
            $wrap.find('.fs-option[data-index=' + window.fSelect.idx + ']').addClass('hl');
            setScroll($wrap);
        }
        else {
            window.fSelect.idx = -1;
            $wrap.find('.fs-search input').focus();
        }
    }
    else if (40 == e.which) { // down
        e.preventDefault();

        var last_index = $wrap.find('.fs-option:last').attr('data-index');
        if (window.fSelect.idx < parseInt(last_index)) {
            window.fSelect.idx++;
            $wrap.find('.fs-option').removeClass('hl');
            $wrap.find('.fs-option[data-index=' + window.fSelect.idx + ']').addClass('hl');
            setScroll($wrap);
        }
    }
    else if (32 == e.which || 13 == e.which) { // space, enter
        $wrap.find('.fs-option.hl').click();
    }
    else if (27 == e.which) { // esc
        $('.fs-dropdown').addClass('hidden');
        window.fSelect.active = null;
    }
});

})(jQuery);


        $('.demo').fSelect();
    

    
</script>
</div>



</body>

</html>