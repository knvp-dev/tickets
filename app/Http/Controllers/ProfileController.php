<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Plan;

class ProfileController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        return view('pages.profile.index', [
            'user' => $user,
            'payments' => $user->payments()->paginate(5, ['*'], 'payments'),
            'teams' => $user->teams()->paginate(5, ['*'], 'teams')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (request()->file('avatar')->isValid()) {
            $file = request()->file('avatar');
            $ext = $file->guessClientExtension();

            $file->storeAs('avatars/' . auth()->id(), 'avatar.' . $ext, 'public');

            auth()->user()->setAvatar('avatars/' . auth()->id() . '/avatar.' . $ext);

            return back();
        }

        return back()->with('message', 'There was a problem with uploading your file, please try a different file or try again.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
