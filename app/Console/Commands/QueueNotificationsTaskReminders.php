<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTaskReminder;
use App\Repository\TasksRepository;
use Illuminate\Console\Command;

class QueueNotificationsTaskReminders extends Command
{
    public function __construct(private TasksRepository $tasksRepository)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:task-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue notifications with task remindings';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = $this->tasksRepository->getTasksForReminding();
        foreach ($tasks as $task) {
            ProcessTaskReminder::dispatch($task);
        }
        $this->info('Queued ' . count($tasks) . 'task remindings');

        return Command::SUCCESS;
    }
}
