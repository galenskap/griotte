<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();
        return $user
            ->recipes()
            ->when($request->has('choice') && $request->choice === 'to_make', function ($query) {
                return $query->where('to_make', true);
            })
            ->when($request->has('search'), function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function update(Recipe $recipe, Request $request)
    {
        $validated = $request->validate([
            'to_make' => 'sometimes|boolean',
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string|max:65535',
        ]);

        if (empty($validated)) {
            abort(422, 'Au moins un champ doit être fourni');
        }

        $recipe->fill($validated)->save();

        return $recipe;
    }

    public function count(Request $request)
    {
        $user = auth('sanctum')->user();
        return $user->recipes()->where('to_make', true)->count();
    }
}
