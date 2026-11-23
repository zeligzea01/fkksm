<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/fkksm/index.html');
});

Route::get('/portofolio', function () {
    $projects = [
        [
            'name' => 'Website FKKSM',
            'description' => 'Project pertama saya untuk belajar membuat website dan memahami kebutuhan pengguna.',
        ],
    ];

    return view('portofolio', compact('projects'));
});