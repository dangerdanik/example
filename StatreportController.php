<?php

/**
 * Description of StatreportController
 
 контроллер взаимодействующий с видом camp
 *
 * @author dan
 */

namespace app\controllers\reports;

use app\controllers\AppController;
use app\models\reports\Statreport;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use DateTime;
use Yii;
use yii\data\SqlDataProvider;
use \yii\db\Exception;

class StatreportController extends AppController {

    public function actionCamp() {

        // \Yii::beginProfile('myBenchmark');

        $model = new Statreport;

        //$sqlQuery = new \app\components\forreports\StatReportQuery;

        $session = Yii::$app->session;

        $post = NULL;

        // если есть POST заполняем
        if (Yii::$app->request->post()) {

            $post = Yii::$app->request->post();
        }

        $camp_id = NULL;

        $jsonDecodeFilter = NULL;

        //получаем Json и делаем из него массив
        if (isset(Yii::$app->request->post()['filter'])) {

            $jsonDecodeFilter = json_decode(Yii::$app->request->post()['filter'], true);
        }

        if (isset(Yii::$app->request->get()['id'])) {

            $camp_id = Yii::$app->request->get()['id'];

            $session['camp_id'] = $camp_id;
        }

        if (!isset($session['select1'])) {

            $session['select1'] = "str.stream_name";
        }

       // $pole_1 = $session['select1'];

        if (!isset($session['selectDate'])) {

            $session['selectDate'] = "today";
        }

        $data = NULL;

        $data2 = NULL;

        $data3 = NULL;

//        $pole_2 = NULL;
//
//        $pole_3 = NULL;

        $title = $session['select1'];

        $date = new DateTime();

        $date_start = $date->format('Y-m-d');

        $date_end = $date->format('Y-m-d');

        if (!isset($session['date_start'])) {

            // $date_start = $date->format('Y-m-d');

            $session['date_start'] = $date_start;
        }

        if (!isset($session['date_end'])) {

            // $date_end = $date->format('Y-m-d');

            $session['date_end'] = $date_end;
        }

        // Получаем данные из модели
        $categorySelect1 = $model->getCatForSelect1();

        $categorySelect2 = $model->getCatForSelect2();

        $categorySelect3 = $model->getCatForSelect3();

        //  $clicks = Statreport::getStreamClick($camp_id);
//        if (\Yii::$app->request->isAjax) {
//            
//            
//            
//        }
        if (isset(Yii::$app->request->post()['selectDate'])) {

            $date = Yii::$app->request->post()['selectDate'];

            $session['selectDate'] = $date;

//            if ($session['selectDate'] == 'between_date') {
//                
//                debug("Тест!");
//            }
        }


        if (isset(Yii::$app->request->post()['date_start'])) {

            $session['date_start'] = Yii::$app->request->post()['date_start'];
        }

        if (isset(Yii::$app->request->post()['date_end'])) {

            $session['date_end'] = Yii::$app->request->post()['date_end'];
        }

        if (isset(Yii::$app->request->post()['select1'])) {

          //  $pole_1 = Yii::$app->request->post()['select1'];

            $select1 = Yii::$app->request->post()['select1'];

            $session['select1'] = $select1;

            $title = $session['select1'];

            $data = $select1;
        }

        if (isset(Yii::$app->request->post()['select2'])) {

         //   $pole_2 = Yii::$app->request->post()['select2'];

            $select2 = Yii::$app->request->post()['select2'];

            $session['select2'] = $select2;

            $data2 = $select2;
        }

        if (isset(Yii::$app->request->post()['select3'])) {

         //   $pole_3 = Yii::$app->request->post()['select3'];

            $select3 = Yii::$app->request->post()['select3'];

            $session['select3'] = $select3;

            $data3 = $select3;
        }

        // получаем sql запрос с нужными полями
        $sql = \app\components\forreports\StatReportQuery::setQuery($session, $post, $session['camp_id'], $jsonDecodeFilter);

        try {

            $query = $model->findbysql($sql)->asArray()->all();
            
        } catch (Exception $ex) {

           // echo('Запись не найдена');
            
            throw new \yii\web\NotFoundHttpException("Проблема с выборкой данных");
            
        }

        /*просто берем название 3 первый полей
         в селектах */
        
        $fields = NULL;

        if (isset($query[0])) {

            foreach ($query[0] as $key => $val) {

                $fields[] = $key;

                // debug($key);
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            //'count' => 1,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 10
            ]
        ]);

        // указываем поля для сортировки
        $dataProvider->setSort([
            'attributes' => [
                'campaign_id',
                'Кампания',
                'Поток',
                'Группа',
                'Клики',
                'Уник(К)',
                'Постбэки',
                'Браузер',
                'Ос',
                'CR',
                'Расход',
                'Источник',
                'Тип_устройства',
                'Бренд_устройства',
                'Модель_устройства',
                'Оператор',
                'Страна',
                'Город',
                'ЮзерАгент',
                'Уник(К)камп',
                'Реферер',
                'Ключевик',
                'x_requested_with',
                'ROI',
                'Выручка',
                'Прибыль',
                'Бот',
                'sub_id_1',
                'sub_id_2',
                'sub_id_3',
                'sub_id_4',
                'sub_id_5',
                'sub_id_6',
                'sub_id_7',
                'sub_id_8',
                'sub_id_9',
                'sub_id_10',
                'sub_id_11',
                'sub_id_12',
                'sub_id_13',
                'sub_id_14',
                'sub_id_15',
                'sub_id_16',
                'sub_id_17',
                'sub_id_18',
                'sub_id_19',
                'sub_id_20',
            ]
        ]);

        return $this->render('camp', [
                    'title' => $title,
                    //  'clicks' => $clicks,
                    'categorySelect1' => $categorySelect1,
                    'categorySelect2' => $categorySelect2,
                    'categorySelect3' => $categorySelect3,
                    'data' => $data,
                    'data2' => $data2,
                    'session' => $session,
                    'dataProvider' => $dataProvider,
                    'fields' => $fields,
//                    'column2' => $column2,
//                    'column3' => $column3,
        ]);

        //  \Yii::endProfile('myBenchmark');
    }

}
