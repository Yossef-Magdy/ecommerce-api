<?php

namespace Database\Seeders;

use App\Models\Categories\Category;
use App\Models\Categories\SubCategory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->addPermissions();
        $this->addCategories();
        $this->addSubcategories();
    }
    private function addPermissions() {
        $actions = ['add', 'delete', 'update', 'view'];
        $models = ['products', 'categories', 'users', 'subcategory', 'orders', 'coupons'];
        $permissions = [];
        foreach ($models as $model) {
            foreach ($actions as $action) {
                $permissions[] = "$action $model";
            }
        }
        $permissions = array_map(function($permission) {
            return ['name' => $permission];
        }, $permissions);
        Permission::insert($permissions);
    }
    private function addCategories() {
        $categories = ['men', 'women', 'kids'];
        $categories = array_map(function($category) {
            return ['name' => $category];
        }, $categories);
        Category::insert($categories);   
    }
    private function addSubcategories() {
        $subcategories = [
            ['shorts', 1], ['shirts', 1], ['pants', 1],
            ['shoes', 1], ['dresses', 2], ['skirts', 2],
            ['shirts', 2], ['pants', 3], ['t-shirts', 3],
        ];
        $subcategories = array_map(function($data) {
            $subcategory = $data[0];
            $category_id = $data[1];
            return [
                'name' => $subcategory,
                'category_id' => $category_id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $subcategories);
        SubCategory::insert($subcategories);
    }
}
