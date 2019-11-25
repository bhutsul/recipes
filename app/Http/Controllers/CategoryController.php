<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $categories = Category::getCategory();

        return response()->json($categories)->setEncodingOptions(JSON_UNESCAPED_UNICODE | JSON_HEX_AMP);
    }
}
