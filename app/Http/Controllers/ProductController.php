<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    
    

        public function index(Request $request){
            if($request->ajax()){

                $data = Product::latest()->get();
                
                return  Datatables::of($data)
                         ->addIndexColumn()
                         ->addColumn('action', function($row){
                            $btn= '<a href="javascript:void(0)" data-toogle="tooltip" data-id="'.$row->id.'" 
                            data-original-title="Edit" class="edit btn btn-primary btn-small editProduct" >Edit</a>';
                            $btn= $btn.'<a href="javascript:void(0)" data-toogle="tooltip" data-id="'.$row->id.'" 
                            data-original-title="Delete" class="btn btn-danger btn-small deleteProduct" >Delete</a>';
                            return $btn;
                        })
                        ->rawColumns(['action'])
                        ->make(true);
            }
            return view('products');
        }

        public function edit($id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }


        public function store(Request $request){
            
          
            $request->validate([
                'name' => 'required|unique:products|max:255',
                'detail' => 'required|max:255',
            ]);

            Product::updateOrCreate([
                'id' => $request->product_id
            ],
            [
                'name' => $request->name, 
                'detail' => $request->detail
            ]);  

          return response()->json(['success'=>'Product saved successfully.']);

        }

        public function destroy($id)
    {
        Product::find($id)->delete();
      
        return response()->json(['success'=>'Product deleted successfully.']);
    }
}
