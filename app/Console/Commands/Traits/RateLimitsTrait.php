<?php

namespace App\Console\Commands\Traits;

use App\Models\Exchange;

trait RateLimitsTrait
{
    protected function checkRateLimits($limit, Exchange $exchange, $job_name)
    {
        if ($limit < 30 && $limit > 0){
            sleep(3);
            if ($limit < 10){
                logi("Reaching exchange {$job_name} limits {$exchange->name} #{$exchange->id} LIMIT:{$limit}");
            }
        }
    }

    protected function processApiError($error_msg, Exchange $exchange, $job_name)
    {
        if (in_array($error_msg, ['invalid api_key', 'API key is invalid.'])) {
            $exchange->api_error = 1;
            $exchange->save();
        }
        loge("Error {$job_name} {$exchange->name} #{$exchange->id} Error:{$error_msg}");
    }
}
