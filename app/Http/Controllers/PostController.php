<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipality;
use League\CommonMark\Extension\Attributes\Node\Attributes;

class PostController extends Controller
{
    public function __construct(Municipality $municipality)
    {
        $this->municipality = $municipality;
    }

    public function edit (Request $request, $id)
    {
        $data = $this->municipality::where('id', $id)->first([
            'id', 'citymunDesc', 'regDesc', 'provCode', 'citymunCode'
        ]);

        //dd($data);
        return inertia('Posts/Create', [
            "editData" => $data,
        ]);
    }

    public function index(Request $request) 
    {
       // $results = $model::where ('id',1)->get();

        $result = $this->municipality::where('citymunDesc', 'like', '%'.'a')
                    ->simplePaginate(10);

        //dd($result);
        //$result = Municipality::where('id',1)->first();
        //return inertia('Posts/Index', 'result' => Municipality::where('id',1)->get());

        //dd ($result);
        return inertia ('Posts/Index', ['municipalities' =>  $result]);

        //echo $result->citymunDesc;


        // return inertia('Posts/Index', [
        //     //returns an array of users with name field only
        //     "users" => $this->model
        //         ->when($request->search, function ($query, $searchItem) {
        //             //$query->where('citymunDesc', 'like', '%' . $searchItem . '%');
        //             $query->where('citymunDesc', 'like', '%' . 'in' . '%');
        //         })
        //         ->orderBy('citymunDesc', 'asc')
        //         ->simplePaginate(10)
        //         ->withQueryString()
        //         ->through(fn($mun) => [
        //             'id' => $mun->id,
        //             'citymunDesc' => $mun->citymunDesc,
        //             'photo' => $mun->getFirstMedia('avatars') ? $mun->getFirstMedia('avatars')->getUrl() : '/images/no-avatar.png',
        //             "can" => [
        //                 // 'edit' => auth()->mun()->can('edit', $mun),
        //             ],
        //         ]),
        //     "filters" => $request->only(['search']),
        //     "can" => [
        //         'createUser' => auth()->user()->can('create', User::class),
        //         'canDeleteUser' => auth()->user()->can('canDeleteUser', User::class),
        //     ],
        // ]);
        //return inertia('Posts/Index');
    }
}
