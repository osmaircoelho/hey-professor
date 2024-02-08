<?php

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, put};

it('should be able to publish', function () {

    $user = User::factory()->create();

    $question = Question::factory()->create(['draft' => true]);

    actingAs($user);

    put(route('question.publish', $question))
        ->assertRedirect();

    # revalida as informacoes pois estava como true e agora esta como false
    $question->refresh();

    expect($question)
        ->draft->toBeFalse();
});
