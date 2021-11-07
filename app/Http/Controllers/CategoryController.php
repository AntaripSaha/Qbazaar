<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Image;
use Validator;

class CategoryController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        date_default_timezone_set(get_option('timezone', 'Asia/Dhaka'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $categorys = Category::all()->sortByDesc("id");
        return view('backend.category.list', compact('categorys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (!$request->ajax()) {
            return view('backend.category.create');
        } else {
            return view('backend.category.modal.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'trans.name' => 'required|unique:category_translations,name',
            'icon'       => 'required_if:parent_id,null',
            'image'      => 'required_if:parent_id,null|image',
            'is_active'  => 'required',
        ], [
            'trans.name.unique' => _lang('Name should be unique !'),
            'icon.required_if'  => _lang('Icon is required for root category'),
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('categories.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $image = "default-cat.png";
        if ($request->hasfile('image')) {
            $file  = $request->file('image');
            $image = time() . uniqid() . '.' . $file->getClientOriginalExtension();
            $img   = Image::make($file->path());
            $img->resize(64, 64, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path() . "/uploads/media/" . $image);
        }

        $category            = new Category();
        $category->slug      = $request->trans['name'];
        $category->icon      = xss_clean($request->input('icon'));
        $category->image     = $image;
        $category->parent_id = $request->input('parent_id');
        $category->is_active = $request->input('is_active');

        $category->save();

        if (!$request->ajax()) {
            return redirect()->route('categories.create')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $category, 'table' => '#categories_table']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $category = Category::find($id);
        if (!$request->ajax()) {
            return view('backend.category.view', compact('category', 'id'));
        } else {
            return view('backend.category.modal.view', compact('category', 'id'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $category = Category::find($id);
        if (!$request->ajax()) {
            return view('backend.category.edit', compact('category', 'id'));
        } else {
            return view('backend.category.modal.edit', compact('category', 'id'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'trans.name' => 'required',
            'icon'       => 'required_if:parent_id,null',
            'image'      => 'nullable|image',
            'is_active'  => 'required',
        ], [
            'icon.required_if' => _lang('Icon is required for root category'),
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('categories.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        if ($request->hasfile('image')) {
            $file  = $request->file('image');
            $image = time() . uniqid() . '.' . $file->getClientOriginalExtension();
            $img   = Image::make($file->path());
            $img->resize(64, 64, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path() . "/uploads/media/" . $image);
        }

        $category       = Category::find($id);
        $category->icon = xss_clean($request->input('icon'));
        if ($request->hasfile('image')) {
            $category->image = $image;
        }
        $category->parent_id = $request->input('parent_id');
        $category->is_active = $request->input('is_active');

        $category->save();

        if (!$request->ajax()) {
            return redirect()->route('categories.index')->with('success', _lang('Updated Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Successfully'), 'data' => $category, 'table' => '#categories_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $category = Category::find($id);
        $category->delete();
        return redirect()->route('categories.index')->with('success', _lang('Deleted Successfully'));
    }

    public function search_categories(Request $request) {
        $search = $request->get('term');
        if ($search == '') {
            return response()->json([]);
        }
        $result = category::join('category_translations', 'categories.id', 'category_translations.category_id')
            ->select('categories.*', 'category_translations.name')
            ->where("name", "like", "$search%")
            ->limit(5)
            ->get();
        return response()->json($result);
    }
}