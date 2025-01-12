<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController {
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup() {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation() {
        /*
            1. Add Columns --done
            2. Reorder --done
            3. Display Image --done
            4. Add Links --done
            5. Wrappers --done
            6. Actions and dropdown button --done
            7. Custom column --done
            8. Filters --This is the pro feature, so we couldn't review it;
            9. Export Buttons --This is the pro feature, so we couldn't review it;
            10. Details row. --This is the pro feature, so we couldn't review it;
        */

        // CRUD::setFromDb(); // set columns from db columns.
        // CRUD::enableExportButtons(); //Pro Feature
        // CRUD::enableDetailsRow(); //Pro Feature
        CRUD::column('id')->type('my_custom_column');
        CRUD::column('price')->prefix("$")->suffix("!");
        CRUD::column('image')->type("image");
        CRUD::column('name');
        CRUD::column('description');
        CRUD::column('category')->wrapper([
            'href' => function ($crud, $column, $entry) {
                return backpack_url("category/" . $entry->category_id . "/show");
            }
        ]);
        CRUD::column('status')->wrapper([
            'class' => function ($crud, $column, $entry) {
                return match ($entry->status) {
                    'DRAFT' => 'badge bg-warning',
                    default => 'badge bg-success'
                };
            }
        ]);

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation() {
        CRUD::setValidation(ProductRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation() {
        $this->setupCreateOperation();
    }
}
