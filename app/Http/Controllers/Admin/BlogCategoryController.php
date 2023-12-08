<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBlogCategoryRequest;
use App\Http\Requests\StoreBlogCategoryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Models\BlogCategory;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('blog_category_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = BlogCategory::query()->select(sprintf('%s.*', (new BlogCategory)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'blog_category_show';
                $editGate      = 'blog_category_edit';
                $deleteGate    = 'blog_category_delete';
                $crudRoutePart = 'blog-categories';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : "";
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? BlogCategory::STATUS_SELECT[$row->status] : '';
            });
            $table->editColumn('meta_keyword', function ($row) {
                return $row->meta_keyword ? $row->meta_keyword : "";
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.blogCategories.index');
    }

    public function create()
    {
        abort_if(Gate::denies('blog_category_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.blogCategories.create');
    }

    public function store(StoreBlogCategoryRequest $request)
    {
        $data=$request->all();

        if (empty($request->slug)) {
            $data['slug']=$this->generateUniqueSlug($request->title);
        } else {
            $data['slug']=$this->generateUniqueSlug($request->slug);
        }
        $blogCategory = BlogCategory::create($data);

        return redirect()->route('admin.blog-categories.index');
    }

    public function edit(BlogCategory $blogCategory)
    {
        abort_if(Gate::denies('blog_category_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.blogCategories.edit', compact('blogCategory'));
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blogCategory)
    {
        $data=$request->all();
    
        if (empty($request->slug)) {
            $data['slug']=$this->generateUniqueSlug($request->title, $blogCategory->id);
        } else {
            $data['slug']=$this->generateUniqueSlug($request->slug, $blogCategory->id);
        }
        $blogCategory->update($data);

        return redirect()->route('admin.blog-categories.index');
    }

    public function show(BlogCategory $blogCategory)
    {
        abort_if(Gate::denies('blog_category_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.blogCategories.show', compact('blogCategory'));
    }

    public function destroy(BlogCategory $blogCategory)
    {
        abort_if(Gate::denies('blog_category_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blogCategory->delete();

        return back();
    }

    public function massDestroy(MassDestroyBlogCategoryRequest $request)
    {
        BlogCategory::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }




    public function generateUniqueSlug($string, $modelId=null)
    {
        $slug=\Str::slug($string);
        if ($modelId!=null) {
            $model=BlogCategory::where('slug', $slug)
                            ->whereNotIn('id', [ $modelId])
                            ->first();
        } else {
            $model=BlogCategory::where('slug', $slug)->exists();
        }

        if ($model) {
            return $slug.'-1';
        }
        return $slug;
    }
}
