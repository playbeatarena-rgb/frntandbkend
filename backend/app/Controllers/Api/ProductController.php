<?php

namespace App\Controllers\Api;

use App\Models\ProductModel;
use App\Services\ProductService;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

/**
 * Product API Controller
 * Handles all product-related API endpoints
 */
class ProductController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = ProductModel::class;
    protected $format = 'json';
    protected $service;

    public function __construct()
    {
        $this->service = new ProductService();
    }

    /**
     * Get all products with optional filtering
     */
    public function index()
    {
        try {
            $category = $this->request->getVar('category');
            $limit = (int)$this->request->getVar('limit') ?? 50;
            $page = (int)$this->request->getVar('page') ?? 1;

            $products = $category 
                ? $this->model->where('category', $category)->paginate($limit)
                : $this->model->paginate($limit);

            return $this->respond([
                'data' => $products,
                'pager' => $this->model->pager->makeLinks(),
                'total' => $this->model->countAllResults()
            ], 200);
        } catch (\Exception $e) {
            return $this->fail('Error fetching products: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get product by ID
     */
    public function show($id = null)
    {
        try {
            if (!is_numeric($id)) {
                return $this->fail('Invalid product ID', 400);
            }

            $product = $this->model->find($id);
            if (!$product) {
                return $this->failNotFound('Product not found');
            }

            return $this->respond($product, 200);
        } catch (\Exception $e) {
            return $this->fail('Error fetching product: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get products by category
     */
    public function getByCategory($category = null)
    {
        try {
            if (empty($category)) {
                return $this->fail('Category parameter is required', 400);
            }

            $products = $this->model->where('category', $category)->findAll();
            
            if (empty($products)) {
                return $this->failNotFound('No products found in this category');
            }

            return $this->respond($products, 200);
        } catch (\Exception $e) {
            return $this->fail('Error fetching category products: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Create new product
     */
    public function create()
    {
        try {
            $data = $this->request->getJSON(true);

            if (!$this->validate($this->model->validationRules)) {
                return $this->fail($this->validator->getErrors(), 400);
            }

            if ($this->model->insert($data)) {
                return $this->respondCreated([
                    'message' => 'Product created successfully',
                    'id' => $this->model->getInsertID()
                ]);
            }

            return $this->fail($this->model->errors(), 400);
        } catch (\Exception $e) {
            return $this->fail('Error creating product: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update product
     */
    public function update($id = null)
    {
        try {
            if (!is_numeric($id)) {
                return $this->fail('Invalid product ID', 400);
            }

            if (!$this->model->find($id)) {
                return $this->failNotFound('Product not found');
            }

            $data = $this->request->getJSON(true);

            if ($this->model->update($id, $data)) {
                return $this->respond(['message' => 'Product updated successfully'], 200);
            }

            return $this->fail($this->model->errors(), 400);
        } catch (\Exception $e) {
            return $this->fail('Error updating product: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete product
     */
    public function delete($id = null)
    {
        try {
            if (!is_numeric($id)) {
                return $this->fail('Invalid product ID', 400);
            }

            if (!$this->model->find($id)) {
                return $this->failNotFound('Product not found');
            }

            if ($this->model->delete($id)) {
                return $this->respondDeleted(['message' => 'Product deleted successfully']);
            }

            return $this->fail('Unable to delete product', 400);
        } catch (\Exception $e) {
            return $this->fail('Error deleting product: ' . $e->getMessage(), 500);
        }
    }
}
