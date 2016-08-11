<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package byiitrix\web
 *
 * @property integer $ID
 * @property string  $TIMESTAMP_X
 * @property string  $LOGIN
 * @property string  $PASSWORD
 * @property string  $CHECKWORD
 * @property string  $ACTIVE
 * @property string  $NAME
 * @property string  $LAST_NAME
 * @property string  $EMAIL
 * @property string  $LAST_LOGIN
 * @property string  $DATE_REGISTER
 * @property string  $LID
 * @property string  $PERSONAL_PROFESSION
 * @property string  $PERSONAL_WWW
 * @property string  $PERSONAL_ICQ
 * @property string  $PERSONAL_GENDER
 * @property string  $PERSONAL_BIRTHDATE
 * @property string  $PERSONAL_PHOTO
 * @property string  $PERSONAL_PHONE
 * @property string  $PERSONAL_FAX
 * @property string  $PRIMARY
 * @property string  $PERSONAL_MOBILE
 * @property string  $PERSONAL_PAGER
 * @property string  $PERSONAL_STREET
 * @property string  $PERSONAL_MAILBOX
 * @property string  $PERSONAL_CITY
 * @property string  $PERSONAL_STATE
 * @property string  $PERSONAL_ZIP
 * @property string  $PERSONAL_COUNTRY
 * @property string  $PERSONAL_NOTES
 * @property string  $WORK_COMPANY
 * @property string  $WORK_DEPARTMENT
 * @property string  $WORK_POSITION
 * @property string  $WORK_WWW
 * @property string  $WORK_PHONE
 * @property string  $WORK_FAX
 * @property string  $WORK_PAGER
 * @property string  $WORK_STREET
 * @property string  $WORK_MAILBOX
 * @property string  $WORK_CITY
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'b_user';
    }

    public static function findIdentity($id)
    {
        return self::find()->where(['id' => $id])->one();
    }

    public static function findIdentityByAccessToken($token, $type = NULL)
    {
        return NULL;
    }

    public function getId()
    {
        return $this->ID;
    }

    public function getAuthKey()
    {
        return NULL;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }
}
