<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUpdateProductFormRequest;

use App\Models\Product;

class ProductController extends Controller
{
    private $product;
    private $totalPage = 50;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = $this->product->getResults($request->all(), $this->totalPage);

        if($products->isEmpty())
            return response()->json(['warning' => 'O produto não foi encontrado'], 404);

        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpdateProductFormRequest $request)
    {
        $product = $this->product->create($request->all());

        if(!$product)
            return response()->json(['erro' => 'Não foi possível cadastrar o produto!'], 500);

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->product->find($id);

        if(!$product)
            return response()->json(['erro' => 'Produto não encontrado'], 404);

        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreUpdateProductFormRequest $request, string $id)
    {
        $product = $this->product->find($id);

        if(!$product)
            return response()->json(['erro' => 'Produto não encontrado'], 404);

        if(!$product->update($request->all()))
            return response()->json(['erro' => 'Não foi possível atualizar o produto'], 500);

        return response()->json(['success' => 'Produto atualizado com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = $this->product->find($id);

        if(!$product)
            return response()->json(['erro' => 'Produto não encontrado'], 404);

        if(!$product->delete())
            return response()->json(['error' => 'Não foi possível excluir o produto!'], 500);

        return response()->json(['success' => 'O produto foi excluído com sucesso!'], 204);
    }
}
