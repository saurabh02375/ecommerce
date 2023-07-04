<?php

namespace App\Http\Controllers\fashi;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Clothing;
use App\Models\Clothing2;
use App\Models\Contactus;
use App\Models\offer;
use App\Models\Product;
use App\Models\Propertly_likes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Slider;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\FormSubmissionMail;
use App\Models\Addtocart;
use App\Models\Lookups;
use App\Models\Product_colors;
use Illuminate\Pagination\PaginationServiceProvider;
use PHPUnit\Event\TestRunner\BootstrapFinished;
use PHPUnit\Framework\Constraint\Count;

class UserController extends Controller

{

    public function formshow()
    {
        return view('emails.form_submission');
    }
    public function home()
    {
        $category = Category::all();
        $slider = Slider::all();
        $clothing = Clothing::all();
        $offer = offer::all();
        $blog = Blog::all();
        $clothing2 = Clothing2::all();
        return view('index', compact('slider', 'category', 'clothing', 'offer', 'blog', 'clothing2'));
    }



    public function viewshop(Request $request)
    {
        $query = Product::query()->with('like', 'productcolors');

        $products = DB::table('products')
            ->leftjoin('product_colors', 'products.id', '=', 'product_colors.product_id')
            ->leftjoin('tags', 'products.id', '=', 'tags.product_id')
            ->leftjoin('sizes', 'products.id', '=', 'sizes.product_id')
            ->select('products.*', 'product_colors.color_id',  'tags.tag_id', 'sizes.size_id');

        $lookups_color = Lookups::where('type', 'color')->pluck('id', 'id');

        $pricerange = DB::table('products')
            ->select(DB::raw('MIN(price) as min_price, MAX(price) as max_price'))
            ->first();

        $minAmount = $request->input('minamount');
        $minAmount = ltrim($minAmount, '$');
        $maxAmount = $request->input('maxamount');
        $maxAmount = ltrim($maxAmount, '$');
        if ($minAmount && $maxAmount && $minAmount > 0 && $maxAmount > 0) {
            $products = $products->whereBetween('price', [$minAmount, $maxAmount]);
        }

        $status = $request->input('category');
        $sorttype = $request->input('sortType');
        $property = Propertly_likes::all();

        foreach ($request->all() as $key => $value) {
            if ($key === 'brand') {
                $products = $products->whereIn('brand_id', $value);
            }
            if ($key === 'searchcolor') {
                $products = $products->whereIn('product_colors.color_id', $value);
            }
            if ($key === 'tag') {
                $products  = $products->whereIn('tags.tag_id', $value);
            }
            if ($key === 'size') {
                $products  = $products->whereIn('sizes.size_id', [$value]);
            }
        }

        $products = $products->paginate(9);

        $Lookups = Lookups::all();
        $category = Category::all();
        $brands = Brand::all();
        $user = $query->get();


        return view('user.frontend.shop', compact('products', 'minAmount', 'maxAmount', 'pricerange', 'Lookups', 'brands', 'category', 'user', 'status', 'sorttype', 'property'));
    }

    public function blogpage()
    {

        $blog = Blog::all();
        $category = Category::all();
        return view('user.frontend.blog', compact('blog', 'category'));
    }
    public function contactpage()
    {
        $contact = Contactus::all();
        return view('user.frontend.contact', compact('contact'));
    }

    public function storeLike(Request $request)
    {
        $productId = $request->input('product_id');
        $userId = Auth::user()->id;
        $like = Propertly_likes::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
        if ($like) {
            $like->is_likes = $like->is_likes == 1 ? 0 : 1;
            $like->save();
        } else {
            $like = new Propertly_likes;

            $like->user_id = $userId;
            $like->product_id =   $productId;
            $like->is_likes = 1;
            $like->save();
        }
        return response()->json(['status' => true, 'is_likes' => $like->is_likes]);
    }
    public function addtocart($id)
    {
        if (Auth::check()) {
            $add = Addtocart::where('user_id', Auth::id())->where('product_id', $id)->first();

            if (!$add) {
                $add = new Addtocart();
                $add->product_id = $id;
                $add->user_id = Auth::id();
                $add->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Added to cart'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Already added to cart'
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Please login to add to cart'
            ]);
        }
    }

    public function viewshopcartpage()
    {

        $products    = DB::table('products')->join('addtocarts', 'products.id', '=', 'addtocarts.product_id')
            ->select('name', 'price', 'addtocarts.*', 'image')->get();


        $subtotal    =  $products->sum('price');


        return view('user.frontend.shoppingcart', compact('products', 'subtotal'));
    }

    public function deletecart($id)
    {

        $delete = Addtocart::where('id', $id)->delete();

        return back()->with('success', ' Successfully Deleted');
    }
}
    // public function index(Request $request)
    // {
    //     $DB = Category::query();
    //     $searchVariable = array();
    //     $inputGet = $request->all();
    //     if ($request->all()) {
    //         $searchData = $request->all();
    //         unset($searchData['display']);
    //         unset($searchData['_token']);

    //         if (isset($searchData['order'])) {
    //             unset($searchData['order']);
    //         }
    //         if (isset($searchData['sortBy'])) {
    //             unset($searchData['sortBy']);
    //         }
    //         if (isset($searchData['page'])) {
    //             unset($searchData['page']);
    //         }
    //         if ((!empty($searchData['date_from'])) && (!empty($searchData['date_to']))) {
    //             $dateS = $searchData['date_from'];
    //             $dateE = $searchData['date_to'];
    //             $DB->whereBetween('categories.created_at', [$dateS . " 00:00:00", $dateE . " 23:59:59"]);
    //         } elseif (!empty($searchData['date_from'])) {
    //             $dateS = $searchData['date_from'];
    //             $DB->where('categories.created_at', '>=', [$dateS . " 00:00:00"]);
    //         } elseif (!empty($searchData['date_to'])) {
    //             $dateE = $searchData['date_to'];
    //             $DB->where('categories.created_at', '<=', [$dateE . " 00:00:00"]);
    //         }
    //         foreach ($searchData as $fieldName => $fieldValue) {
    //             if ($fieldValue != "") {
    //                 if ($fieldName == "title") {
    //                     $DB->where("categories.title", 'like', '%' . $fieldValue . '%');
    //                 }
    //                 if ($fieldName == "is_active") {
    //                     $DB->where("categories.is_active", $fieldValue);
    //                 }
    //             }
    //             $searchVariable = array_merge($searchVariable, array($fieldName => $fieldValue));
    //         }
    //     }
    //     $DB->where("is_deleted", 0);
    //     $DB->where("is_active", 1);
    //     $DB->select("categories.*");
    //     $sortBy = ($request->input('sortBy')) ? $request->input('sortBy') : 'created_at';
    //     $order = ($request->input('order')) ? $request->input('order') : 'DESC';
    //     $records_per_page = ($request->input('per_page')) ? $request->input('per_page') : Config("Reading.records_per_page");
    //     $results = $DB->orderBy($sortBy, $order)->paginate($records_per_page);
    //     $complete_string = $request->query();
    //     unset($complete_string["sortBy"]);
    //     unset($complete_string["order"]);
    //     $query_string = http_build_query($complete_string);
    //     $results->appends($inputGet)->render();
    //     return View("admin.$this->model.index", compact('results', 'searchVariable', 'sortBy', 'order', 'query_string'));
    // }