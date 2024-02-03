<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, post};

it('should be able to like a question', function () {
    $user     = User::factory()->create();
    $question = Question::factory()->create();

    actingAs($user);

    post(route('question.like', $question))->assertRedirect();

    assertDatabaseHas('votes', [
        'question_id' => $question->id,
        'like'        => 1,
        'unlike'      => 0,
        'user_id'     => $user->id,
    ]);
});

it('should not be able to like more than once', function () {
    $user     = User::factory()->create();
    $question = Question::factory()->create();

    actingAs($user);

    foreach (range(1, 5) as $_) {
        post(route('question.like', $question))->assertRedirect();
    }

    expect($user->votes()->where('question_id', '=', $question->id)->get())
        ->toHaveCount(1);
});
