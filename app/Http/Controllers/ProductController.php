<?php

namespace App\Http\Controllers;

use App\Models\Product;
use DataTables;
use Illuminate\Http\Request;
use Image;
use Validator;

class ProductController extends Controller {

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
        return view('backend.product.list');
    }

    public function get_table_data() {

        $currency = currency();
        $products = Product::select('products.*')
            ->with('translation')
            ->with('category.translation')
            ->where('product_type', 'general')
            ->orderBy("products.id", "desc");

        return Datatables::eloquent($products, $currency)
            ->editColumn('thumbnail', function ($product) {
                return '<img src="' . asset('uploads/media/' . $product->thumbnail) . '" class="thumb-md img-thumbnail">';
            })
            ->editColumn('unit_id', function ($product) {
                return $product->unit_number . ' ' . $product->unit->translation->short_name;
            })
            ->editColumn('price', function ($product) use ($currency) {
                if ($product->special_price > 0) {
                    return '<del>' . $currency . ' ' . $product->price . '</del><br>' . $currency . ' ' . $product->special_price;
                }
                return $currency . ' ' . $product->price;
            })
            ->editColumn('in_stock', function ($product) {
                return $product->in_stock ? status(_lang('Available')) : status(_lang('Unavailable'), 'danger');
            })
            ->editColumn('is_active', function ($product) {
                return $product->is_active ? status(_lang('Active')) : status(_lang('Inactive'), 'danger');
            })
            ->addColumn('action', function ($product) {
                return '<form action="' . action('ProductController@destroy', $product['id']) . '" class="text-center" method="post">'
                . '<a href="' . action('ProductController@edit', $product['id']) . '" data-title="' . _lang('Update Product') . '" class="btn btn-primary btn-sm"><i class="icofont-edit-alt"></i></a>&nbsp;'
                . csrf_field()
                    . '<input name="_method" type="hidden" value="DELETE">'
                    . '<button class="btn btn-danger btn-sm btn-remove" type="submit"><i class="icofont-trash"></i></button>'
                    . '</form>';
            })
            ->setRowId(function ($product) {
                return "row_" . $product->id;
            })
            ->rawColumns(['thumbnail', 'price', 'in_stock', 'is_active', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {

        if (!$request->ajax()) {
            return view('backend.product.create');
        } else {
            return view('backend.product.modal.create');
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
            'trans.name'    => 'required',
            'category_id'   => 'required',
            'unit_id'       => 'required',
            'unit_number'   => 'required|numeric',
            'price'         => 'required|numeric',
            'special_price' => 'nullable|numeric',
            'in_stock'      => 'required',
            'thumbnail'     => 'nullable|image',
        ], [
            'trans.name.unique' => _lang('Name should be unique !'),
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('products.create')
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $thumbnail = "default.png";
        if ($request->hasfile('thumbnail')) {
            $file      = $request->file('thumbnail');
            $thumbnail = time() . uniqid() . '.' . $file->getClientOriginalExtension();

            try {
                $img = Image::make($file->path());
                $img->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path() . "/uploads/media/" . $thumbnail);
            } catch (\Exception $e) {
                $file->move(public_path() . "/uploads/media/", $thumbnail);
            }

        }

        $product                = new Product();
        $product->category_id   = $request->input('category_id');
        $product->unit_id       = $request->input('unit_id');
        $product->unit_number   = $request->input('unit_number');
        $product->slug          = $request->trans['name'];
        $product->price         = $request->input('price');
        $product->special_price = $request->input('special_price');
        $product->in_stock      = $request->input('in_stock');
        $product->is_active     = $request->input('is_active');
        $product->thumbnail     = $thumbnail;

        $product->save();

        if (!$request->ajax()) {
            return redirect()->route('products.create')->with('success', _lang('Saved Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'store', 'message' => _lang('Saved Successfully'), 'data' => $product, 'table' => '#products_table']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) {
        $product = Product::find($id);
        if (!$request->ajax()) {
            return view('backend.product.view', compact('product', 'id'));
        } else {
            return view('backend.product.modal.view', compact('product', 'id'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $product = Product::find($id);
        if (!$request->ajax()) {
            return view('backend.product.edit', compact('product', 'id'));
        } else {
            return view('backend.product.modal.edit', compact('product', 'id'));
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
            'trans.name'    => 'required',
            'category_id'   => 'required',
            'unit_id'       => 'required',
            'unit_number'   => 'required|numeric',
            'price'         => 'required|numeric',
            'special_price' => 'nullable|numeric',
            'in_stock'      => 'required',
            'thumbnail'     => 'nullable|image',
        ], [
            'trans.name.unique' => _lang('Name should be unique !'),
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['result' => 'error', 'message' => $validator->errors()->all()]);
            } else {
                return redirect()->route('products.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        if ($request->hasfile('thumbnail')) {
            $file      = $request->file('thumbnail');
            $thumbnail = time() . uniqid() . '.' . $file->getClientOriginalExtension();
            try {
                $img = Image::make($file->path());
                $img->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path() . "/uploads/media/" . $thumbnail);
            } catch (\Exception $e) {
                $file->move(public_path() . "/uploads/media/", $thumbnail);
            }
        }

        $product                = Product::find($id);
        $product->category_id   = $request->input('category_id');
        $product->unit_id       = $request->input('unit_id');
        $product->unit_number   = $request->input('unit_number');
        $product->price         = $request->input('price');
        $product->special_price = $request->input('special_price');
        $product->in_stock      = $request->input('in_stock');
        $product->is_active     = $request->input('is_active');
        if ($request->hasfile('thumbnail')) {
            $product->thumbnail = $thumbnail;
        }

        $product->save();

        if (!$request->ajax()) {
            return redirect()->route('products.index')->with('success', _lang('Updated Successfully'));
        } else {
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => _lang('Updated Successfully'), 'data' => $product, 'table' => '#products_table']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', _lang('Deleted Successfully'));
    }

    public function search_products(Request $request) {
        $search = $request->get('term');
        if ($search == '') {
            return response()->json([]);
        }
        $result = Product::join('product_translations', 'products.id', 'product_translations.product_id')
            ->select('products.*', 'product_translations.name')
            ->where("name", "like", "$search%")
            ->limit(5)
            ->get();
        return response()->json($result);
    }
}