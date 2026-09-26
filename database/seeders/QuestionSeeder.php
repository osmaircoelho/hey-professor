<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{

    public function run(): void
    {
        $questions = [
            [
                'id' => 1,
                'created_by' => 2,
                'question' => "May it won't be raving mad--at least not so mad.",
                'draft' => 0,
                'created_at' => '2024-03-09 18:02:14',
                'updated_at' => '2024-03-09 18:02:14',
            ],
            [
                'id' => 2,
                'created_by' => 3,
                'question' => "HERE.' 'But then,' thought Alice, 'they're sure.",
                'draft' => 0,
                'created_at' => '2024-03-09 18:02:14',
                'updated_at' => '2024-03-09 18:02:14',
            ],
            [
                'id' => 3,
                'created_by' => 4,
                'question' => "Dodo replied very gravely. 'What else have you.",
                'draft' => 1,
                'created_at' => '2024-03-09 18:02:14',
                'updated_at' => '2024-03-09 18:02:14',
            ],
            [
                'id' => 4,
                'created_by' => 5,
                'question' => "King say in a moment: she looked down at them.",
                'draft' => 1,
                'created_at' => '2024-03-09 18:02:14',
                'updated_at' => '2024-03-09 18:02:14',
            ],
            [
                'id' => 5,
                'created_by' => 6,
                'question' => "Alice. 'Anything you like,' said the Cat. '--so.",
                'draft' => 0,
                'created_at' => '2024-03-09 18:02:14',
                'updated_at' => '2024-03-09 18:02:14',
            ],
            [
                'id' => 6,
                'created_by' => 7,
                'question' => "Hatter. Alice felt a very small cake, on which.",
                'draft' => 0,
                'created_at' => '2024-03-09 18:02:14',
                'updated_at' => '2024-03-09 18:02:14',
            ],
            [
                'id' => 7,
                'created_by' => 8,
                'question' => "Gryphon interrupted in a shrill, loud voice, and.",
                'draft' => 1,
                'created_at' => '2024-03-09 18:02:14',
                'updated_at' => '2024-03-09 18:02:14',
            ],
            [
                'id' => 8,
                'created_by' => 9,
                'question' => "Alice. The poor little thing howled so, that.",
                'draft' => 0,
                'created_at' => '2024-03-09 18:02:14',
                'updated_at' => '2024-03-09 18:02:14',
            ],
            [
                'id' => 9,
                'created_by' => 10,
                'question' => "THEN--she found herself in a languid, sleepy.",
                'draft' => 1,
                'created_at' => '2024-03-09 18:02:14',
                'updated_at' => '2024-03-09 18:02:14',
            ],
            [
                'id' => 10,
                'created_by' => 11,
                'question' => "Alice to herself, 'in my going out altogether.",
                'draft' => 0,
                'created_at' => '2024-03-09 18:02:14',
                'updated_at' => '2024-03-09 18:02:14',
            ],
            [
                'id' => 11,
                'created_by' => 1,
                'question' => "Laravel:: Configuração do ambiente local?",
                'draft' => 0,
                'created_at' => '2024-03-09 21:08:23',
                'updated_at' => '2024-03-09 21:08:27',
            ],
        ];

        foreach ($questions as $question) {
            Question::updateOrCreate(
                ['id' => $question['id']],
                $question
            );
        }
    }
}
