<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Produto;
use DB;

class DashboardController extends Controller
{
    public function index() {

        $usuarios = User::all()->count();


        //grafico 1 - usuários
        $usersData = User::select([
            DB::raw('YEAR(created_at) as ano'),
            DB::raw('COUNT(*) as total')
        ])
        ->groupBy('ano')
        ->orderBy('ano', 'asc')
        ->get();

        //Preparar arrays
        foreach($usersData as $user) {
            $ano[] = $user->ano;
            $total[] = $user->total;
        }

        //Formatar para chartjs
        $userLabel = "'Comparativo para cadastro de usuários'";
        $userAno = implode(',', $ano);
        $userTotal = implode(',', $total);

       //Grafico 2 - Ctaegorias
       $catData = Categoria::with('produtos')->get();

       //Preparar array
       foreach($catData as $cat) {
           $catNome[] = "'".$cat->nome."'";
           $catTotal[] = $cat->produtos->count();
       }

       //Formatar para chartjs
       $catLabel = implode(',', $catNome);
       $catTotal = implode(',', $catTotal);

        return view('admin.dashboard', compact('usuarios', 'userLabel', 'userAno', 'userTotal', 'catLabel', 'catTotal'));
    }
}
