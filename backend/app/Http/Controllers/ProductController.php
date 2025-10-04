<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::with(['category'])
                ->where('is_active', true);

            // Search
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('sku', 'LIKE', "%{$search}%")
                      ->orWhere('barcode', 'LIKE', "%{$search}%");
                });
            }

            // Category filter
            if ($request->has('category_id') && $request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            // Stock filter
            if ($request->has('stock_filter') && $request->stock_filter) {
                switch ($request->stock_filter) {
                    case 'in_stock':
                        $query->whereColumn('quantity', '>', 'min_quantity');
                        break;
                    case 'low_stock':
                        $query->whereColumn('quantity', '<=', 'min_quantity')
                              ->where('quantity', '>', 0);
                        break;
                    case 'out_of_stock':
                        $query->where('quantity', '<=', 0);
                        break;
                }
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $products = $query->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $products,
                'message' => 'Products retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:50|unique:products',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'cost_price' => 'nullable|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'min_quantity' => 'required|integer|min:0',
                'unit' => 'required|string|max:20',
                'description' => 'nullable|string',
                'barcode' => 'nullable|string|max:50|unique:products',
                'image' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $product = Product::create($request->all());

            // Create initial inventory transaction
            if ($request->quantity > 0) {
                InventoryTransaction::createTransaction(
                    $product->id,
                    auth()->id(),
                    'in',
                    $request->quantity,
                    [
                        'unit_cost' => $request->cost_price,
                        'reference_type' => 'initial_stock',
                        'notes' => 'สต๊อกเริ่มต้น'
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $product->load('category'),
                'message' => 'Product created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error creating product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::with(['category'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:50|unique:products,sku,' . $id,
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric|min:0',
                'cost_price' => 'nullable|numeric|min:0',
                'min_quantity' => 'required|integer|min:0',
                'unit' => 'required|string|max:20',
                'description' => 'nullable|string',
                'barcode' => 'nullable|string|max:50|unique:products,barcode,' . $id,
                'image' => 'nullable|string',
                'is_active' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $product->load('category'),
                'message' => 'Product updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Soft delete by setting is_active to false
            $product->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function stockHistory($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $transactions = InventoryTransaction::where('product_id', $id)
                ->with(['user'])
                ->latest()
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => [
                    'product' => $product,
                    'transactions' => $transactions
                ],
                'message' => 'Stock history retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving stock history: ' . $e->getMessage()
            ], 500);
        }
    }

    public function adjustStock(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quantity' => 'required|integer|min:0',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $product = Product::findOrFail($id);

            DB::beginTransaction();

            InventoryTransaction::createTransaction(
                $product->id,
                auth()->id(),
                'adjustment',
                $request->quantity,
                [
                    'notes' => $request->notes ?? 'ปรับยอดสต๊อก'
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $product->fresh(),
                'message' => 'Stock adjusted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error adjusting stock: ' . $e->getMessage()
            ], 500);
        }
    }
}