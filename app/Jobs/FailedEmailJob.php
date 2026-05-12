<?php

namespace App\Jobs;

use App\Models\FailedEmailLog;
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
        try {
            FailedEmailLog::create([
                'email' => $this->email,
                'code' => $this->code,
                'error_message' => $this->errorMessage,
                'queue_name' => 'failed',
                'attempts' => $this->attempt,
                'failed_at' => now(),
            ]);

            Log::info("✅ Ошибка сохранена в БД для {$this->email}");

        } catch (\Exception $e) {
            Log::error("❌ Не удалось сохранить ошибку в БД: " . $e->getMessage());
        }
    }
}
