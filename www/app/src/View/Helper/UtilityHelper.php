<?php

declare(strict_types=1);

namespace App\View\Helper;

use App\Model\Entity\AppEntity;
use App\Model\Table\AppTable;
use Cake\Chronos\Chronos;
use Cake\Chronos\Date;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Cake\Utility\Text;
use Cake\View\Helper;
use Cake\View\View;

/**
 * Utility helper
 */
class UtilityHelper extends Helper
{

    /**
     * List of helpers used by this helper
     *
     * @var array
     */
    protected $helpers = ['Html', 'Form'];

    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];


    /**
     *  getEmbedMap function
     *
     * @return striing
     */
    public function getEmbedMapSrc($latitude = null, $longitude = null)
    {
        if (!$latitude || !$longitude) {
            return "";
        }
        if (!Configure::check('Site.Settings.GoogleMapApiKey')) {
            return "";
        }

        $src = "https://www.google.com/maps/embed/v1/place?key=" . Configure::read('Site.Settings.GoogleMapApiKey') . "&q=" . rawurlencode($latitude . ',' . $longitude);
        return $src;
    }

    /**
     *  getEmbedMap function
     *
     * @return striing
     */
    public function getRouteMapLink($latitude = null, $longitude = null)
    {
        if (!$latitude || !$longitude) {
            return "";
        }

        $link = "https://www.google.com/maps/dir/?api=1&destination=" . rawurlencode($latitude . ',' . $longitude);
        return $link;
    }


    public function noImage()
    {
        return $this->Html->image(Configure::read('CustomSettings.General.noimage'), ['alt' => '']);
    }

    /**
     *  getRecaptchaForm function
     *
     * @return striing
     */
    public function getRecaptchaSrc()
    {
        if (!Configure::check('Site.Settings.reCAPTCHA.siteKey')) {
            return "";
        }
        $siteKey = Configure::read('Site.Settings.reCAPTCHA.siteKey');

        $this->Html->script("https://www.google.com/recaptcha/api.js?render={$siteKey}", ['block' => true]);
    }
    /**
     *  getRecaptchaForm function
     *
     * @return striing
     */
    public function getRecaptchaFormAndScript($formId = null)
    {
        if (!Configure::check('Site.Settings.reCAPTCHA.siteKey')) {
            return "";
        }
        if (!$formId) {
            return "";
        }
        $siteKey = Configure::read('Site.Settings.reCAPTCHA.siteKey');

        $uuId = Text::uuid();
        $token = str_replace('-', '_', $uuId);

        echo $this->Form->hidden('g-recaptcha-response', ['name' => 'g-recaptcha-response', 'id' => $uuId]);

        $js = <<<JS
    window.addEventListener('DOMContentLoaded', function(e){
        e.preventDefault();
        const form = document.getElementById('{$formId}');
        // let token_{$token} = false;
        form.addEventListener('submit', function(e){
            e.preventDefault();
            // if(!token_{$token}){
                grecaptcha.ready(function() {
                    grecaptcha.execute('{$siteKey}', {action: 'submit'}).then(function(token) {
                        document.getElementById('{$uuId}').value = token;
                        // token_{$token} = true;
                        const form = document.getElementById('{$formId}');
                        form.submit();
                    });
                });
            // }
            return false;
        })
    });
JS;
        $this->Html->scriptBlock($js, ['block' => true]);
    }

    /**
     *  getDayOfWeek function
     *
     * @return striing
     */
    public function getDayOfWeek($index)
    {
        return AppEntity::dayOfWeekJp[$index] ?? "";
    }

    public function fileUpload($maxFileSize = 2097152, $accept = "image/jpeg", $model = "Contacts")
    {

        $url = Router::url(['action' => 'upload']);

        $prefix = Router::url(['action' => 'fileShow']);

        $this->Html->script('/js/load-image.all.min.js', ['block' => true]);

        $js = <<<JS
    $(document).ready(function() {
        $(".file-upload .file-upload-delete").each(function() {
            if($(this).closest(".file-upload").find(".file-upload-thumbnail").html().trim() == "" ) {
                $(this).hide();
            }
        })
        $(".file-upload .file-upload-delete").on("click", function() {
            $(this).closest(".file-upload").find(".file-upload-thumbnail").html("");
            $(this).closest(".file-upload").find(".file-upload-hidden").val("");
            $(this).closest(".file-upload").find(".file-upload-name").val("");
            $(this).closest(".file-upload").find(".file-upload-size").val("");
            $(this).closest(".file-upload").find(".file-upload-type").val("");
            $(this).hide();
        })
    });
    $(document).on("change", ".file-upload input[type=file]", function(){

            var elms = {};
            elms.box       = $(this).closest(".file-upload");
            elms.input     = $(elms.box).find("input[type=file]");
            elms.thumbnail = $(elms.box).find(".file-upload-thumbnail");
            elms.hidden    = $(elms.box).find(".file-upload-hidden");
            elms.name      = $(elms.box).find(".file-upload-name");
            elms.size      = $(elms.box).find(".file-upload-size");
            elms.width     = $(elms.box).find(".file-upload-width");
            elms.height    = $(elms.box).find(".file-upload-height");
            elms.type      = $(elms.box).find(".file-upload-type");
            elms.delbtn    = $(elms.box).find(".file-upload-delete");
            elms.messages  = $(elms.box).find(".file-upload-messages");
            elms.token     = $(elms.input).closest("form").find("input[name=_csrfToken]").val();
            elms.progress  = $(elms.box).find(".file-upload-progress");

            elms.setValue  = function(result) {
                elms.clearValue();

                $(this.hidden).val(result.id);

                var t_wid = $(elms.thumbnail).attr("thumb-width")? $(elms.thumbnail).attr("thumb-width"): 200;
                var img = $("<img>").attr("src", "/files/"+result.path).attr("width", t_wid);
                // console.log(t_wid);
                $(elms.thumbnail).html(img);
                $(elms.delbtn).show();
            };

            elms.clearValue = function() {
                $(this.input).val("");
                $(this.name).val("");
                $(this.hidden).val("");
                $(this.delbtn).hide();
            }

            elms.checkMaxFileSize =function(upfile) {
                var maxSize = '{$maxFileSize}';
                if(maxSize < 1 || upfile.size < maxSize) {
                    return(true);
                }

                var size = function(size) {
                    var units = [" B", " KB", " MB", " GB", " TB"];
                    for (var i = 0; size > 1024; i++) {
                        size /= 1024;
                    }
                    return Math.round(size) + units[i];
                }(maxSize);

                var msg = $(elms.messages).attr("size-over")? $(elms.messages).attr("size-over"): "ファイルサイズが大きすぎます。ファイルは" + size + "バイト以内に設定してください";
                alert(msg);
                return(false);
            }

            elms.checkMimeType =function(upfile) {
                var accepts = '{$accept}';

                if (upfile.type &&  accepts.indexOf(upfile.type) != -1) {
                    return(true);
                }
                var msg = $(elms.messages).attr("invlid-file-type")? $(elms.messages).attr("invlid-file-type"): "許可されていない拡張子のファイルがアップロードされました";

                alert(msg);
                return(false);
            }

            elms.upload = function(upfile, data) {
                var fd = new FormData();
                fd.append("file", upfile);
                fd.append("model", "{$model}");
                // console.log(upfile);
                // fd.append("name", upfile.name);
                fd.append("_csrfToken", elms.token);

                var url = '{$url}';
                var postData = {
                    type : "POST",
                    dataType : "json",
                    data : fd,
                    processData : false,
                    contentType : false
                };
                // console.log(url);
                // console.log(postData);

                $.ajax(
                    url, postData
                )
                .done(function( result ){
                    elms.setValue(result);
                });
            }

            if (this.files.length > 0) {
                // 選択されたファイル情報を取得
                var upfile = this.files[0];

                if(! elms.checkMaxFileSize(upfile)) {
                    elms.clearValue();
                    return;
                }

                if(! elms.checkMimeType(upfile)) {
                    elms.clearValue();
                    return;
                }

                var width = $(elms.input).attr("resize-width")? $(elms.input).attr("resize-width"): 1024;
                var height = $(elms.input).attr("resize-height")? $(elms.input).attr("resize-height"): 1024;
                var quarity =  $(elms.input).attr("resize-quarity")? $(elms.input).attr("resize-quarity"): 0.8;

                loadImage(upfile, function (data) {
                    $(elms.width).val(data.width);
                    $(elms.height).val(data.height);
                    elms.upload(upfile, data.toDataURL(upfile.type, quarity));
                }, {maxWidth: width, maxHeight: height, canvas: true, meta: true, orientation: true} );

            }

        });
JS;
        $this->Html->scriptBlock($js, ['block' => true]);
    }

    /* イベントリストから日付のjsonを作成するメソッド→そのままカレンダー表示まで進む
     *
     */
    public function getCalendarList($datas, $class, $model = null, $column = null)
    {
        if (empty($datas)) return;
        $model = $model ?: 'event_dates';
        $column = $column ?: 'date';

        $eventDates = [];
        foreach ($datas as $data) {
            foreach ($data->$model as $eventDate) {
                $eventDates[] = $eventDate->$column->format('Y-m-d');
            }
        }
        // sort($eventDates);
        $eventDateList = json_encode($eventDates);

        $this->getCalendar($eventDateList, $class, $column);
    }

    /* 日付リストを持ってきてカレンダーを表示するメソッド
     *
     */
    public function getCalendar($eventDateList, $class, $column = null)
    {
        if (empty($eventDateList)) return;
        $column = $column ?: 'date';

        $this->Html->css([
            '/js/flatpickr/dist/flatpickr.min.css',
            '/js/flatpickr/dist/themes/material_green.css'
        ], ['block' => true]);

        $this->Html->script([
            '/js/flatpickr/dist/flatpickr.min.js',
            '/js/flatpickr/dist/l10n/ja.js',
        ], ['block' => 'scriptBody']);

$js = <<<JS
document.addEventListener("DOMContentLoaded", function() {
    var eventDates = $eventDateList;
    flatpickr("#{$class}", {
        inline: true,
        locale: "ja",

        // イベントのある日付にCSS用のclassを付ける
        onDayCreate: function(dObj, dStr, fp, dayElem) {
            // var date = dayElem.dateObj.toISOString().split('T')[0];
            // ※ ホントは↑で良いはずだけど、タイムゾーンが合わず１日ズレるので↓でセット → 後で研究
            var date = new Date(dayElem.dateObj.getTime() - (dayElem.dateObj.getTimezoneOffset() * 60000)).toISOString().split('T')[0];
            if (eventDates.includes(date)) {
                dayElem.classList.add('event-day');
            }
        },

        // 日付クリック時にAjaxで当該日のイベントを取得する
        onChange: function(selectedDates, dateStr, instance) {
            // var selectedDate = selectedDates[0].toISOString().split('T')[0];
            var selectedDate = new Date(selectedDates[0].getTime() - (selectedDates[0].getTimezoneOffset() * 60000)).toISOString().split('T')[0];
            fetchEventsByDate(selectedDate);
        }
    });
});

// 当該日のイベントを取得する
function fetchEventsByDate(date) {

    $.ajax({
        type : "GET",
        url : "/events/day-event/" + date,
        dataType : "html",
    }).done(function(html) {
        // サーバから送られたElement(html)を一旦全消しして再表示
        // console.log("成功");
        $("#event-list").empty();
        $("#event-list").append(html);
    }).fail(function(xhr, status, error) {
        // console.log("失敗");
        console.log(xhr.responseText);
    });
}
JS;
$this->Html->scriptBlock($js, ['block' => 'scriptBody']);
    }
}
