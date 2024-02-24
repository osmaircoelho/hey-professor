<?php

use App\Models\{Question, User};
use Illuminate\Pagination\LengthAwarePaginator;

use function Pest\Laravel\{actingAs, get};

it('should list all the questions', function () {
    //arrange
    $user = User::factory()->create();

    //criar algumas perguntas
    $questions = Question::factory()->count(5)->create();

    actingAs($user);

    //act
    //acessar a rota
    $response = get(route('dashboard'));

    // Assert
    //verificar se a lista de perguntas esta sendo mostrada
    /* @var Question $q */
    foreach ($questions as $q) {
        $response->assertSee($q->question);
    }
});

it('should paginate the result', function () {

    $user = User::factory()->create();
    Question::factory()->count(20)->create();

    actingAs($user);

    get(route('dashboard'))
        ->assertViewHas('questions', fn ($value) => $value instanceof LengthAwarePaginator);
});
