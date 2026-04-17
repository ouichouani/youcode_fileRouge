<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function index(){
        $categories = Category::where('user_id' , Auth::id())->get() ;
        return view('tasks.categories.index' , compact('categories'));

    }

    public function create()
    {
        $this->authorize('CreateGlobalCategory', Category::class);
        return view('tasks.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('CreateGlobalCategory', Category::class);
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $category = Category::create($data);
        return redirect()->route('categories.show', $category);
    }


    public function show(Category $category)
    {
        $category->load([
            'habits' => function ($query) {
                $query->where('user_id', Auth::id());
            },
            'tasks' => function ($query) {
                $query->where('user_id', Auth::id());
            }
        ]);

        $habits = $category->habits;
        $tasks = $category->tasks;
        
        return view('tasks.categories.show', compact('category', 'habits', 'tasks'));
    }


    public function edit(Category $category)
    {
        $this->authorize('UpdateGlobalCategory', $category);
        $this->authorize('update', $category);
        return view('tasks.categories.edit', compact('category'));
    }


    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('UpdateGlobalCategory', $category);
        $this->authorize('update', $category);
        $data = $request->validated();
        $category->update($data);
        $category->save();
        return redirect()->route('categories.show', $category);
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();
        return redirect()->route('categorie.index');
    }
    

}
