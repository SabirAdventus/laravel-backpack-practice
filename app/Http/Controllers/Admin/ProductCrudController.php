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

    // Additional Operations
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;


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
        CRUD::column('image')->type("image")->label('Image')->prefix("storage/uploads/products/");
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

    protected function setupReorderOperation()
    {
        // define which model attribute will be shown on draggable elements
        CRUD::set('reorder.label', 'name');
        // define how deep the admin is allowed to nest the items
        // for infinite levels, set it to 0
        CRUD::set('reorder.max_level', 0);

        // if you don't fully trust the input in your database, you can set 
        // "escaped" to true, so that the label is escaped before being shown
        // you can also enable it globally in config/backpack/operations/reorder.php
        CRUD::set('reorder.escaped', true);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation() {
        CRUD::setValidation(ProductRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.
        CRUD::field('name');
        CRUD::field('description');
        CRUD::field('category')->inline_create(true);
        CRUD::field('image')
            ->type('upload')
            ->label('Image')
            ->withFiles([
                'path' => 'uploads/products'
            ]);


        // This is the PRO Feature, we can't use for free
        // CRUD::field('prices')
        //     ->type('repeatable')
        //     ->label('Price Options');

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
        $this->crud->setOperationSetting("showDeleteButton", backpack_url("/"));
        $this->setupCreateOperation();
    }

    protected function setupShowOperation() {
        /*
            1. Reuse list operation logic.
            2. Use tabs (also compatible with create/update operations).
                i.   Make sure you have config (backpack.operations.show.tabsEnabled).
                ii.  Add 'tab' values to your columns.
                iii. Not all columns need a tab.
        */
        // $this->setupListOperation();

        CRUD::column('id')->label("Product ID")->tab("General");
        CRUD::column('category')->wrapper([
            'href' => function ($crud, $column, $entry) {
                return backpack_url("category/$entry->category_id/show");
            }
        ])->tab("General");
        CRUD::column('name')->tab("General");
        CRUD::column('price')->tab("General");

        CRUD::column("description")->tab("Content");
        CRUD::column("image")->type('image')->label("Product Image")->prefix("storage/uploads/products/")->tab("Content");

        CRUD::column('status')->wrapper([
            'class' => function ($crud, $column, $entry) {
                return match ($entry->status) {
                    "DRAFT" => 'badge bg-warning',
                    default => 'badge bg-success'
                };
            },
        ]);
    }
}
