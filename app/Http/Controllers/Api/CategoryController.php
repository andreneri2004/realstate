<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryController extends Controller
{
    use SoftDeletes;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $category;
    protected $dates = ['deleted_at'];
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        $categories = $this->category->paginate('10')->all();
        return Response()->json([
            'data' => $categories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->all();

        try {

            $category = $this->category->create($data);
            return Response()->json([
                "success" => "Categoria salva com sucesso!"
            ], 200);

        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return Response()->json($message->getMessage(), 401);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $category = $this->category->findOrFail($id);
            return Response()->json(['data' => $category], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return Response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $data = $request->all();

        try {
            $category = $this->category->findOrFail($id);
            $category->update($data);
            return Response()->json(['success' => 'Categoria atualizada com sucesso!'], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return Response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->category->findOrFail($id)->delete();
            return Response()->json(['success' => 'Categoria removida com sucesso!'],200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return Response()->json($message->getMessage(), 401);
        }
    }

    public function realstates($id){

        try {
            $category = $this->category->findOrFail($id);
            return Response()->json(['data' => $category->realStates], 200);
        } catch (\Throwable $th) {
            $message = new ApiMessages($th->getMessage());
            return Response()->json($message->getMessage(), 401);
        }
    }
}
