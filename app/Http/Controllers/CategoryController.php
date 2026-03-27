<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function index()
    {
        // maybe i could show all categories with there tasks and habits 
    }

    public function create()
    {
        return view('tasks.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated() ;
        $data['user_id'] = Auth::id() ;
        $category = Category::create($data) ;
        return redirect()->route('categories.show' , $category) ;
    }

    
    public function show(Category $category)
    {
        // show cat with there tasks and habits hhh
        $category->load('habits' , 'tasks' );
        $habits = $category->habits ;
        $tasks = $category->tasks ;
        return view('tasks.categories.show' , compact('category' , 'habits' , 'tasks'));
    }


    public function edit(Category $category)
    {
        return view('tasks.categories.edit' , compact('category')) ;
    }


    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated() ;
        $category->update($data) ;
        $category->save() ;
        return redirect()->route('categories.show' , $category) ;
    }


    public function destroy(Category $category)
    {
        $category->delete() ;
        return redirect()->route('categorie.index') ;
    }
}
