<?php

namespace App\Jobs;

use App\Models\FailedEmailError;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FailedEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $email;
    protected $code;
    protected $errorMessage;
    protected $attempt;

    public function __construct($email, $code, $errorMessage, $attempt = 1)
    {
        $this->email = $email;
        $this->code = $code;
        $this->errorMessage = $errorMessage;
        $this->attempt = $attempt;
    }

    public function handle(): void
    {
        $errorType = $this->detectErrorType($this->errorMessage);

        FailedEmailError::create([
            'email' => $this->email,
            'code' => $this->code,
            'error_type' => $errorType,
            'error_message' => $this->errorMessage,
            'attempts' => $this->attempt,
            'failed_at' => now(),
        ]);

        Log::info("✅ Ошибка почты сохранена в БД", [
            'email' => $this->email,
            'type' => $errorType
        ]);
    }

    protected function detectErrorType($message)
    {
        $message = strtolower($message);

        if (str_contains($message, 'authentic')) return 'auth';
        if (str_contains($message, 'timeout')) return 'timeout';
        if (str_contains($message, 'connection') || str_contains($message, 'connect')) return 'connection';
        if (str_contains($message, 'smtp')) return 'smtp';
        return 'unknown';
    }
}
