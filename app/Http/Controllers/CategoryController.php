<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Colors;
use App\Models\Icons;
use App\Models\Transactions;
use Auth;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the all categories for the given user.
     *
     */
    public function index()
    {
        $categories = Categories::user()->with('color')->orderBy('sequence','asc')->get();
        return view('category.all', ['categories' => $categories]);
    }
    
    /**
     * View category
     * @param int $id
     */
    public function add($id = null)
    {
        $category = Categories::user()->with('color','icon')->find($id);
        $colors = Colors::all();
        $icons = Icons::all();
        return view('category.add',['category' => $category, 'colors' => $colors, 'icons' => $icons]);
    }

    /**
     * View category
     * @param int $id
     */
    public function save(Request $request)
    {
        $request->flash();
        $this->validate($request, [
            'name' => 'required|string|min:2|max:20',
            'color_id' => 'required_with:colors.id|string',
            'icon_id' => 'required_with:icons.id|string',
        ]);

        if ($request->has(['name', 'color_id', 'icon_id'])) {

            $id = $request->cid;

            if($id != ''){
                $categories = Categories::user()->find($id);
                $msg = 'updated';
            }else{
                $categories = new Categories;
                $msg = 'added';
            }

            $categories->name = $request->name;
            $categories->description = $request->description;
            $categories->sequence = $request->sequence;
            $categories->color_id = $request->color_id;
            $categories->icon_id = $request->icon_id;
            $categories->user_id = Auth::id();
            $categories->save();

        	return redirect()->route('categories')->with('success', $categories->name . ' category '.$msg.' successfully!');
    	}else{
	        return redirect()->route('category_add')->with('warning', 'Something missing!');
    	}
    }

    /**
     * Delete category
     * @param int $id
     */
    public function del(Request $request)
    {
        $msgType = "error";
        if ($request->has(['cid'])) {
            if($category = Categories::user()->where('id',$request->cid)->first()){
                $transactions = Transactions::user()->where('category_id',$category->id)->get();
                $transactionsCount = count($transactions);
                if($transactionsCount > 0){
                    return redirect()->route('categories')->with('warning', $category->name . " category has ".$transactionsCount." transaction(s). Please update/delete the transaction(s) first and try again.");
                }else{
                    $category->delete();
                    $msgType = 'success';
                    $msg = $category->name . ' category deleted successfully!';
                }
            }else{
                $msg = 'Category not found!';
            }
        }else{
            $msg = 'Nothing to delete in categories!';
        }
        return redirect()->route('categories')->with($msgType, $msg);
    }
}
