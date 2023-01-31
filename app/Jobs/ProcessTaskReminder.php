<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTaskReminder implements ShouldQueue
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
        //TODO: should make safeguard flag which marks queued tasks. Now it's possible they will be duplicated in queue
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->task->user->notify(new \App\Notifications\TaskReminder($this->task));

        $this->task->reminded = true;
        $this->task->save();
    }
}
