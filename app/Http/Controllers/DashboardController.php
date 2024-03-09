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
            return 'COALESCE(votes_sum_like, 0) DESC, COALESCE(votes_sum_unlike, 0)';
        } else {
            // Assuming MySQL or other databases
            return 'CASE WHEN votes_sum_like IS NULL THEN 0 ELSE votes_sum_like END DESC,
                    CASE WHEN votes_sum_unlike IS NULL THEN 0 ELSE votes_sum_unlike END';
        }
    }
}
