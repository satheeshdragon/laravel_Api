<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Corona;

class CoronaController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $coronacases = Corona::all();

        return view('index', compact('coronacases'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'country_name' => 'required|max:255',
            'symptoms' => 'required',
            'cases' => 'required|numeric',
        ]);
        $show = Corona::create($validatedData);
   
        return redirect('/coronas')->with('success', 'Corona Case is successfully saved');
    }

    public function edit($id)
    {
        $coronacase = Corona::findOrFail($id);

        return view('edit', compact('coronacase'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'country_name' => 'required|max:255',
            'symptoms' => 'required',
            'cases' => 'required|numeric',
        ]);
        Corona::whereId($id)->update($validatedData);

        return redirect('/coronas')->with('success', 'Corona Case Data is successfully updated');
    }

    public function destroy($id)
    {
        $coronacase = Corona::findOrFail($id);
        $coronacase->delete();

        return redirect('/coronas')->with('success', 'Corona Case Data is successfully deleted');
    }
}
