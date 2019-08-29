<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

class ErrorHelper
{
    public static function sendMail($mail_content)
    {
        $send_errors_on_mail_log = config("mail.send_errors_on_mail_log");
        if ($send_errors_on_mail_log) {
            Mail::raw(
                $mail_content,
                function ($message) {
                    $mail_to = config("mail.mail_error_to");
                    $mail_from = config("mail.mail_error_from");
                    $mail_subject = config("mail.subject_title");
                    $message->subject($mail_subject);
                    $message->from(explode(",", $mail_from));
                    $message->to(explode(",", $mail_to));
                }
            );
        }
    }

    /**
     *
     * Write error to log file
     * @param string $message
     */
    public static function writeErrorLog($message)
    {
        try {
            //Log::error($message);
            $view_log = new Logger('Error Logs');
            $view_log->pushHandler(new RotatingFileHandler(storage_path().'/logs/error_logs/error_log.log', 90, Logger::ERROR));

            $view_log->addError($message);
        } catch (\Exception $ex) {
        }
    }



    /**
     *
     * Wrap method to write to log file and send mail
     * @param string $mail_message
     * @param string $log_message
     */
    public static function writeError($mail_message, $log_message)
    {
        try {
            if (strlen($mail_message) > 0) {
                self::sendMail($mail_message);
            }
            if (strlen($log_message) > 0) {
                self::writeErrorLog($log_message);
            }
        } catch (\Exception $ex) {
        }
    }

    /**
     *
     * Wrap method to write to log file and send mail
     * @param string $mail_message
     * @param string $log_message
     */
    public static function writeInfo($mail_message, $log_message)
    {
        try {
            if (strlen($mail_message) > 0) {
                self::sendMail($mail_message);
            }
            if (strlen($log_message) > 0) {
                self::writeInfoLog($log_message);
            }
        } catch (\Exception $ex) {
        }
    }

    /**
     *
     * Write info to log file
     * @param string $message
     */
    public static function writeInfoLog($message)
    {
        try {
            //Log::info($message);
            $view_log = new Logger('Info Logs');
            $view_log->pushHandler(new RotatingFileHandler(storage_path().'/logs/info_logs/info_log.log', 90, Logger::INFO));

            $view_log->addInfo($message);
        } catch (\Exception $ex) {
        }
    }

    /**
     * @param $message_title
     * @param $message
     */
    public static function writeToFirebug($message_title, $message)
    {
        // Get an instance of Monolog
        $monolog = Log::getMonolog();
        // Choose FirePHP as the log handler
        $monolog->pushHandler(new \Monolog\Handler\FirePHPHandler());
        // Start logging
        $monolog->addInfo($message_title, array('message' => $message));
    }
}
