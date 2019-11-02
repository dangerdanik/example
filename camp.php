<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\select2\Select2;
use yii\jui\DatePicker;
use yii\widgets\Pjax;
//use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
//use yii\bootstrap\ButtonDropdown;

$id = $session['camp_id'];

if (isset(Yii::$app->request->get()['id'])) {

    $id = Yii::$app->request->get()['id'];
}

$campaign = \app\models\Campaign::getCampName($id);

$this->title = "Отчет по кампании: " . $campaign['name'];

$this->params['breadcrumbs'][] = $this->title;

$controller_id = Yii::$app->controller->id;

//debug($controller_id);
?>

<!-- анимация загрузки -->
<div class="ajax_load"></div>

<div class="statreport-index">

    <?php
    // выбор всех дат не показываем
    $select_all_dates_div = "display: block;";

   //интервал между датами
    $select_between_dates_div = "display: none;";

    //url на контроллер
    $url = "/reports/statreport/camp/$id";

    // при выборе значений из select или других элементов с данными из формы обновление элементов обернутых pjax
    $onchangeSelect1 = "$.pjax.reload({container: '#statreport_page', timeout: false, type: 'post', url: '" . \yii\helpers\Url::to(["$url"]) . "', data: {select1: $(this).val(), value: $('#categorySelectMain :selected').html()}});";

    $onchangeSelect2 = "$.pjax.reload({container: '#statreport_page', timeout: false, type: 'post', url: '" . \yii\helpers\Url::to(["$url"]) . "', data: {select2: $(this).val()}});";

    $onchangeSelect3 = "$.pjax.reload({container: '#statreport_page', timeout: false, type: 'post', url: '" . \yii\helpers\Url::to(["$url"]) . "', data: {select3: $(this).val()}});";

    $onchangeDataSelect = "$.pjax.reload({container: '#statreport_page', timeout: false, type: 'post', url: '" . \yii\helpers\Url::to(["$url"]) . "', data: {selectDate: $(this).val()}});";

    $onchangeDateStart = "$.pjax.reload({container: '#statreport_page', timeout: false, type: 'post', url: '" . \yii\helpers\Url::to(["$url"]) . "', data: {date_start: $(this).val()}});";

    $onchangeDateEnd = "$.pjax.reload({container: '#statreport_page', timeout: false, type: 'post', url: '" . \yii\helpers\Url::to(["$url"]) . "', data: {date_end: $(this).val()}});";
    ?>

    <div style='float:right; padding-left: 20px;'>

            <?php
            //модальное окно с фильтрами
            Modal::begin([
                'header' => 'Фильтры',
                'id' => 'myfiltr',
                'options' => ['pjax' => 0,],
                'toggleButton' => ['label' => 'Фильтры', 'class' => 'btn btn-default',],
                'footer' =>
                "<a id='otmena_filtr' class='btn btn-default'>Отмена</a>

                 <input type='hidden' id='controller_id' value='$controller_id'>

                 <a id='sbros_filtr' class='btn btn-default' data-dismiss='modal'>Сбросить</a>
                 <button id='apply_filtr' type='submit' data-dismiss='modal' class='btn btn-success'>Применить</button>",
            ]);
            ?>

            <!--  filter block -->
            <div class="filter-list-block">

                <form action="/reports/parametrs" method="post" id="filter_form">
                    <div class="filter-items">

                    </div>
                </form>

                <button class="add-filter-btn">+ Добавить</button>

            </div>
            <!-- /end filter block -->

            <?php
            Modal::end();
// echo "</div>";
            ?>

        </div>
    
    
    <?php Pjax::begin(['id' => 'statreport_page']); ?>

<!-- <script src="/js/statreport.js"> -->

    <h1> <a href="#" onclick ="history.back();" class="btn btn-default btn-square margin-right-20"><i class="fa fa-chevron-circle-left"></i> </a>

        <?= "Отчет по кампании" ?>: <?= $campaign['name'] ?>  </h1>

    <?php
//    $tableNames = Yii::$app->db->getSchema()->tableNames;
//
//        foreach ($tableNames as $tableName) {
//
//            if (strpos($tableName, 'stat_data') !== false) {
//
//                //$this->dropTable($tableName);
//                
//                debug($tableName);
//            }
//
//            if (strpos($tableName, 'traffic_queue') !== false) {
//
//                //$this->dropTable($tableName);
//                
//                debug($tableName);
//            }
//        }
//    if (isset($session)) {
////        debug('select1' . '=' . $session['select1']
////                . ' ' . 'select2' . '=' . $session['select2']
////                . ' ' . 'select3' . '=' . $session['select3']
////                . ' ' . 'selectDate' . '=' . $session['selectDate']
////                . ' ' . 'date_start' . '=' . $session['date_start']
////                . ' ' . 'date_end' . '=' . $session['date_end']);
//
//    debug($_SESSION); //die;
//    }

    // берем значения из сессий
    $select1_val = [$session['select1'] => $session['select1']];

    //debug($select1_val);

    $select2_val = [$session['select2'] => $session['select2']];

    $select3_val = [$session['select3'] => $session['select3']];

    $selectDate_val = [$session['selectDate'] => $session['selectDate']];

    // значения из контроллера
    $select_1_data = $categorySelect1;

    $select_2_data = $categorySelect2;

    $select_3_data = $categorySelect3;

    $select_date_data = ['today' => 'Сегодня',
                        'yesterday' => 'Вчера',
                        '7days' => 'Последние 7 дней',
                        'between_date' => 'Интервал дат'];

    // проверяем выбранное значение для изменения дат
    if ($session['selectDate'] == "between_date") {

        $select_all_dates_div = "display: none;";

        $select_between_dates_div = "display: block;";
    }

    // если оба значения равны удаляем второе
    if ($session['select1'] == $session['select2']) {

        unset($_SESSION['select2']);
    }

    /* если в первом селект есть данные, во втором селект пусто и в 3 данные так же есть, делаем смещение данных
     переносим данные с третьего селект во второй */
    
    if ($session['select1'] == TRUE && $session['select2'] == FALSE && $session['select3'] == TRUE) {

        $session['select2'] = $session['select3'];

        unset($_SESSION['select3']);

        $select2_val = $select3_val;

        // принудительно вызываем pjax reload
        $script = "<script> $.pjax.reload({container: '#statreport_page'}) </script>";

        //$this->registerJsFile($script);

        print $script;
    }

    
    // избавляемся от повторяющих данных в селект2 и селект 3
    unset($select_2_data[$session['select1']]);

    unset($select_2_data[$session['select3']]);

    unset($select_3_data[$session['select1']]);

    unset($select_3_data[$session['select2']]);

    // Pjax::end();
    ?>



    <?php
    echo Select2::widget([
        'name' => 'categorySelectMain',
        'id' => 'categorySelectMain',
        'theme' => 'default',
        'language' => 'ru',
        'data' => $select_1_data,
        'value' => $select1_val,
        'options' => ['onchange' => $onchangeSelect1],
        'pluginOptions' => [
            //'allowClear' => true,
            'width' => '150px',
        ],
    ]);
    ?>

    <span class="select2-selection__arrow"><b role="presentation"></b></span>

    <?php
    echo Select2::widget([
        'name' => 'categorySelect2',
        'id' => 'categorySelect2',
        'theme' => 'default',
        'language' => 'ru',
        'data' => $select_2_data,
        'value' => $select2_val,
        'options' => ['placeholder' => 'Группировать по', 'onchange' => $onchangeSelect2],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '150px',
        ],
    ]);
    ?>    

    <span class="glyphicon glyphicon-chevron-right"></span>

    <?php
    echo Select2::widget([
        'name' => 'categorySelect3',
        'id' => 'categorySelect3',
        'theme' => 'default',
        'language' => 'ru',
        'data' => $select_3_data,
        'value' => $select3_val,
        'options' => ['placeholder' => 'Группировать по', 'onchange' => $onchangeSelect3],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '150px',
        ],
    ]);
    ?>

    <div style="float: right">

        <div id="select_all_dates"  style="float: right; <?= $select_all_dates_div ?>">
            <?php
            echo Select2::widget([
                'name' => 'dateSelect',
                'id' => 'dateSelect',
                'theme' => 'default',
                'language' => 'ru',
                'hideSearch' => true,
                'data' => $select_date_data,
                'value' => $selectDate_val,
                'options' => ['placeholder' => 'Дата', 'onchange' => $onchangeDataSelect,],
                'pluginOptions' => [
                    'allowClear' => false,
                    'width' => '200px',
                //'style' => 'display: none'
                ],
            ]);

//        echo ButtonDropdown::widget([
//            'label' => 'Сегодня',
//            'options' => [
//                'class' => 'btn btn-default',
//                'style' => 'margin-left:100px',
//                'id' => 'date_button',
//            ],
//            'dropdown' => [
////                'options' => ['id' => 'date',
////                    'data' => 'date_to_date',
////                ],
//                'items' => [
////                    [
////                        'label' => 'Сегодня',
////                        'url' => '#',
////                    ],
//                    [
//                        'options' => ['id' => 'date_today',
//                            'date_today' => 'date_today',
//                        ],
//                        'label' => 'Сегодня',
//                        'url' => '#'
//                    ],
//                    
//                    [
//                        'options' => ['id' => 'date_yesterday',
//                            'date_yesterday' => 'date_yesterday',
//                        ],
//                        'label' => 'Вчера',
//                        'url' => '#'
//                    ],
//                ]
//            ]
//        ]);
            ?>

        </div>

        <div id="select_between_dates" style="float: right; <?= $select_between_dates_div ?>">

            <table>

                <td width="40">

                    <button id="del_select_date_btn" type="button" title="Закрыть" class="btn btn-danger btn-sm margin-left-10">

                        <span class="fa fa-times"></span> 

                    </button> 

                </td>

                <td width="100">

                    <?php
                    
                    $dateStartValue = time();

                    if (isset($_SESSION['date_start'])) {

                        $dateStartValue = $_SESSION['date_start'];
                    }

                    echo DatePicker::widget([
                        'name' => 'date_start',
                        'value' => $dateStartValue,
                        'options' => [
                            'id' => 'date_start',
                            'class' => 'form-control datepicker',
                            'style' => 'width:150px;',
                            //   'disabled' => $select_disable,
                            'onchange' => $onchangeDateStart
                        ],
                        'language' => 'ru',
                        'dateFormat' => 'dd.MM.yyyy',
                        'clientOptions' => [
                            // 'source' => ['RUS'],
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                            'firstDay' => '1',
                        ],
                    ]);
                    ?>

                </td>

                <td>

                    -

                </td>

                <td width="100">

                    <?php
                    
                  
                    
                    $dateEndValue = time();

                    if (isset($_SESSION['date_end'])) {

                        $dateEndValue = $_SESSION['date_end'];
                    }

                    echo DatePicker::widget([
                        'name' => 'date_end',
                        'language' => 'ru',
                        'value' => $dateEndValue,
                        'options' => [
                            'id' => 'date_end',
                            'class' => 'form-control',
                            'style' => 'width:150px;',
                            'onchange' => $onchangeDateEnd
                        ],
                        'dateFormat' => 'dd.MM.yyyy',
                        'clientOptions' => [
                            //'source' => ['RUS'],
                            'changeMonth' => 'true',
                            'changeYear' => 'true',
                            'firstDay' => '1',
                        ],
                    ]);
                    ?>

                </td>

            </table>

        </div>

        <?php
//        
//        $show_reset_filter_button = "display: none";
//
//        if (isset($_COOKIE['filter'])) {
//            
////            $script = "<script> $.pjax.reload({container: '#statreport_page'}) </script>";
////
////            print $script;
//            
//            $show_reset_filter_button = "display: block";
//        }
        ?>

        <div id="div_reset_filter_button" style='float:left; padding-right: 3px;'>
            <?php
// удаление выбранных потоков
//            Html::Button('Сброс', ['class' => 'btn btn-danger', 'id' => 'filter_reset',
//                'name' => 'filter_reset',
//                'title' => 'Сбросить фильтры'
//                    //'data' => ['confirm' => 'Are you sure?'],
//                    // 'onclick' => "delStream();",
//            ]);
//            
            ?>
        </div>

    </div>

    <input type="hidden" id="categoryHiddentMain" value="<?= $id ?>">

    <br>

    <br>

    <?php
    /* показываем сам грид
     с первыми тремя динамическими полями */
    
    $col0_visiable = TRUE;

    $col1_visiable = get_statistik_field1($fields);

    $col2_visiable = get_statistik_field2($fields);

  //  debug($fields[0]);
    
    $columns = [
        ['attribute' => $fields[0],
            'visible' => $col0_visiable,
         //   'group' => true,
        ],
        ['attribute' => $fields[1],
            'visible' => $col1_visiable,
          // 'group' => true,  // enable grouping
 
        ],
        ['attribute' => $fields[2],
            'visible' => $col2_visiable,
          // 'group' => true,  // enable grouping
          
        ],
        [
            'attribute' => 'Клики',
            'visible' => true,
            'pageSummary' => true
        ],
        [
            'attribute' => 'Уник(К)',
            'visible' => true,
            'pageSummary' => true
        ],
        [
            'attribute' => 'Постбэки',
            'visible' => true,
            'pageSummary' => true
        ],
        [
            'attribute' => 'CR',
            'visible' => true,
            'pageSummary' => true
        ],
        [
            'attribute' => 'ROI',
            'visible' => true,
            'pageSummary' => true
        ],
        [
            'attribute' => 'Расход',
            'visible' => true,
            'pageSummary' => true,
//            'format' => ['decimal', 1],
//            'pageSummary' => true
        ],
        [
            'attribute' => 'Выручка',
            'visible' => true,
            'pageSummary' => true
        ],
        [
            'attribute' => 'Прибыль',
            'visible' => true,
            'pageSummary' => true
        ],
    ];
    ?>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        //   'showPageSummary' => true,
        'pjax' => true,
        'striped' => true,
        'hover' => true,
        'panel' => ['type' => 'primary',],
        'responsive' => true,
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        'columns' => $columns,
        'showPageSummary' => true
//        [
//            'class' => 'kartik\grid\FormulaColumn',
//            'header' => 'Amount In Stock',
//            'mergeHeader' => true,
//         //   'value' => NULL,
//            'width' => '150px',
//            'hAlign' => 'right',
//            'format' => ['decimal', 2],
//            'pageSummary' => true
//        ],
    ]);
    ?>
</div>

<?php //Pjax::begin(['id' => 'some_pjax_id', 'timeout' => 5000]);                ?>

<?php Pjax::end(); ?>