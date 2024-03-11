<?php
namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Vote;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        // Obter o nome do driver do banco de dados
        $driver = \DB::connection()->getDriverName();

        // Construir a consulta com base no driver
        $query = Question::query()
            ->when(request()->has('search'), function (Builder $query) {
                $query->where('question', 'like', '%' . request()->search . '%');
            })
            ->leftJoin('votes', 'questions.id', '=', 'votes.question_id')
            ->selectRaw('questions.*, SUM(votes.like) as votes_sum_like, SUM(votes.unlike) as votes_sum_unlike')
            ->whereNull('questions.deleted_at')
            ->groupBy('questions.id');

        // Ajustar a ordenação com base no driver
        if ($driver == 'pgsql') {
            $query->orderByRaw('COALESCE(SUM(votes.like), 0) DESC, COALESCE(SUM(votes.unlike), 0) DESC');
        } elseif ($driver == 'mysql') {
            $query->orderByDesc('votes_sum_like')
                ->orderByDesc('votes_sum_unlike');
        }

        // Paginar e passar os resultados para a visão
        return view('dashboard', [
            'questions' => $query->paginate(5),
        ]);

    }
}
