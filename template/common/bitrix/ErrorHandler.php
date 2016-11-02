<?php

namespace common\bitrix;

/**
 * Class ErrorHandler
 * @package common\bitrix
 */
class ErrorHandler extends \yii\base\ErrorHandler
{
    /**
     * Nothing to do. Bitrix catches errors and exceptions already
     *
     * @see \Bitrix\Main\Diag\ExceptionHandler::initialize()
     * @see \Bitrix\Main\Diag\ExceptionHandler::handleError()
     * @see \Bitrix\Main\Diag\ExceptionHandler::handleException()
     */
    public function register()
    {
    }

    public function unregister()
    {
    }

    /**
     * Renders the exception.
     *
     * @param \Exception $exception the exception to be rendered.
     */
    protected function renderException($exception)
    {
    }
}
