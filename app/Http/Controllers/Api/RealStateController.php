<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\RealState;


class RealStateController extends Controller
{
    private $realState;
    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }

    public function index()
    {
        $realState = $this->realState->paginate('10');

        return response()->json($realState, 200);
    }

    public function store(RealStateRequest $request)
    {
        $data = $request->all();
        $images = $request->file('images');

        try {

            $realState = $this->realState->create($data);
            if (isset($data['categories'])  && count($data['categories'])) {

                $realState->categories()->sync($data['categories']);
            }
            if($images){
                foreach($images as $image){
                    $path = $image->store('images', 'public');
                    dd($path);
                }
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel cadastrado com sucesso!'
                ]
            ], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
    public function update($id, RealStateRequest $request)
    {

        $data = $request->all();

        try {
            $realState = $this->realState->findOrFail($id);
            $realState->update($data);

            if (isset($data['categories'])  && count($data['categories'])) {

                $realState->categories()->sync($data['categories']);
            }

            return Response()->json(['data' => 'Imóvel atualizado com sucesso!'], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($id)
    {
        try {
            $realState = $this->realState->findOrFail($id);
            $realState->delete();
            return Response()->json([
                'msg' => 'Imovél removido com sucesso!'
            ], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function show($id)
    {
        try {
            $data = $this->realState->findOrFail($id);
            return response()->json(['data' => $data], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
