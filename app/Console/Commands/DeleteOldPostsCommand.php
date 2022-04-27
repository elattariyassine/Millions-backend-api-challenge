<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class DeleteOldPostsCommand extends Command
{
    protected $signature = 'posts:delete-old-posts {days=15}';

    protected $description = 'Delete {15} days old posts.';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        Post::query()
            ->whereDate('created_at', now()->subDays($this->argument('days')))
            ->delete();

        return Command::SUCCESS;
    }
}
