<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogCategoryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Http\Resources\Admin\BlogCategoryResource;
use App\Models\BlogCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogCategoryApiController extends Controller
{

    /**
     * Blog Categories.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response blog categories not found
     *
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response  {
             "statusCode": 200,
             "message": "blog category fetch successfully",
             "data": {
                    "id": 70,
                    "title": "Category 7",
                    "slug": "category-7",
                    "post_count": 3
                  },
                  {
                    "id": 71,
                    "title": "Category 8",
                    "slug": "category-8",
                    "post_count": 1
                },
                {
                    "id": 72,
                    "title": "Category 9",
                    "slug": "category-9",
                    "post_count": 4
                },
                {
                    "id": 73,
                    "title": "Category 10",
                    "slug": "category-10",
                    "post_count": 1
                }
            }
      *
      * @response status=404 {
            "statusCode": 404,
            "message": "blog category not found!",
            "data": []
        }

    */
    public function index()
    {
        $blogCategories=BlogCategory::has('blog_post')->withCount('blog_post')->get();

        if (!$blogCategories) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'blog category not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }


        $cat_arr=[];

        foreach ($blogCategories as $key => $value) {
            $cat_arr[]=[
                'id'=>$value->id,
                'title'=>$value->title,
                'slug'=>$value->slug,
                'post_count'=>$value->blog_post_count,
                'lastModified'=>$value->updated_at->format('Y-m-d')
             ];
        }

        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'blog category fetch successfully',
                         'data'=>$cat_arr
                     ]);
    }

    public function store(StoreBlogCategoryRequest $request)
    {
        $blogCategory = BlogCategory::create($request->all());

        return (new BlogCategoryResource($blogCategory))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(BlogCategory $blogCategory)
    {
        abort_if(Gate::denies('blog_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new BlogCategoryResource($blogCategory);
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blogCategory)
    {
        $blogCategory->update($request->all());

        return (new BlogCategoryResource($blogCategory))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(BlogCategory $blogCategory)
    {
        abort_if(Gate::denies('blog_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blogCategory->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
