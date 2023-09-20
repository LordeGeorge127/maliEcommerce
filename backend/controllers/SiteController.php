<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\Order;
use common\models\OrderItem;
use common\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $totalEarnings = Order::find()->paid()->sum('total_price');
        $totalOrders = Order::find()->paid()->count();
//        var_dump($totalOrders);exit;
        $totalProducts = OrderItem::find()
            ->alias('oi')
            ->innerJoin(Order::tableName() . 'o', 'o.id = oi.order_id')
            ->andWhere(['o.status' => Order::STATUS_COMPLETED])
            ->sum('quantity');
//        $Tproducts = OrderItem::findBySql("SELECT SUM(oi.quantity) from order_item oi INNER JOIN [order] o on oi.order_id = o.id where  o.status = :status",['status'=> Order::STATUS_COMPLETED])->scalar();
        $totalUsers = User::find()->andWhere(['status' => 10])->count();
        $orders = Order::findBySql("SELECT
            CAST(DATEADD(SECOND, o.created_at, '19700101') AS DATE) AS [date],
            SUM(o.total_price) AS [total_price]
            FROM [Lambistic].[dbo].[order] o
            WHERE o.status = :status
            GROUP BY CAST(DATEADD(SECOND, o.created_at, '19700101') AS DATE)", ['status' => Order::STATUS_COMPLETED])
            ->asArray()
            ->all();
        $earningsData = [];
        $labels = [];//days
        if (!empty($orders)) {
            $minDate = $orders[0]['date'];//date of the earliest order
            $orderByPriceMap = ArrayHelper::map($orders, 'date', 'total_price');
            $d = new \DateTime($minDate);//date of the earliest order
            $nowDate = new \DateTime();//current date today
//            VarDumper::dump([$minDate,$d],30,true);exit;
//            VarDumper::dump([$d,$orderByPriceMap,$minDate],30,true);

            //parse date,convert to object from that dTE TO NOW create  a list of dates
            $dates = [];
            while ($d->getTimestamp() < $nowDate->getTimestamp()) {
                $label = $d->format('Y-m-d');
                $labels[] = $label;
                $earningsData[] = (float)($orderByPriceMap[$label] ?? 0);
                $d->setTimestamp($d->getTimestamp() + 86400);
            }
//            VarDumper::dump([$label,$labels,$earningsData],30,true);exit;
        }
        $countriesData = Order::findBySql("SELECT SUM(o.total_price) total_price , oa.country
        from [order] o
        LEFT JOIN order_address oa on o.id = oa.order_id
        WHERE status = :status
        group by country", ['status' => Order::STATUS_COMPLETED])
            ->asArray()
            ->all();

        $colorOptions = ['#4e73df','#1cc8a','#36b9cc'];
        $countryLabels = ArrayHelper::getColumn($countriesData, 'country');
//        VarDumper::dump([$countriesData,$countryLabels],20,true);exit;
        $bgColors = [];
        foreach ($countryLabels as $i => $country) {
            $bgColors[] = $colorOptions[$i] ?? '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);;

        }
//        VarDumper::dump($bgColors,10,true);exit;
        return $this->render('index', [
            'totalEarnings' => $totalEarnings,
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'labels' => $labels,
            'data' => $earningsData,
            'countries' => $countryLabels,
            'bgColors' => $bgColors,
            'countriesData' => ArrayHelper::getColumn($countriesData, 'total_price'),
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    

}
