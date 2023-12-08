<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MassDestroyBlogRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Image;

class BlogController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('blog_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Blog::with(['blog_categories'])->select(sprintf('%s.*', (new Blog())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'blog_show';
                $editGate = 'blog_edit';
                $deleteGate = 'blog_delete';
                $crudRoutePart = 'blogs';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('title', function ($row) {
                return $row->title ? $row->title : '';
            });
            $table->editColumn('status', function ($row) {
                return $row->status ? @Blog::STATUS_SELECT[$row->status] : 'DRAFT';
            });
            $table->editColumn('blog_category', function ($row) {
                $labels = [];
                foreach ($row->blog_categories as $blog_category) {
                    $labels[] = sprintf('<span class="badge badge-primary">%s</span>', $blog_category->title);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'blog_category']);

            return $table->make(true);
        }

        return view('admin.blogs.index');
    }

    public function create()
    {
        abort_if(Gate::denies('blog_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blog_categories = BlogCategory::all()->pluck('title', 'id');

        return view('admin.blogs.create', compact('blog_categories'));
    }

    public function store(StoreBlogRequest $request)
    {
        $data=$request->all();
        //let's saved the image if exist
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            //get the image size
            $original_image_size=getimagesize($image);
            list($width, $height)=getimagesize($image);
             
            //let's build the size of array
            $sizes_arr=[
                "520,390",  // list page
                "160,160",  //sidebar
                "915,686", //detail
             ];
            $time=time();
            
            //original image
            $image_name =$time.'.' . $image->getClientOriginalExtension();

            //resize path directory
            $destinationPath = public_path('/images/blog/resize');

            $resize_image = Image::make($image->getRealPath());

            foreach ($sizes_arr as $size_value) {
                $array = explode(',', $size_value);
                $new_width = $array[0];
                $new_height = $array[1];
                $new_image_name =$time.'-'.$new_width.'x'.$new_height.'.'.$image->getClientOriginalExtension();
                $resize_image->resize($new_width, $new_height, function ($constraint) use ($new_width, $new_height) {
                    if ($new_width=='520' && $new_height=='390') {
                        $constraint->aspectRatio();
                    }
                })->save($destinationPath . '/' . $new_image_name);
            }
            //original path directory.
            $destinationPath = public_path('/images/blog');
            $image->move($destinationPath, $image_name);
            $data['image']='/images/blog/'.$image_name;
        }

        $blog = Blog::create($data);


        $blog->blog_categories()->sync($request->input('blog_categories', []));
        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $blog->id]);
        }

        return redirect()->route('admin.blogs.index');
    }

    public function edit(Blog $blog)
    {
        abort_if(Gate::denies('blog_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blog_categories = BlogCategory::all()->pluck('title', 'id');

        $blog->load('blog_categories');

        return view('admin.blogs.edit', compact('blog_categories', 'blog'));
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $data=$request->all();
        //let's saved the image if exist
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            //get the image size
            $original_image_size=getimagesize($image);
            list($width, $height)=getimagesize($image);
             
            $sizes_arr=[
                "520,390",  // list page
                "160,160",  //sidebar
                "915,686", //detail
             ];

            $time=time();
            
            //original image
            $image_name =$time.'.' . $image->getClientOriginalExtension();

            //resize path directory
            $destinationPath = public_path('/images/blog/resize');

            $resize_image = Image::make($image->getRealPath());

            foreach ($sizes_arr as $size_value) {
                $array = explode(',', $size_value);
                $new_width = $array[0];
                $new_height = $array[1];
                $new_image_name =$time.'-'.$new_width.'x'.$new_height.'.'.$image->getClientOriginalExtension();
                $resize_image->resize($new_width, $new_height, function ($constraint) use ($new_width, $new_height) {
                    if ($new_width=='520' && $new_height=='390') {
                        $constraint->aspectRatio();
                    }
                })->save($destinationPath . '/' . $new_image_name);
            }
            //original path directory.
            $destinationPath = public_path('/images/blog');
            $image->move($destinationPath, $image_name);
            $data['image']='/images/blog/'.$image_name;
        }

        $blog->update($data);
        
        $blog->blog_categories()->sync($request->input('blog_categories', []));

        return redirect()->route('admin.blogs.index');
    }

    public function show(Blog $blog)
    {
        abort_if(Gate::denies('blog_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blog->load('blog_categories');

        return view('admin.blogs.show', compact('blog'));
    }

    public function destroy(Blog $blog)
    {
        abort_if(Gate::denies('blog_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $blog->delete();

        return back();
    }

    public function massDestroy(MassDestroyBlogRequest $request)
    {
        Blog::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('blog_create') && Gate::denies('blog_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Blog();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
