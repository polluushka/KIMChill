<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with('user')->orderByDesc('created_at')->get();
        return response()->json($reviews, 201);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'review_text'=>['required'],
        ],
            [
                'review_text.required'=>'Обязательное поле',
            ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $review = new Review();
        $review->user_id = Auth::id();
        $review->description = $request->review_text;
        $review->stars = $request->stars;
        if ($request->file('img_review')) {
            foreach ($request->file('img_review') as $key=>$img) {
                $path = $img->store('img/reviews');
                $path = 'storage/' . $path;

                if ($key + 1 === count($request->file('img_review'))) {
                    $review->imgs = $review->imgs . $path;
                } else {
                    $review->imgs = $review->imgs . $path . ';';
                }
            }
        } else {
            $review->imgs = '';
        }
        $review->save();
        return response()->json('Отзыв сохранён', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'review_text_edit'=>['required'],
        ], [

            'review_text_edit.required'=>'Обязательное поле',

        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        $review = Review::query()->where('id', $request->id)->first();
        $review->stars = $request->stars;
        $review->description = $request->review_text_edit;
        $old_imgs = explode(',', $request->imgs);
        if ($old_imgs) {
            $review->imgs = null;
            foreach ($old_imgs as $key=>$img) {
                $path = $img;

                if ($key + 1 === count($old_imgs)) {
                    $review->imgs = $review->imgs . $path;
                } else {
                    $review->imgs = $review->imgs . $path . ';';
                }
            }
        } else {
            $review->imgs = null;
        }

        if ($request->img_new) {
            if ($review->imgs != null) {
                $review->imgs = $review->imgs . ';';
            }
            foreach ($request->img_new as $key=>$img) {
                $path = $img->store('img/reviews');
                $path = 'storage/' . $path;

                if ($key + 1 === count($request->img_new)) {
                    $review->imgs = $review->imgs . $path;
                } else {
                    $review->imgs = $review->imgs . $path . ';';
                }
            }
        }

        $review->update();
        return response()->json('Отзыв изменён', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
