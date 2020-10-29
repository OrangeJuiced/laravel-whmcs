<?php
namespace WHMCS\Exceptions;

use Illuminate\Support\Facades\Log;

class WHMCSConnectionException extends \Exception
{
    public function __construct($log_message)
    {
        if (! is_null($log_message)) {
            Log::error($log_message);
        }

        parent::__construct($log_message);
    }
}