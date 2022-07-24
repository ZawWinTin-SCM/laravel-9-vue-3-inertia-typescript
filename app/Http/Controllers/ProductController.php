<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class ProductController extends Controller
{
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $products = Product::sort()->get();

        return Inertia::render('Product', [
            'products' => $products,
        ]);
    }

    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $image = $this->imageService->upload($request->file('image'));
            Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'image' => $image,
            ]);

            DB::commit();
        } catch (Throwable $th) {
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Product Creating failed] Message: ' . $th->getMessage());
            DB::rollBack();
        }
    }

    public function update(Product $product, ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'price' => $request->price,
            ];
            if ($request->file('image')) {
                $this->imageService->removeImage($product->image);
                $data['image'] = $this->imageService->upload($request->file('image'));
            }
            $product->update($data);

            DB::commit();
        } catch (Throwable $th) {
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Product Updating failed] Message: ' . $th->getMessage());
            DB::rollBack();
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            $this->imageService->removeImage($product->image);
            $product->delete();

            DB::commit();
        } catch (Throwable $th) {
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Product Deleting failed] Message: ' . $th->getMessage());
            DB::rollBack();
        }
    }
}
