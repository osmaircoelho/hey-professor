<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $questions = Question::query()
            ->when(request()->has('search'), function (Builder $query) {
                $query->where('question', 'like', '%' . request()->search . '%');
            })
            ->withSum('votes', 'like')
            ->withSum('votes', 'unlike')
            ->orderByRaw($this->getOrderByClause())
            ->paginate(5);

        return view('dashboard', ['questions' => $questions]);
    }

    protected function getOrderByClause(): string
    {
        $databaseDriver = \DB::connection()->getDriverName();

        if ($databaseDriver === 'pgsql') {
            return 'COALESCE((SELECT SUM(like) FROM votes WHERE questions.id = votes.question_id), 0) DESC, 
                    COALESCE((SELECT SUM(unlike) FROM votes WHERE questions.id = votes.question_id), 0)';
        } else {
            // Assuming MySQL or other databases
            return 'CASE WHEN votes_sum_like IS NULL THEN 0 ELSE votes_sum_like END DESC,
                    CASE WHEN votes_sum_unlike IS NULL THEN 0 ELSE votes_sum_unlike END';
        }
    }
}
