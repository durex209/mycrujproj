<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipality;
use GuzzleHttp\Psr7\Message;

use League\CommonMark\Extension\Attributes\Node\Attributes;

class PostController extends Controller
{
    public function __construct(Municipality $municipal)
    {
        $this->municipal = $municipal;
    }

    public function edit (Request $request, $id)
    {
        $data = $this->municipal::where('id', $id)->first([
            'id', 'citymunDesc', 'regDesc', 'provCode', 'citymunCode'
        ]);

        return inertia('Posts/Create', [
            "editData" => $data,
        ]);
    }

    public function update(Request $request, $id) 
    {
        $data = $this->municipal->findorFail($request->id);
        $data->update ([
            'citymunDesc' => $request->citymunDesc,
            'regDesc' => $request->regDesc,
            'provCode' => $request->provCode,
            'citymunCode' => $request->citymunCode,
        ]);
        return redirect('/posts')->with('message', 'Municipal updated.');
        
    }

    public function index(Request $request) 
    {

        // $result = $this->municipal::where('citymunDesc', 'like', '%'.'a')
        //             ->simplePaginate(10);

        $result = $this->municipal::query()
            ->when($request->input('search'), function ($query, $search){
                $query->where('citymunDesc', 'like',"%{$search}%");
            })
            ->simplePaginate(10)
            ->through(fn($municipal)=>[
                'id' => $municipal->id,
                'citymunDesc' => $municipal->citymunDesc,
                'provCode' => $municipal->provCode,
            
        ]);

        return inertia ('Posts/Index', ['municipalities' =>  $result]);
    }
}
