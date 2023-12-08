<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Image;
use App\Models\Event;
use App\Models\EventGallery;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\MassDestroyEventRequest;
use Storage;
use Illuminate\Support\Facades\File;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('event_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Event::select(sprintf('%s.*', (new Event())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'event_show';
                $editGate = 'event_edit';
                $deleteGate = 'event_delete';
                $crudRoutePart = 'events';

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
                return $row->status ? @Event::STATUS_SELECT[$row->status] : 'DRAFT';
            });
            $table->editColumn('location', function ($row) {
                return $row->street_add.', '.$row->suite.' '.$row->city.', '.$row->state;
            });
            /*$table->editColumn('blog_category', function ($row) {
                $labels = [];
                foreach ($row->blog_categories as $blog_category) {
                    $labels[] = sprintf('<span class="badge badge-primary">%s</span>', $blog_category->title);
                }

                return implode(' ', $labels);
            });*/

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        $events = Event::with(['user'])->latest('id')->get();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $users = User::whereHas('roles', function ($q) {
        //     $q->whereIn('id', [4, 3]);
        // })->active()->get();
        $users = User::active()->get();
        return view('admin.events.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        abort_if(Gate::denies('event_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data=$req->except(['image', 'gallery_img_1', 'gallery_img_2', 'gallery_img_3', 'gallery_img_4', 'gallery_img_5', 'gallery_img_6']);
        $event = Event::create($data);
        $event_id = $event->id;

        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $original_image_size = getimagesize($image);
            list($width, $height) = getimagesize($image);
            $sizes_arr = [
                "915,686", //detail
                "520,390",  // list page
                "160,160",  //sidebar
            ];
            $time = time();
            $image_name = $time . '.' . $image->getClientOriginalExtension();
            $destinationPathRT = public_path('images/events/' .$event_id);
            $destinationPath = public_path('images/events/' .$event_id. '/resize');
            if(!File::isDirectory($destinationPathRT)){
                File::makeDirectory($destinationPathRT, 0777, true, true);
            }
            if(!File::isDirectory($destinationPath)){
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777);
                chmod(public_path('images/events/' .$event_id), 0777, true);
                chmod($destinationPath, 0777);
            }

            foreach ($sizes_arr as $size_value) {
                $resize_image = Image::make($image->getRealPath());
                $array = explode(',', $size_value);
                $new_width = $array[0];
                $new_height = $array[1];
                $new_image_name = $time . '-' . $new_width . 'x' . $new_height . '.' . $image->getClientOriginalExtension();

                if ($new_width == '160' && $new_height == '160' ) {
                    $resize_image->fit($new_width, $new_height)->save($destinationPath . '/' . $new_image_name);
                }else{
                    $resize_image->resize($new_width, $new_height, function ($constraint) use ($new_width, $new_height) {
                        $constraint->aspectRatio();
                    })->save($destinationPath . '/' . $new_image_name);
                }

            }
            $allConfig = public_path('images/events/' .$event_id);
            $event = Event::find($event_id);

            $req->file('image')->move($allConfig, $image_name);
            $event->image = 'images/events/' .$event_id.'/'.$image_name;
            $event->save();

        }

        if ($req->hasFile('gallery_img_1')) {
            $allConfig ='images/events/' .$event_id;
            $event = Event::find($event_id);
            $event->gallery_img_1=Storage::disk('public_uploads')->put($allConfig, $req->gallery_img_1);
            $event->save();
        }
        if ($req->hasFile('gallery_img_2')) {
            $allConfig ='images/events/' .$event_id;
            $event = Event::find($event_id);
            $event->gallery_img_2=Storage::disk('public_uploads')->put($allConfig, $req->gallery_img_2);
            $event->save();
        }
        if ($req->hasFile('gallery_img_3')) {
            $allConfig ='images/events/' .$event_id;
            $event = Event::find($event_id);
            $event->gallery_img_3=Storage::disk('public_uploads')->put($allConfig, $req->gallery_img_3);
            $event->save();
        }
        if ($req->hasFile('gallery_img_4')) {
            $allConfig ='images/events/' .$event_id;
            $event = Event::find($event_id);
            $event->gallery_img_4=Storage::disk('public_uploads')->put($allConfig, $req->gallery_img_4);
            $event->save();
        }
        if ($req->hasFile('gallery_img_5')) {
            $allConfig ='images/events/' .$event_id;
            $event = Event::find($event_id);
            $event->gallery_img_5=Storage::disk('public_uploads')->put($allConfig, $req->gallery_img_5);
            $event->save();
        }
        if ($req->hasFile('gallery_img_6')) {
            $allConfig ='images/events/' .$event_id;
            $event = Event::find($event_id);
            $event->gallery_img_6=Storage::disk('public_uploads')->put($allConfig, $req->gallery_img_6);
            $event->save();
        }

        return redirect()->route('admin.events.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        abort_if(Gate::denies('event_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        abort_if(Gate::denies('event_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $users = User::whereHas('roles', function ($q) {
        //     $q->where('id', 4);
        // })->active()->get();
        $users = User::active()->get();
        return view('admin.events.edit', compact('event', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, Event $event)
    {
        $data=$req->except(['image', 'gallery_img_1', 'gallery_img_2', 'gallery_img_3', 'gallery_img_4', 'gallery_img_5', 'gallery_img_6']);
        $event->update($data);
        $event_id = $event->id;

        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $original_image_size = getimagesize($image);
            list($width, $height) = getimagesize($image);
            $sizes_arr = [
                "915,686", //detail
                "520,390",  // list page
                "160,160",  //sidebar
            ];
            $time = time();
            $image_name = $time . '.' . $image->getClientOriginalExtension();
            $destinationPathRT = public_path('images/events/' .$event_id);
            $destinationPath = public_path('images/events/' .$event_id. '/resize');
            if(!File::isDirectory($destinationPathRT)){
                File::makeDirectory($destinationPathRT, 0777, true, true);
            }
            if(!File::isDirectory($destinationPath)){
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $resize_image = Image::make($image->getRealPath());
            foreach ($sizes_arr as $size_value) {
                $array = explode(',', $size_value);
                $new_width = $array[0];
                $new_height = $array[1];
                $new_image_name = $time . '-' . $new_width . 'x' . $new_height . '.' . $image->getClientOriginalExtension();

                if ($new_width == '160' && $new_height == '160' ) {
                    $resize_image->fit($new_width, $new_height)->save($destinationPath . '/' . $new_image_name);
                }else{
                    $resize_image->resize($new_width, $new_height, function ($constraint) use ($new_width, $new_height) {
                        $constraint->aspectRatio();
                    })->save($destinationPath . '/' . $new_image_name);
                }
            }
            $allConfig = public_path('images/events/' .$event_id);
            $event = Event::find($event_id);

            $req->file('image')->move($allConfig, $image_name);
            $event->image = 'images/events/' .$event_id.'/'.$image_name;
            $event->save();
        }

        if ($req->hasFile('gallery_img_1')) {
            $allConfig ='images/events/' .$event_id;
            $event = Event::find($event_id);
            $event->gallery_img_1=Storage::disk('public_uploads')->put($allConfig, $req->gallery_img_1);
            $event->save();
        }
        if ($req->hasFile('gallery_img_2')) {
            $allConfig ='images/events/' .$event_id;
            $event = Event::find($event_id);
            $event->gallery_img_2=Storage::disk('public_uploads')->put($allConfig, $req->gallery_img_2);
            $event->save();
        }
        if ($req->hasFile('gallery_img_3')) {
            $allConfig ='images/events/' .$event_id;
            $event = Event::find($event_id);
            $event->gallery_img_3=Storage::disk('public_uploads')->put($allConfig, $req->gallery_img_3);
            $event->save();
        }
        if ($req->hasFile('gallery_img_4')) {
            $allConfig ='images/events/' .$event_id;
            $event = Event::find($event_id);
            $event->gallery_img_4=Storage::disk('public_uploads')->put($allConfig, $req->gallery_img_4);
            $event->save();
        }
        if ($req->hasFile('gallery_img_5')) {
            $allConfig ='images/events/' .$event_id;
            $event = Event::find($event_id);
            $event->gallery_img_5=Storage::disk('public_uploads')->put($allConfig, $req->gallery_img_5);
            $event->save();
        }
        if ($req->hasFile('gallery_img_6')) {
            $allConfig ='images/events/' .$event_id;
            $event = Event::find($event_id);
            $event->gallery_img_6=Storage::disk('public_uploads')->put($allConfig, $req->gallery_img_6);
            $event->save();
        }

        return redirect()->route('admin.events.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        abort_if(Gate::denies('event_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $event->delete();
        return back();
    }

    public function massDestroy(MassDestroyEventRequest $request)
    {
        Event::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
