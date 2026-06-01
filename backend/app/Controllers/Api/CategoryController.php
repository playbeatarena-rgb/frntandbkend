<?php

namespace App\Controllers\Api;

use App\Models\CategoryModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

/**
 * Category API Controller
 * Handles all category-related API endpoints
 */
class CategoryController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = CategoryModel::class;
    protected $format = 'json';

    /**
     * Get all categories
     */
    public function index()
    {
        try {
            $categories = $this->model->findAll();
            return $this->respond($categories, 200);
        } catch (\Exception $e) {
            return $this->fail('Error fetching categories: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get category by ID
     */
    public function show($id = null)
    {
        try {
            if (!is_numeric($id)) {
                return $this->fail('Invalid category ID', 400);
            }

            $category = $this->model->find($id);
            if (!$category) {
                return $this->failNotFound('Category not found');
            }

            return $this->respond($category, 200);
        } catch (\Exception $e) {
            return $this->fail('Error fetching category: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Create new category
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
                    'message' => 'Category created successfully',
                    'id' => $this->model->getInsertID()
                ]);
            }

            return $this->fail($this->model->errors(), 400);
        } catch (\Exception $e) {
            return $this->fail('Error creating category: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update category
     */
    public function update($id = null)
    {
        try {
            if (!is_numeric($id)) {
                return $this->fail('Invalid category ID', 400);
            }

            if (!$this->model->find($id)) {
                return $this->failNotFound('Category not found');
            }

            $data = $this->request->getJSON(true);

            if ($this->model->update($id, $data)) {
                return $this->respond(['message' => 'Category updated successfully'], 200);
            }

            return $this->fail($this->model->errors(), 400);
        } catch (\Exception $e) {
            return $this->fail('Error updating category: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Delete category
     */
    public function delete($id = null)
    {
        try {
            if (!is_numeric($id)) {
                return $this->fail('Invalid category ID', 400);
            }

            if (!$this->model->find($id)) {
                return $this->failNotFound('Category not found');
            }

            if ($this->model->delete($id)) {
                return $this->respondDeleted(['message' => 'Category deleted successfully']);
            }

            return $this->fail('Unable to delete category', 400);
        } catch (\Exception $e) {
            return $this->fail('Error deleting category: ' . $e->getMessage(), 500);
        }
    }
}
