<?php

# ->todo() com este method podemos marcar como pendente

use App\Models\{Question, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, post, postJson};

it('should be able to create a new question bigger than 255 characters', function () {
    // AAA
    // arrange :: preparar, prepara o ambiente para os testes
    $user = User::factory()->create();
    actingAs($user);

    // act ::
    $request = post(route('question.store'), [
        'question' => str_repeat('*', 260) . '?',
    ]);

    // assert ::
    $request->assertRedirect();
    assertDatabaseCount('questions', 1);
    assertDatabaseHas('questions', ['question' => str_repeat('*', 260) . '?']);
});

it('should check if ends w/ question mark ?', function () {
    // Arrange :: preparar
    $user = User::factory()->create();
    actingAs($user);

    // Act :: agir
    $request = post(route('question.store'), [
        'question' => str_repeat('*', 10),
    ]);

    // assert ::
    $request->assertSessionHasErrors(
        ['question' => 'Are you sure that is a question? It is missing the question mark in the end.']
    );
    assertDatabaseCount('questions', 0);
});

it('should have at least 10 characters', function () {
    // Arrange :: preparar
    $user = User::factory()->create();
    actingAs($user);

    // Act :: agir
    $request = post(route('question.store'), [
        'question' => str_repeat('*', 8) . '?',
    ]);

    // assert ::
    $request->assertSessionHasErrors(
        ['question' => __('validation.min.string', ['min' => 10, 'attribute' => 'question'])]
    );
    assertDatabaseCount('questions', 0);
});

it('should create as a draft all the time', function () {
    $user = User::factory()->create();
    actingAs($user);

    $request = post(route('question.store'), [
        'question' => str_repeat('*', 10) . '?',
    ]);

    assertDatabaseHas('questions', [
        'question' => str_repeat('*', 10) . '?',
        'draft'    => true,
    ]);
});

test('only authenticated users can create a new question', function () {
    post(route('question.store'), [
        'question' => str_repeat('*', 8) . "?",
    ])->assertRedirect(route('login'));
});

test('question should be unique', function () {
    $user = User::factory()->create();
    actingAs($user);

    Question::factory()->create(['question' => 'Any question?']);

    post(route('question.store'), [
        'question' => 'Any question?',
    ])->assertSessionHasErrors(['question' => 'Question already exists!']);
});
