<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $category = $this->category->create($request->all());

        if( !$category )
            return response()->json($category, 500);

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = $this->category->find($id);

        if(!$category)
            return response()->json('A categoria não existe!', 404);

        $update = $category->update($request->all());

        if(!$update)
            return response()->json('Não foi possível atualizar a categoria', 500);

        return response()->json($category, 200);

    }
}
