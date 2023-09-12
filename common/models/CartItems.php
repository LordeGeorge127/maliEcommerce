<?php

namespace common\models;

use Yii;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%cart_items}}".
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $quantity
 * @property int $user_id
 *
 * @property Product $product
 * @property User $user
 */
class CartItems extends \yii\db\ActiveRecord
{
    const SESSION_KEY = 'Cart-Items';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%cart_items}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'quantity', 'user_id'], 'integer'],
            [['user_id'], 'required'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'quantity' => 'Quantity',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\ProductQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\CartItemsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CartItemsQuery(get_called_class());
    }

    public static function getItemsForUser($currUserId)
    {
        if (Yii::$app->user->isGuest) {
            //retrieve cart items from seesion
            $cartItems = Yii::$app->session->get(CartItems::SESSION_KEY, []);
        } else {
            $cartItems = CartItems::findBySql("
            SELECT c.product_id as id,c.quantity,
            p.name,p.image, p.price,
            p.price * c.quantity as total_price
            from cart_items c 
                LEFT JOIN product p on p.id = c.product_id 
            where user_id= :userId",
                ['userId' => $currUserId]
            )->asArray()
                ->all();
        }
        return $cartItems;
    }

    public static function getTotalQuantityForUser($currUserId)
    {
        if (\Yii::$app->user->isGuest) {
            $cartItems = \Yii::$app->session->get(CartItems::SESSION_KEY, []);
            $sum = 0;
            foreach ($cartItems as $cartItem) {
                $sum += $cartItem['quantity'];
            }
        } else {
            $sum = CartItems::findBySql("
        SELECT SUM(quantity) FROM cart_items WHERE user_id=:userId
        ", ['userId' => $currUserId])->scalar();
        }
        return $sum;
    }

    public static function getTotalPriceForItemForUser($productId, $currUserId)
    {
        if (\Yii::$app->user->isGuest) {
            $cartItems = \Yii::$app->session->get(CartItems::SESSION_KEY, []);
            $sum = 0;
            foreach ($cartItems as $cartItem) {
                if ($cartItem['id'] == $productId) {

                    $sum += $cartItem['quantity'] * $cartItem['price'];
                }
            }
//            VarDumper::dump($cartItems, 10, true);

        } else {
            $sum = CartItems::findBySql("
        SELECT SUM(c.quantity * p.price ) 
        FROM cart_items c 
            LEFT JOIN product p on c.product_id = p.id  WHERE product_id=:productId AND user_id=:userId
        ", ['productId' => $productId, 'userId' => $currUserId])->scalar();
        }
        return $sum;
    }

    public static function getTotalPriceForUser($currUserId)
    {
        if (\Yii::$app->user->isGuest) {
            $cartItems = \Yii::$app->session->get(CartItems::SESSION_KEY, []);
            $sum = 0;
            foreach ($cartItems as $cartItem) {
                $sum += $cartItem['quantity'] * $cartItem['price'];

            }
//            VarDumper::dump($cartItems, 10, true);

        } else {
            $sum = CartItems::findBySql("
        SELECT SUM(c.quantity * p.price ) 
        FROM cart_items c 
            LEFT JOIN product p on c.product_id = p.id  WHERE  user_id=:userId
        ", ['userId' => $currUserId])->scalar();
        }
        return $sum;
    }

    public static function clearCartItems($currUserId)
    {
        if (\Yii::$app->user->isGuest) {
            Yii::$app->session->remove(CartItems::SESSION_KEY);
        } else {
            CartItems::deleteAll(['user_id' => $currUserId]);
        }
    }
}
