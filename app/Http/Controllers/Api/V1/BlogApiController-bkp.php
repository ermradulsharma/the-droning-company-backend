<?php

namespace App\Http\Controllers\Api\V1;

use Gate;
use App\Models\Blog;
use App\Models\PilotJob;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Resources\Admin\BlogResource;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class BlogApiController extends Controller
{

    /**
     * Blog Post listing.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response blog post not found!
     *

     *
     * <aside class="success">This api also work on search or filter as query string For Example </aside>
     *
     * <aside class="notice">basepath/api/v1/blogs?q=some desired keywords.</aside>
     *
     *
     * <aside class="notice">basepath/api/v1/blogs?category='category-slug from category api'.</aside>
     *
     *
     * @queryParam ?q Filter by desired keyword. Example ?q=some desired keywords.
     * @queryParam ?category Filter by category slug. Example ?category=category-1.
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response  {
            "statusCode": 200,
            "message": "blog post fetch successfully",
            "data": {
                "current_page": 1,
                "data": [
                    {
                        "id": 121,
                        "title": "Velit qui soluta iste sed et odit natus.",
                        "image": "http://local.drone/images/blog/resize/1623221514-520x390.jpg",
                        "excerpt": "Sint officiis facere aperiam voluptas ut voluptatum. Nostrum quisquam placeat nobis fugiat eum ut.",
                        "description": "Expedita deserunt commodi eos labore. Quam minima molestias quia fugiat illo iure. Incidunt laboriosam et sit et. Quis neque consequatur error rerum. Accusamus a repellendus possimus culpa ex voluptas aspernatur rem. Reprehenderit dignissimos explicabo accusantium sint dicta sed similique. Eos quasi dolore nulla. Cupiditate aspernatur dolorem dolor veniam fugit. Numquam tempora qui nobis veniam. Aliquam hic consequuntur molestiae fuga. Dolores laborum vel quibusdam sequi in ipsa.",
                        "slug": "laboriosam-eligendi-repellendus-eos-recusandae-est-quo",
                        "blog_categories": []
                    },
                ],
                "first_page_url": "http://local.drone/api/v1/blogs?page=1",
                "from": 1,
                "next_page_url": "http://local.drone/api/v1/blogs?page=2",
                "path": "http://local.drone/api/v1/blogs",
                "per_page": 10,
                "prev_page_url": null,
                "to": 10
            }
      }

     *
     * @response status=404 {
            "statusCode": 404,
            "message": "blog post not found!",
            "data": []
        }

    */
    public function index(Request $request)
    {
        $blogs=Blog::query()
                ->with(['blog_categories:title,slug'])
                ->select('id', 'title', 'image', 'excerpt', 'description', 'slug')
                ->latest('id');
                
        if ($request->has('q')) {
            $blogs=$blogs->where('title', 'like', '%'.$request->input('q').'%')
                        ->orWhere('description', 'like', '%'.$request->input('q').'%');
        }

        if ($request->has('category')) {
            $blogs=$blogs->whereHas('blog_categories', function ($q) use ($request) {
                $q->where('slug', 'like', '%'.$request->input('category').'%');
            });
        }
        $post_count=$blogs->count();

        if ($request->has('page')) {
            $page=$request->input('page');
            $page=$page-1;
            $offset=$page*10;
            $blogs=$blogs->offset($offset);
        }
        $blogs=$blogs->take(10)->get();
        $blogs=$blogs->map(function ($category) {
            $category->setRelation('blog_categories', $category->blog_categories->take(2));
            return $category;
        });
       
        if (!$blogs) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'blog post not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }
        

        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'blog post fetch successfully',
                         'post_count'=>$post_count,
                         'data'=>[
                            'current_page'=>$request->input('page') ?? 1,
                            'data'=>$blogs,
                            'per_page'=>10,
                         ],
                     ]);
    }

    /**
     * Recent Blog Post.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response recent blog post not found!
     *
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response  {

            "statusCode": 200,
            "message": "recent blog post fetch successfully",
            "data": [
                {
                    "id": 121,
                    "title": "Velit qui soluta iste sed et odit natus.",
                    "image": "http://local.drone/images/blog/resize/1623221514-76x80.jpg",
                    "slug": "laboriosam-eligendi-repellendus-eos-recusandae-est-quo"
                },
                {
                    "id": 120,
                    "title": "Quis sequi placeat est.",
                    "image": "http://local.drone/images/blog/resize/1623221514-76x80.jpg",
                    "slug": "quasi-non-nihil-omnis-ipsam-atque"
                },
                {
                    "id": 119,
                    "title": "Dicta ipsum quam minima.",
                    "image": "http://local.drone/images/blog/resize/1623221514-76x80.jpg",
                    "slug": "architecto-et-reprehenderit-incidunt-omnis"
                },
                {
                    "id": 118,
                    "title": "Et aut et ipsa eveniet.",
                    "image": "http://local.drone/images/blog/resize/1623221514-76x80.jpg",
                    "slug": "veniam-commodi-velit-voluptatibus-totam-mollitia-vel"
                }
            ]

        }
      *
      * @response status=404 {
            "statusCode": 404,
            "message": "recent blog post not found!",
            "data": []
        }

    */
    public function recent(Request $request)
    {
        $blogs=Blog::query()
                 ->select('id', 'title', 'image', 'slug')
                 ->latest('id')
                 ->take(10)
                 ->get();
               

        if (!$blogs) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'recent blog post not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }
      

        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'recent blog post fetch successfully',
                         'data'=>$blogs
                     ]);
    }

    public function store(StoreBlogRequest $request)
    {
        $blog = Blog::create($request->all());

        return (new BlogResource($blog))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Single Blog Post.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response blog post not found!
     *
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response  {
            "statusCode": 200,
            "message": "blog post detail fetch successfully",
            "data": {
                "id": 71,
                "title": "Et deserunt voluptatibus quia vel rerum quis ut qui.",
                "image": "http://local.drone/images/blog/1623221514.jpg",
                "excerpt": "Aut repellat ut exercitationem amet repellendus pariatur culpa voluptatem. Soluta quis aspernatur eius corrupti sint sunt.",
                "description": "Ut a incidunt rerum debitis id dolorum. Ratione ipsa cumque explicabo libero asperiores earum quo. Odio error minus aliquam. Culpa eius incidunt quia temporibus porro quae. Id aspernatur nobis tempora ducimus id aut veritatis. Porro eveniet voluptas sit perferendis voluptatem. Sed dolorum quo beatae et provident dignissimos. Ullam consequatur doloremque consequatur animi nisi. Sapiente et aut voluptatem laudantium nesciunt. Totam eius fugit modi. Est sed et quo ab. Dolores quam et officiis eum officiis qui nisi. Qui ut sunt vitae nesciunt aut esse. Eius cum non quo.",
                "slug": "velit-suscipit-commodi-maiores-qui-et",
                "meta_keyword": "necessitatibus",
                "meta_description": "Aut excepturi ut optio ad culpa maiores laudantium. Fugiat cum et velit ex ea in fugit non.",
                "blog_categories": [
                    {
                        "title": "nisi"
                    },
                    {
                        "title": "Est deleniti ut reprehenderit facere qui et."
                    },
                    {
                        "title": "Illo sunt aut et sed aut enim."
                    },
                    {
                        "title": "Est deleniti ut reprehenderit facere qui et."
                    },
                    {
                        "title": "Rerum ullam vel recusandae deleniti necessitatibus et."
                    },
                    {
                        "title": "Category 7"
                    }
                ]
            }
            }
      *
      * @response status=404 {
            "statusCode": 404,
            "message": "blog post not found!",
            "data": []
        }

    */
    public function show(Request $request, $slug)
    {
        $blog=Blog::where('slug', $slug)->with(['blog_categories:title,slug'])
                ->select('id', 'title', 'image', 'excerpt', 'description', 'slug', 'meta_keyword', 'meta_description')
                ->first();


       
      
        if (!$blog) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'blog post not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        session()->forget('blog_session_value');
        $blogs=Blog::query()
                ->whereNotIn('id', [$blog->id])
                ->select('id', 'title', 'image', 'excerpt', 'description', 'slug')
                ->latest('id')
                ->chunk(3, fn ($q) =>session()->push('blog_session_value', $q));
                
        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'blog post detail fetch successfully',
                         'data'=>$blog,
                         'relatedBlogs'=>array_slice(session()->get('blog_session_value'), 0, 4),
                     ]);
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $blog->update($request->all());

        return (new BlogResource($blog))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Blog $blog)
    {
        abort_if(Gate::denies('blog_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blog->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Home Feature Blog Category.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response recent featured Category not found!
     *
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response  {
            "statusCode": 200,
            "message": "featured Category fetch successfully",
            "data": [
                {
                    "category": "Category 1",
                    "title": "Placeat rerum tempore est explicabo debitis repellendus.",
                    "image": "http:\/\/local.drone\/images\/blog\/1623221514.jpg",
                    "excerpt": "Sapiente magnam dolores illum totam qui. Velit sed est aliquam sed.",
                    "slug": "nesciunt-est-culpa-odio-possimus-ut"
                },
                {
                    "category": "Category 10",
                    "title": "Et aut et ipsa eveniet.",
                    "image": "http:\/\/local.drone\/images\/blog\/1623221514.jpg",
                    "excerpt": "Similique et voluptate alias explicabo ab. Veritatis corrupti autem nihil recusandae omnis consectetur eius.",
                    "slug": "veniam-commodi-velit-voluptatibus-totam-mollitia-vel"
                }
            ]

        }
      *
      * @response status=404 {
            "statusCode": 404,
            "message": "featured Category not found!",
            "data": []
        }

    */

    public function featuredHomeCategory(Request $request)
    {
        $blogs=Blog::query()
                 ->with(['blog_categories'])
                 ->select('id', 'title', 'image', 'excerpt', 'description', 'slug')
                 ->latest()
                 ->homeFeatured()
                 ->get();

        if ($blogs->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'featured Category not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }
                
        $cat_arr=[];

        $blog_arr=[];
        foreach ($blogs as $key => $blog) {
            if (!in_array(@$blog->blog_categories[0]->id, $cat_arr)) {
                $cat_arr[]=@$blog->blog_categories[0]->id;
                $blog_arr[]=[
                    'category'=>$blog->blog_categories[0]->title,
                    'title'=>$blog->title,
                    'image'=>$blog->image,
                    'excerpt'=>$blog->excerpt,
                    'slug'=>$blog->slug,
                ];
            }
        }

        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'featured Category fetch successfully',
                         'data'=>$blog_arr
                     ]);
    }

    /**
     * Home Feature Blog Post.
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response recent featured blog post not found!
     *
     *
     * @param \Illuminate\Http\Request  $request
     * @param $id required
     * @return \Illuminate\Http\Response
     *
     * @response  {
             "statusCode": 200,
            "message": "featured blog post fetch successfully",
            "data": [
                {
                    "id": 113,
                    "title": "Placeat rerum tempore est explicabo debitis repellendus.",
                    "image": "http:\/\/local.drone\/images\/blog\/1623221514.jpg",
                    "excerpt": "Sapiente magnam dolores illum totam qui. Velit sed est aliquam sed.",
                    "description": "<p>Error est quo cumque. Amet est qui iste dolor laborum. Quia asperiores assumenda labore dolor. Ut sed nulla et quasi et voluptatem aut. Illum incidunt libero ratione et nobis nulla. Fugit quisquam et dolores occaecati dolorem. Ut consectetur laboriosam voluptate sit nesciunt accusantium. Molestiae voluptatem iste omnis culpa culpa. Fugit est ipsa voluptas omnis odio ut hic. Dolores aut ut reprehenderit numquam sunt. Sequi vero necessitatibus deleniti libero soluta accusantium velit.<\/p>",
                    "slug": "nesciunt-est-culpa-odio-possimus-ut"
                },
                {
                    "id": 114,
                    "title": "Totam excepturi officiis sit excepturi dolorem animi quia corporis.",
                    "image": "http:\/\/local.drone\/images\/blog\/1623221514.jpg",
                    "excerpt": "Illum facere unde optio eligendi iusto aut voluptatem. Tempore ut cum sunt numquam vitae.",
                    "description": "<p>Nulla voluptate facilis reprehenderit fugiat fugiat. Rem et quia est et quae nesciunt dicta consectetur. Nesciunt ipsa sit libero non et amet eligendi. Repellendus consequuntur expedita quaerat expedita sint dolores sapiente. Alias eum eum sint cum et voluptates. Beatae voluptatem nostrum aperiam suscipit. Necessitatibus ad id sit hic rerum culpa ratione.<\/p>",
                    "slug": "amet-qui-sed-aut-asperiores"
                },
                {
                    "id": 115,
                    "title": "Praesentium omnis soluta modi magni facere cum.",
                    "image": "http:\/\/local.drone\/images\/blog\/1623221514.jpg",
                    "excerpt": "Occaecati voluptas unde vitae. Totam explicabo rerum consequuntur.",
                    "description": "<p>Distinctio error quia quos aut. Molestiae neque ea autem et autem facere. In et quo sint ut facere omnis saepe maxime. Commodi nostrum delectus quos autem cum qui doloremque. Ullam tenetur maiores rerum voluptatem. Cum dolor sit et iusto suscipit ipsum est. Modi impedit aut unde voluptatem id molestias ut. Facere non et nesciunt ea. A necessitatibus corrupti aut quia voluptatem labore. Ut at odio voluptas ab.<\/p>",
                    "slug": "adipisci-ab-nisi-dicta-laudantium"
                },
                {
                    "id": 117,
                    "title": "Odio est nam inventore et explicabo a.",
                    "image": "http:\/\/local.drone\/images\/blog\/1623221514.jpg",
                    "excerpt": "Ipsa ad architecto cum minus ut accusantium sapiente. Eum mollitia dignissimos corporis.",
                    "description": "<p>Consequatur laborum voluptas dicta excepturi qui. Dolorum ab id perspiciatis perspiciatis. Et nobis ex rerum quaerat eligendi consequatur. Voluptate incidunt et itaque qui aut. Itaque ut animi est omnis sint. Voluptatem aut voluptatem dolorem aliquid. Veritatis aut eligendi est culpa molestias delectus. Recusandae eligendi aut aut cupiditate. Quam placeat quis illo porro id. Voluptatem voluptatem totam impedit et sint vel nihil porro. Aut minus mollitia rem facilis ex fuga. Optio possimus molestiae voluptatem doloremque exercitationem reiciendis quibusdam. Id voluptas assumenda aut nisi rem placeat.<\/p>",
                    "slug": "vel-magnam-iusto-explicabo-deleniti-veniam-dignissimos"
                }
            ]
        }
      *
      * @response status=404 {
            "statusCode": 404,
            "message": "featured blog post not found!",
            "data": []
        }

    */
    public function home(Request $request)
    {
        $blogs=Blog::query()
                 ->select('id', 'title', 'image', 'excerpt', 'description', 'slug')
                 ->latest()
                 ->homeFeatured()
                 ->take(4)
                 ->get();

        if ($blogs->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'featured blog post not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'featured blog post fetch successfully',
                         'data'=>$blogs
                     ]);
    }


    public function homeFeatured(Request $request)
    {
        $category_1=BlogCategory::query()
                 ->promoted()
                 ->first();

        $category_2=BlogCategory::query()
                 ->promoted()
                 ->skip(1)
                 ->first();

        $category_4= BlogCategory::where(['title'=>'Product News'])->first();

        //dd($category_4);

        if (!$category_2 && !$category_1) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'featured Category not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

    
        $category_1_blog_first=Blog::whereHas('blog_categories', function ($q) use ($category_1) {
            $q->where('blog_category_id', @$category_1->id);
        })->orderBy('id','DESC')->first();
        $category_1_blog=Blog::whereHas('blog_categories', function ($q) use ($category_1) {
            $q->where('blog_category_id', @$category_1->id);
        })->orderBy('id','DESC')->skip(1)->take(2)->get();

        
        $category_2_blog_first=Blog::whereHas('blog_categories', function ($q) use ($category_2) {
            $q->where('blog_category_id', @$category_2->id);
        })->orderBy('id','DESC')->first();

        $category_2_blog=Blog::whereHas('blog_categories', function ($q) use ($category_2) {
            $q->where('blog_category_id', @$category_2->id);
        })->orderBy('id','DESC')->skip(1)->take(2)->get();


        $category_4_blog_first=Blog::whereHas('blog_categories', function ($q) use ($category_4) {
            $q->where('blog_category_id', @$category_4->id);
        })->first();

        $category_4_blog=Blog::whereHas('blog_categories', function ($q) use ($category_4) {
            $q->where('blog_category_id', @$category_4->id);
        })->skip(1)->take(2)->get();



        $category_3_blog_first=PilotJob::latest()->first();

        $category_3_blog=PilotJob::query()
                     ->select(
                         'id',
                         'job_title',
                         'slug',
                         'job_description',
                         'status',
                         'created_at'
                     )
                     ->with(['location:city,state,country,pilot_job_id'])
                     ->approvedJob()
                     ->latest()
                     ->take(2)
                     ->get();

      
    
        $data=[];
        $data['category_1']=@$category_1->title;
        $data['category_1_title']=@$category_1_blog_first->title;
        $data['category_1_title_slug']=@$category_1_blog_first->slug;
        
        $data['category_1_image']=@$category_1_blog_first->image;
        $data['category_1_short_descrption']=@$category_1_blog_first->excerpt;
        $data['category_1_blogs']=$category_1_blog;

        $data['category_2']=@$category_2->title;
        $data['category_2_title']=@$category_2_blog_first->title;
        $data['category_2_title_slug']=@$category_2_blog_first->slug;
        $data['category_2_image']=@$category_2_blog_first->image;
        $data['category_2_short_descrption']=@$category_2_blog_first->excerpt;
        $data['category_2_blogs']=$category_2_blog;


        $data['category_4']=@$category_4->title;
        $data['category_4_title']=@$category_4_blog_first->title;
        $data['category_4_title_slug']=@$category_4_blog_first->slug;
        $data['category_4_image']=@$category_4_blog_first->image;
        $data['category_4_short_descrption']=@$category_4_blog_first->excerpt;
        $data['category_4_blogs']=$category_4_blog;

        $data['category_3']='Latest Jobs';
        $data['category_3_title']=@$category_3_blog_first->job_title;
        $data['category_3_title_slug']=@$category_3_blog_first->slug;
        $data['category_3_image']='';
        $data['category_3_short_descrption']=@$category_3_blog_first->job_description;
        $data['category_3_blogs']=$category_3_blog;



        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'featured Category fetch successfully',
                         'data'=>$data
                     ]);
    }

    /**
     * Blog sitemap
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response recent featured blog post not found!
     *


    */
    public function sitemap(Request $request)
    {
        $blogs=Blog::query()
                ->select('id', 'slug', 'updated_at', 'title')
                ->latest('id')
                ->get();

        $model=[];
        foreach ($blogs as $key => $value) {
            $model[]=[
                'title'=>$value->title,
                'slug'=>$value->slug,
                'lastModified'=>$value->updated_at->format('Y-m-d'),
           ];
        }

        if (!$blogs) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'blog post not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }
        

        return response()->json([
                         'statusCode'=>Response::HTTP_OK,
                         'message' => 'blog post fetch successfully',
                         'data'=>[
                            'data'=>$model,
                         ],
                     ]);
    }
}
