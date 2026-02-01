<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PollController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $polls = [
            [
                'id' => 1,
                'author' => 'Jane Doe',
                'title' => 'What is your favorite programming language?',
                'description' => 'A poll about your favorite programming languages.',
                'options' => array(
                    [
                        'label' => 'Python',
                        'votes' => 120
                    ],
                    [
                        'label' => 'JavaScript',
                        'votes' => 95
                    ],
                    [
                        'label' => 'Java',
                        'votes' => 60
                    ],
                    [
                        'label' => 'C#',
                        'votes' => 45
                    ]
                )],
            [
                'id' => 2,
                'author' => 'John Smith',
                'title' => 'What is your preferred travel destination?',
                'description' => 'A poll about preferred travel destinations.',
                'options' => array(
                    [
                        'label' => 'Beach',
                        'votes' => 80
                    ],
                    [
                        'label' => 'Mountains',
                        'votes' => 70
                    ],
                    [
                        'label' => 'City',
                        'votes' => 50
                    ],
                    [
                        'label' => 'Countryside',
                        'votes' => 30
                    ]
                )]
        ];
        return view('home', ['polls' => $polls]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}


namespace App\Http\Controllers;
