<?php

namespace Database\Seeders;

use App\Models\Vote;
use Illuminate\Database\Seeder;

class VoteSeeder extends Seeder
{

    public function run(): void
    {
        $votes = [
            [
                'id' => 1,
                'question_id' => 1,
                'user_id' => 1,
                'like' => 1,
                'unlike' => 0,
                'created_at' => '2024-03-09 21:08:40',
                'updated_at' => '2024-03-09 21:08:54',
            ],
            [
                'id' => 2,
                'question_id' => 1,
                'user_id' => 1,
                'like' => 1,
                'unlike' => 0,
                'created_at' => '2024-03-09 21:08:40',
                'updated_at' => '2024-03-09 21:08:54',
            ],
            [
                'id' => 3,
                'question_id' => 1,
                'user_id' => 1,
                'like' => 1,
                'unlike' => 0,
                'created_at' => '2024-03-09 21:08:40',
                'updated_at' => '2024-03-09 21:08:54',
            ],
            [
                'id' => 4,
                'question_id' => 1,
                'user_id' => 1,
                'like' => 1,
                'unlike' => 0,
                'created_at' => '2024-03-09 21:08:40',
                'updated_at' => '2024-03-09 21:08:54',
            ],
            [
                'id' => 5,
                'question_id' => 1,
                'user_id' => 1,
                'like' => 1,
                'unlike' => 0,
                'created_at' => '2024-03-09 21:08:40',
                'updated_at' => '2024-03-09 21:08:54',
            ],
            [
                'id' => 6,
                'question_id' => 1,
                'user_id' => 1,
                'like' => 1,
                'unlike' => 0,
                'created_at' => '2024-03-09 21:08:40',
                'updated_at' => '2024-03-09 21:08:54',
            ],
            [
                'id' => 7,
                'question_id' => 2,
                'user_id' => 1,
                'like' => 1,
                'unlike' => 0,
                'created_at' => '2024-03-09 21:17:21',
                'updated_at' => '2024-03-09 21:17:21',
            ],
        ];

        foreach ($votes as $vote) {
            Vote::updateOrCreate(
                ['id' => $vote['id']],
                $vote
            );
        }
    }
}
