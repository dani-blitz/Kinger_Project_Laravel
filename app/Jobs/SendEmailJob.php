<?php

namespace App\Jobs;

use App\Jobs\FailedEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $email;
    protected $code;
    public $tries = 3;
    public $backoff = [5, 15, 30];

    public function __construct($email, $code)
    {
        $this->email = $email;
        $this->code = $code;
    }

    public function handle(): void
    {
        Log::info('📧 Попытка отправки письма', [
            'email' => $this->email,
            'code' => $this->code,
            'attempt' => $this->attempts()
        ]);

        try {
            // Временно: просто логируем успех (пока нет реальной почты)
            Log::info("✅ [КОД ВЕРИФИКАЦИИ] Для {$this->email} код: {$this->code}");

            // TODO: Когда настроишь SMTP - раскомментируй:
            // Mail::to($this->email)->send(new \App\Mail\VerificationCodeMail($this->code));

        } catch (Throwable $e) {
            Log::error('❌ Ошибка при отправке письма', [
                'email' => $this->email,
                'attempt' => $this->attempts(),
                'error' => $e->getMessage()
            ]);

            FailedEmailJob::dispatch(
                $this->email,
                $this->code,
                $e->getMessage(),
                $this->attempts() + 1
            );

            throw $e;
        }
    }

    public function failed(Throwable $exception)
    {
        Log::critical('💀 Все попытки отправки исчерпаны', [
            'email' => $this->email,
            'code' => $this->code,
            'last_error' => $exception->getMessage()
        ]);

        FailedEmailJob::dispatch(
            $this->email,
            $this->code,
            "Все попытки исчерпаны. Последняя ошибка: " . $exception->getMessage(),
            3
        );
    }
}
