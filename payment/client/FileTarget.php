<?php

namespace app\payment\client;

/**
 * Write only log message
 */
class FileTarget extends \yii\log\FileTarget
{
    /**
     * Return only log message
     * @param array $message
     * @return string
     */
    public function formatMessage($message): string
    {
        list($text, $level, $category, $timestamp) = $message;
        return $text;
    }
}
