<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\StoreUpdateProductFormRequest;
use App\Models\Product;


class ProductController extends Controller
{
    private $product;
    private $totalPage = 50;
    private $path = 'products';

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
        $data = $request->all();

        // Verifica se existe aquivos para fazer upload
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            /** O trecho de código $request->name::of('fooBar')->kebab()
             * convert a string $request->name para kebab_case, ou seja, retira caracteres especiais e espaços do nome do arquivo
             *
             * O trecho de código $request->image->extension()
             * Pega a extensão do arquivo quem vem na request
             *
             */
            $fileName = Str::of($request->name)->kebab().'.'.$request->image->extension();
            $data['image'] = $fileName;

            if(!$request->file('image')->storeAs("public/{$this->path}", $fileName))
                return response()->json(['error' => 'Não foi possível fazer o upload da imagem'], 500);
        }

        $product = $this->product->create($data);

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
        $data = $request->all();

        $product = $this->product->find($id);

        if(!$product)
            return response()->json(['erro' => 'Produto não encontrado'], 404);

        // Verifica se existe aquivos para fazer upload
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            /** Verifica que existe uma imagem no produto. Se existir, e for realizada uma atualização na imagem, a imagem que já estava armazenada é excluída do diretório e a
             * nova imagem é inserida.
             */
            if( $product->image ) { // Se existir a imagem
                /**
                 * A facade Storage do láravel verifica se o arquivo existe no diretório
                 */
                if(Storage::exists("{$this->path}/{$product->image}"))
                    Storage::delete("{$this->path}/{$product->image}"); // Storage::delete é o método que vai deletar o arquivo do diretório
            }

            /** O trecho de código $request->name::of('fooBar')->kebab()
             * convert a string $request->name para kebab_case, ou seja, retira caracteres especiais e espaços do nome do arquivo
             *
             * O trecho de código $request->image->extension()
             * Pega a extensão do arquivo quem vem na request
             *
             */
            $fileName = Str::of($request->name)->kebab().'.'.$request->image->getClientOriginalExtension();
            $data['image'] = $fileName;

            if(!$request->file('image')->storeAs("public/{$this->path}", $fileName))
                return response()->json(['error' => 'Não foi possível fazer o upload da imagem'], 500);
        }


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
