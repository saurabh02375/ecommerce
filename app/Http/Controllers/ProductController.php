<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Lookups;
use App\Models\Product;
use App\Models\Product_colors;
use App\Models\Size;
use App\Models\Tag;
use App\Models\User;
use App\Models\User_tag;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use PDF;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

    public function productdelete($id)
    {
        $user = Product::where('id', $id)->delete(['is_deleted' => 1]);

        return redirect()->back();
    }

    public function productstatus($id)
    {
        $user = Product::where('id', $id)->first();
        if ($user) {
            $user->is_active = !$user->is_active;
            $user->save();
            return redirect()->back();
        } else {
            return 'User not found.';
        }
    }

    public function productpage()
    {
        $product = Product::all();
        $brand = Brand::all();
        $lookups = Lookups::all();
        $users  = User::all();

        return view('adminpnlx.users.addproduct', compact('product', 'brand', 'lookups', 'users'));
    }
    public function addproduct(Request $request)
    {
        $validation = $request->validate([
            'name' => ['required', 'unique:products'],
        ]);
        $product = new Product();
        $product->name = $request->input('name');
        $product->brand_id = $request->input('brand_id');
        $product->price = $request->input('price');
        $product->description = $request->input('description');

        if ($request->hasFile('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = time() . '-image.' . $extension;
            $folderName = strtoupper(date('M') . date('Y')) . "/";
            $folderPath = config('constants.catimage_IMAGE_ROOT_PATH') . $folderName;
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, $mode = 0777, true);
            }
            if ($request->file('image')->move($folderPath, $fileName)) {
                $product->image = $folderName . $fileName;
            }
        }
        $product->save();


        $colorId = $request->input('color');
        $productColor = new Product_colors();
        $productColor->product_id = $product->id;
        $productColor->color_id = $colorId;
        $productColor->save();

        $tagcode = $request->input('tags');
        $newtag = $request->input('newtags');
        $sizes = $request->input('size');
        $newsize = $request->input('newsize');

        if ($tagcode) {


            $tag = Lookups::where('code', $tagcode)->first();
        } else {

            $tag = new Lookups();
            $tag->code = $newtag;
            $tag->type = 'tag';
            $tag->save();
        }


        $productTag = new Tag();
        $productTag->product_id = $product->id;
        $productTag->tag_id = $tag->id;
        $productTag->save();
        $userid = $request->input('usertags');
        $user    = User::where('id', $userid)->first();

        $usertag    =  new User_tag();
        $usertag->user_id   = $userid;
        $usertag->tag_id = $tag->id;
        $usertag->product_id = $product->id;
        $usertag->save();

        if ($sizes) {

            $newsave    = Lookups::where('code', $sizes)->first();
        } else {
            $newsave     = new Lookups();
            $newsave->code  =  $newsize;
            $newsave->type  =  'size';
            $newsave->save();
        }

        $size = new Size();
        $size->size_id = $newsave->id;
        $size->product_id = $product->id;
        $size->save();



        return redirect()->route('producttable')->with('success', 'Product added successfully');
    }

    public function producttable(Request $request)
    {
        $search = $request->input('search');

        $productsQuery = DB::table('products')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('product_colors', 'products.id', '=', 'product_colors.product_id')
            ->leftJoin('tags', 'products.id', '=', 'tags.product_id')
            ->leftJoin('sizes', 'products.id', '=', 'sizes.product_id')
            ->leftJoin('user_tags', 'products.id', '=', 'user_tags.product_id')
            ->leftJoin('lookups as colors', 'product_colors.color_id', '=', 'colors.id')
            ->leftJoin('lookups as tag', 'tags.tag_id', '=', 'tag.id')
            ->leftJoin('lookups as usertag', 'user_tags.user_id', '=', 'usertag.id')
            ->leftJoin('lookups as size', 'sizes.size_id', '=', 'size.id')
            ->select('brands.name AS brandname', 'products.*', 'colors.code AS color_type', 'tag.code AS tag', 'user_tags.user_id AS user', 'size.code AS size');

        if ($search) {
            $productsQuery->where('products.name', 'LIKE', '%' . $search . '%');
            Session::put('export_data_payout_search', $search);
        } else {
            Session::forget('export_data_payout_search');
        }

        $products = $productsQuery->get();

        return view('adminpnlx.dashboard.Product', compact('products'));
    }


    public function createPDF()
    {
        $search = Session::get('export_data_payout_search');

        $productsQuery = DB::table('products')
            ->leftJoin('brands', 'products.brand_id', '=', 'brands.id')
            ->leftJoin('product_colors', 'products.id', '=', 'product_colors.product_id')
            ->leftJoin('tags', 'products.id', '=', 'tags.product_id')
            ->leftJoin('sizes', 'products.id', '=', 'sizes.product_id')
            ->leftJoin('user_tags', 'products.id', '=', 'user_tags.product_id')
            ->leftJoin('lookups as colors', 'product_colors.color_id', '=', 'colors.id')
            ->leftJoin('lookups as tag', 'tags.tag_id', '=', 'tag.id')
            ->leftJoin('lookups as usertag', 'user_tags.user_id', '=', 'usertag.id')
            ->leftJoin('lookups as size', 'sizes.size_id', '=', 'size.id')
            ->select('brands.name AS brandname', 'products.*', 'colors.code AS color_type', 'tag.code AS tag', 'user_tags.user_id AS user', 'size.code AS size');

        if ($search) {
            $productsQuery->where('products.name', 'LIKE', '%' . $search . '%');
        }

        $products = $productsQuery->get();

        view()->share('products', $products);

        $pdf = FacadePdf::loadView('pdf.template', compact('products', 'search'));

        return $pdf->download('pdf_file.pdf');
    }
}
