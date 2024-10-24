<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeleteOldPostsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Find all soft-deleted posts older than 30 days
        $posts = Post::onlyTrashed()->where('deleted_at', '<=', Carbon::now()->subDays(30))->get();

        // Force delete each post
        foreach ($posts as $post) {
            $post->forceDelete();
        }
        Log::info('Deleted old soft-deleted posts older than 30 days ' . $posts->count());
    }
}

