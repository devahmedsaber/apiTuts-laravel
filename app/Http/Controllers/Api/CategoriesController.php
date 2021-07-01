<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(){
        $categories = Category::select('id', 'name_' . app()->getLocale() . ' as name')->get();
        return response()->json($categories);
    }
}
