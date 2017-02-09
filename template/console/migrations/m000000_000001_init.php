<?php

use core\Codex;
use yii\db\Migration;

class m000000_000001_init extends Migration
{
    public function safeUp()
    {
        /** @var \CDBResult $siteObject */
        $siteObject = \CSite::GetByID(Codex::SITE_ID_MAIN);

        if( $siteObject->SelectedRowsCount() > 0 ) {
            $siteFields = [
                'ACTIVE'          => 'Y',
                'SORT'            => '1',
                'DEF'             => 'Y',
                'NAME'            => 'Проект по умолчанию',
                'DIR'             => '/',
                'FORMAT_DATE'     => 'DD.MM.YYYY',
                'FORMAT_DATETIME' => 'DD.MM.YYYY HH:MI:SS',
                'SITE_NAME'       => 'Проект по умолчанию',
                'SERVER_NAME'     => 'projectexample.com',
                'EMAIL'           => 'info@projectexample.com',
                'DOMAINS'         => implode("\n", ['projectexample.com', 'dev.projectexample.com']),
            ];

            $site = new \CSite();
            $site->Update(Codex::SITE_ID_MAIN, $siteFields);

            if( strlen($site->LAST_ERROR) > 0 ) {
                $errors[] = $site->LAST_ERROR;
            }
        }

        /*
         $this->delete('b_site_template');
         $this->insert('b_site_template', [
            'SITE_ID'   => \core\Codex::SITE_ID_MAIN,
            'CONDITION' => '',
            'SORT'      => 1,
            'TEMPLATE'  => 'byiitrix-default',
         ]);
         */

        /*
        SetMenuTypes([
            'left'   => 'Левое меню',
            'top'    => 'Верхнее меню',
            'bottom' => 'Нижнее меню',
        ], Codex::SITE_ID_MAIN);
        */

        return true;
    }

    public function safeDown()
    {
        // These settings shouldn't be reverted.
        return true;
    }
}
