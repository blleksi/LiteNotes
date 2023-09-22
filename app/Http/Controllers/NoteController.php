<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$notes = Note::where('user_id', Auth::id())->latest('updated_at')->paginate(5);
        //$notes = Auth::user()->notes()->latest('updated_at')->paginate(5);
        $notes = Note::whereBelongsTo(Auth::user())->latest('updated_at')->paginate(5);
        return view('notes.index')->with('notes',$notes);
       /*  $notes->each(function($note){
            dump($note->title);
        }); */
       // dd($notes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:120',
            'text'  => 'required'

        ]);
       
        Auth::user()->notes()->create([
                'uuid'    => Str::uuid(),
                'title'   => $request->title,
                'text'    => $request->text
            ]);

/*         
//eski versiyon
        Note::create([ 
            'uuid'    => Str::uuid(),
            'user_id' => Auth::id(),
            'title'   => $request->title,
            'text'    => $request->text
        ]); */
        return to_route('notes.index');
        //dd($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
       // if ($note->user_id != Auth::id()){
        if (!$note->user->is(Auth::user())){
            return abort(403);
        }
       //$note = Note::where('id', $note->id)->where('user_id', Auth::id())->firstOrFail();
        return view('notes.show')->with('note',$note);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
       // if ($note->user_id != Auth::id()){
        if (!$note->user->is(Auth::user())){
            return abort(403);
        }
        return view('notes.edit')->with('note',$note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
       // if ($note->user_id != Auth::id()){
        if (!$note->user->is(Auth::user())){
            return abort(403);
        }
        $request->validate([
            'title' => 'required|max:120',
            'text'  => 'required'

        ]);

        $note->update([
            'title' => $request->title,
            'text'  => $request->text
        ]);
        return to_route('notes.show',$note)->with('success','Note edited successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
       // if ($note->user_id != Auth::id()){
        if (!$note->user->is(Auth::user())){
            return abort(403);
        }

        $note->delete();
        return to_route('notes.index')->with('success','Note Moved to Trash successfully.');
    }
}
