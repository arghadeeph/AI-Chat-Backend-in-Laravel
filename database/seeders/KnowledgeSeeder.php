<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Knowledge;
use App\Services\EmbeddingService;

class KnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $embedder = app(EmbeddingService::class);

        $documents = [
            'Laravel is a PHP web framework designed for web artisans.',
            'Laravel uses MVC architecture.',
            'Eloquent is Laravel’s ORM.',
            'Laravel supports REST APIs out of the box.',
            'Migrations are used for database version control.',
            'Service providers bootstrap Laravel services.',
            'Blade is Laravel’s templating engine.',
            'Laravel supports job queues and workers.',
            'Middleware filters HTTP requests.',
            'Laravel has built-in authentication scaffolding.',
            'Composer manages PHP dependencies.',
            'Laravel supports API rate limiting.',
            'Laravel works well with MySQL and PostgreSQL.',
            'Laravel supports caching using Redis.',
            'Laravel has excellent community support.',
        ];

        foreach ($documents as $text) {
            Knowledge::create([
                'content' => $text,
                'embedding' => $embedder->embed($text),
            ]);
        }
    }
}
