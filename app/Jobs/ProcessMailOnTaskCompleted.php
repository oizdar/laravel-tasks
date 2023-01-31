<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMailOnTaskCompleted implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected Task $task)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var User $user */
        $user = $this->task->user()->first();
        $user->notify(new \App\Notifications\TaskCompleted($this->task));
    }
}
