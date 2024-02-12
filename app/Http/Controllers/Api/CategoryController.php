<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategoryFormRequest;
use Illuminate\Http\Request;

use App\Models\Category;

class CategoryController extends Controller
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index(Request $request)
    {
        $categories = $this->category->getResults($request->name);

        return response()->json($categories);
    }

    public function store(StoreUpdateCategoryFormRequest $request)
    {
        $category = $this->category->create($request->all());

        if( !$category )
            return response()->json(['error' => 'Não foi possível cadastrar a categoria!'], 500);

        return response()->json($category, 201);
    }

    public function update(StoreUpdateCategoryFormRequest $request, $id)
    {
        $category = $this->category->find($id);

        if(!$category)
            return response()->json(['error' => 'A categoria não existe!'], 404);

        $update = $category->update($request->all());

        if(!$update)
            return response()->json(['error' => 'Não foi possível atualizar a categoria'], 500);

        return response()->json($category, 200);
    }

    public function show($id)
    {
        $category = $this->category->find($id);

        if(!$category)
            return response()->json(['erro' => 'A categoria não existe!'], 404);

        return response()->json($category, 200);
    }

    public function destroy($id)
    {
        $category = $this->category->find($id);

        if(!$category)
            return response()->json(['error' => 'A categoria não existe!'], 404);

        if(!$category->delete())
            return response()->json(['error' => 'Não foi possível excluir a categoria!'], 500);

        return response()->json(['success' => 'A categoria foi excluída com sucesso!'], 204);

    }
}
