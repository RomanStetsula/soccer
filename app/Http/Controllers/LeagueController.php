<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\League;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;

class LeagueController extends Controller
{
    /**
     * @var League
     */
    public $league;

    /**
     * LeagueController constructor.
     * @param League $league
     */
    public function __construct(League $league)
    {
        $this->league = $league;
    }

    public function index()
    {
        $leagues = $this->league->orderBy('tier', 'asc')
            ->orderBy('country', 'ask')
            ->paginate(20);

        if(!isset($leagues[0])){
            return view('league.createEdit');
        }
        return view('league.all', ['leagues' => $leagues]);
    }

    public function create()
    {
        return view('league.createEdit');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->league->create($request->input());

        Session::flash('message', 'Ліга успішно створена');

        return Redirect::to('league');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $league = $this->league->find($id);

        return view('league.createEdit', array('league' => $league));
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $league = $this->league->find($id);
        $league->fill($request->input());
        $league->save();
        Session::flash('message', 'Ліга успішно відредагована');

        return Redirect::to('league');
    }

    public function destroy($id){
        $league = $this->league->find($id);
        $league->delete();

        return Redirect::to('league');
    }
}
