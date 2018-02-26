<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Bookkeeping;

class BookkeepingController extends Controller
{
    public function index()
    {
        $bookkeepings = Bookkeeping::all();
        return view('home.bookkeepings.index', compact('bookkeepings'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        $bk = new Bookkeeping();
        $bk->fill($request->all());
        $bk->save();

        return back()->with(['success' => 'Bookkeeping saved!']);
    }

    public function edit($id)
    {
        $bookkeeping = Bookkeeping::findOrFail($id);
        return view('home.bookkeepings.edit', compact('bookkeeping'));
    }

    public function update(Request $request, $id)
    {
        Bookkeeping::findOrFail($id)->update($request->all());
        return redirect()->route('home.bookkeepings.index')->with(['success' => 'Bookkeeping updated!']);
    }

    public function delete($id)
    {
        Bookkeeping::findOrFail($id)->delete();
        return back()->with(['success' => 'Bookkeeping deleted!']);
    }
}
