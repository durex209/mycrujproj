<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipality;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Extension\Attributes\Node\Attributes;
use PhpParser\Node\Stmt\TryCatch;

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


    public function create() 
    {
        return inertia('Posts/Create');
    }

    public function store(Request $request) 
    {
        $attributes = $request->validate([
                'citymunDesc' => 'required',
                'psgcCode' => 'required',
                'regDesc' => 'required',
                'provCode' => 'required',
                'citymunCode' => 'required',
        ]);
        
        DB::beginTransaction();
        try {
            $newMun = $this->municipal->create($attributes);
            $mun = Municipality::find($newMun->id);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/posts')->with('message', $e);
        }

        return redirect('/posts')->with('message', 'Municipal created');
        // $data = $this->municipal->findorFail($request->id);
        // $data->update ([
        //     'citymunDesc' => $request->citymunDesc,
        //     'regDesc' => $request->regDesc,
        //     'provCode' => $request->provCode,
        //     'citymunCode' => $request->citymunCode,
        // ]);
        // return redirect('/posts')->with('message', 'Municipal updated.');
        
    }

    public function index(Request $request) 
    {
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
