<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\TagRequest as StoreRequest;
use App\Http\Requests\TagRequest as UpdateRequest;
use Illuminate\Support\Facades\Auth;

class TagCrudController extends CrudController
{
    public function __construct()
    {
        parent::__construct();

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel("App\Models\Tag");
        $this->crud->setRoute(config('backpack.base.route_prefix', 'admin').'/tag');
        $this->crud->setEntityNameStrings('tag', 'tags');

        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */

        // ------ CRUD COLUMNS
        $this->crud->addColumn([
                                'name' => 'name',
                                'label' => 'Name',
                            ]);
        $this->crud->addColumn([
                                'name' => 'slug',
                                'label' => 'Slug',
                            ]);

        // ------ CRUD FIELDS
        $this->crud->addField([
                                'name' => 'name',
                                'label' => 'Name',
                            ]);
        $this->crud->addField([
                                'name' => 'slug',
                                'label' => 'Slug (URL)',
                                'type' => 'text',
                                'hint' => 'Will be automatically generated from your name, if left empty.',
                                // 'disabled' => 'disabled'
                            ]);
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }

    public function create(){
        $user = Auth::user();

        if($user->hasRole('Admin')) return parent::create();

        \Alert::warning('Bạn không có quyền sử dụng tính năng này')->flash();

        return redirect()->route('crud.tag.index');
    }

    public function edit($id){
        $user = Auth::user();

        if(!$user->hasRole('Author')) return parent::edit($id);
        \Alert::warning('Bạn không thể chỉnh sửa tag')->flash();
        return redirect()->route('crud.tag.index');
    }

    public function destroy($id){
        $user = Auth::user();

        if(!$user->hasRole('Author')) return parent::destroy($id);
        \Alert::warning('Bạn không thể xóa tag')->flash();
        return redirect()->route('crud.tag.index');
    }
}
