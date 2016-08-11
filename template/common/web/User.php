<?php

namespace common\web;

/**
 * Class WebUser
 * @package common\web
 *
 * @property User $user
 *
 * @method User getIdentity($autoRenew = true)
 */
class User extends \yii\web\User
{
    public function setIdentity(&$identity)
    {
        /**
         * @var \CUser $USER
         */
        global $USER;

        if( $identity === NULL && $USER->IsAuthorized() ) {
            $identity = \common\models\User::findIdentity($USER->GetID());
        }

        parent::setIdentity($identity);
    }
}
