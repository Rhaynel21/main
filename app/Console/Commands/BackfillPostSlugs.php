<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Illuminate\Support\Str;

class BackfillPostSlugs extends Command
{
    protected $signature = 'posts:backfill-slugs';
    protected $description = 'Populate slug column for existing posts without slugs';

    public function handle()
    {
        $posts = Post::whereNull('slug')->orWhere('slug','')->get();

        $this->info("Found {$posts->count()} posts to update.");

        foreach ($posts as $post) {
            $base = Str::slug(Str::limit($post->title, 8, ''));
            $random = Str::random(6);
            $post->slug = "{$random}-{$base}";
            $post->save();

            $this->line("→ #{$post->id} → {$post->slug}");
        }

        $this->info('Done.');
    }
}
