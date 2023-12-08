<?php

namespace App\Http\Controllers\Api\V1;

use Gate;
use Storage;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\Admin\EventResource;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\File;
use Image;

use Illuminate\Support\Facades\Mail;
use App\Mail\EventSubmitted;

class EventApiController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::query()->select('id', 'title', 'image', 'street_add', 'suite', 'city', 'state', 'description', 'slug', 'event_start', 'event_end', 'cost', 'created_at')->latest('id');
        if ($request->has('q')) {
            $events = $events->where('title', 'like', '%' . $request->input('q') . '%')->orWhere('description', 'like', '%' . $request->input('q') . '%');
        }
        $event_count = $events->count();
        if ($request->has('page')) {
            $page = $request->input('page');
            $page = $page - 1;
            $offset = $page * 10;
            $events = $events->offset($offset);
        }
        $events = $events->take(10)->get();
        if (!$events) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'Events not found!',
                'data' => []
            ])->setStatusCode(404);
        }
        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'Events fetched successfully',
            'post_count' => $event_count,
            'data' => [
                'current_page' => $request->input('page') ?? 1,
                'data' => $events,
                'per_page' => 10,
            ],
        ]);
    }

    public function recent(Request $request)
    {
        //
        $events = Event::query()->select('id', 'title', 'image', 'street_add', 'suite', 'city', 'state', 'description', 'slug', 'event_start', 'event_end', 'cost', 'created_at')->active()->latest('id');
        if($request->past != 'true'){
            $events->whereDate('event_start', '>', Carbon::now());
        }
        if(!empty($request->skip)){
            $events->skip($request->skip);
        }
        if(!empty($request->limit)){
            $events->take($request->limit);
        }
        $events = $events->get();
        $events_data = [];
        foreach($events as $key => $event){
            $events_data[$key]['id'] = $event->id;
            $events_data[$key]['title'] = $event->title;
            $events_data[$key]['image'] = $event->image_thumb;
            $events_data[$key]['location'] = $event->street_add.', '.$event->suite.' '.$event->city.', '.$event->state;
            $events_data[$key]['description'] = $event->description;
            $events_data[$key]['event_start'] = Carbon::parse($event->event_start)->format('jS F Y H:iA');
            $events_data[$key]['event_end'] = Carbon::parse($event->event_end)->format('jS F Y H:iA');
            $events_data[$key]['event_start_cal'] = $event->event_start;
            $events_data[$key]['event_end_cal'] = $event->event_end;
            $events_data[$key]['is_past'] = Carbon::parse($event->event_start)->isPast();
            $events_data[$key]['cost'] = $event->cost;
            $events_data[$key]['created_at'] = $event->created_at;
            $events_data[$key]['slug'] = $event->slug;
        }
        if (!$events) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'Recent event not found!',
                'data' => []
            ])->setStatusCode(404);
        }

        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'Events fetched successfully',
            'data' => $events_data
        ]);
    }

    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Event::select('slug')->where('slug', 'like', $slug.'%')->where('id', '<>', $id)->get();
    }

    public function createSlug($title, $id = 0)
    {
        $slug = \Str::slug($title);
        $allSlugs = $this->getRelatedSlugs($slug, $id);
        if (! $allSlugs->contains('slug', $slug)){
            return $slug;
        }
        $i = 2;
        $is_contain = true;
        do {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                $is_contain = false;
                return $newSlug;
            }
            $i++;
        } while ($is_contain);
    }

    public function store(Request $request)
    {
        $data = $request->except('image', 'gallery_img_1', 'gallery_img_2', 'gallery_img_3', 'gallery_img_4', 'gallery_img_5', 'gallery_img_6');
        $slug= $this->createSlug($request->title);
        $slug_exist = Event::where('slug', $slug)->get()->count();
        $data['slug'] = ($slug_exist == 0) ? $slug : $slug.'-'.$slug_exist+1;
        $data = array_map(fn($v) => $v === 'null' ? null : $v, $data);
        $user = User::find($request->user_id);
        if($user->stripe_id == ''){
            $user->createAsStripeCustomer();
        }
        $event = Event::create($data);
        if (preg_match('/^data:image\/(\w+);base64,/', $request->image)) {
            $data = substr($request->image, strpos($request->image, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->image);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->image);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $time = time();
            $file = 'ev1'. $time . '.' . $extension;
            $allConfig ='images/events/' .$event->id. '/' . $file;

            $sizes_arr = [
                "915,686", //detail
                "520,390",  // list page
                "160,160",  //sidebar
            ];

            $image_name = 'ev1'. $time . '.' . $extension;
            $destinationPathRT = public_path('images/events/' .$event->id);
            $destinationPath = public_path('images/events/' .$event->id. '/resize');
            if(!File::isDirectory($destinationPathRT)){
                File::makeDirectory($destinationPathRT, 0777, true, true);
            }
            if(!File::isDirectory($destinationPath)){
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777);
                chmod(public_path('images/events/' .$event->id), 0777, true);
                chmod($destinationPath, 0777);
            }

            foreach ($sizes_arr as $size_value) {
                $resize_image = Image::make($data);
                $array = explode(',', $size_value);
                $new_width = $array[0];
                $new_height = $array[1];
                $new_image_name = 'ev1'. $time . '-' . $new_width . 'x' . $new_height . '.' . $extension;

                if ($new_width == '160' && $new_height == '160' ) {
                    $resize_image->fit($new_width, $new_height)->save($destinationPath . '/' . $new_image_name);
                }else{
                    $resize_image->resize($new_width, $new_height, function ($constraint) use ($new_width, $new_height) {
                        $constraint->aspectRatio();
                    })->save($destinationPath . '/' . $new_image_name);
                }

            }
            Storage::disk('public_uploads')->put($allConfig, $data);
            $event_update = Event::find($event->id);
            $event_update->image = $allConfig;
            $event_update->save();
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->gallery_img_1)) {
            $data = substr($request->gallery_img_1, strpos($request->gallery_img_1, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->gallery_img_1);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->gallery_img_1);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'ev2'.time() . '.' . $extension;
            $allConfig ='images/events/' .$event->id. '/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $event_update = Event::find($event->id);
            $event_update->gallery_img_1 = $allConfig;
            $event_update->save();
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->gallery_img_2)) {
            $data = substr($request->gallery_img_2, strpos($request->gallery_img_2, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->gallery_img_2);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->gallery_img_2);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'ev3'.time() . '.' . $extension;
            $allConfig ='images/events/' .$event->id. '/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $event_update = Event::find($event->id);
            $event_update->gallery_img_2 = $allConfig;
            $event_update->save();
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->gallery_img_3)) {
            $data = substr($request->gallery_img_3, strpos($request->gallery_img_3, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->gallery_img_3);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->gallery_img_3);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'ev4'.time() . '.' . $extension;
            $allConfig ='images/events/' .$event->id. '/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $event_update = Event::find($event->id);
            $event_update->gallery_img_3 = $allConfig;
            $event_update->save();
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->gallery_img_4)) {
            $data = substr($request->gallery_img_4, strpos($request->gallery_img_4, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->gallery_img_4);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->gallery_img_4);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'ev5'.time() . '.' . $extension;
            $allConfig ='images/events/' .$event->id. '/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $event_update = Event::find($event->id);
            $event_update->gallery_img_4 = $allConfig;
            $event_update->save();
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->gallery_img_5)) {
            $data = substr($request->gallery_img_5, strpos($request->gallery_img_5, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->gallery_img_5);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->gallery_img_5);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'ev6'.time() . '.' . $extension;
            $allConfig ='images/events/' .$event->id. '/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $event_update = Event::find($event->id);
            $event_update->gallery_img_5 = $allConfig;
            $event_update->save();
        }

        if (preg_match('/^data:image\/(\w+);base64,/', $request->gallery_img_6)) {
            $data = substr($request->gallery_img_6, strpos($request->gallery_img_6, ',') + 1);
            $data = base64_decode($data);
            $imgforext = explode(',', $request->gallery_img_6);
            $ini = substr($imgforext[0], 11);
            $type = explode(';', $ini);
            $extension = $type[0]; // results extension
            $img = str_replace('data:image/' . $extension . ';base64,', '', $request->gallery_img_6);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = 'ev7'.time() . '.' . $extension;
            $allConfig ='images/events/' .$event->id. '/' . $file;
            Storage::disk('public_uploads')->put($allConfig, $data);
            $event_update = Event::find($event->id);
            $event_update->gallery_img_6 = $allConfig;
            $event_update->save();
        }


        /*return response()->json([
            'statusCode'=>Response::HTTP_OK,
            'message' => 'Please submit payment to publish the event!',
            'data'=>$getUser,
            'access_token' => $authToken,
        ])->setStatusCode(Response::HTTP_OK);*/

        return (new EventResource($event))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Request $request, $slug)
    {
        $event = Event::where('slug', $slug)->first();

        if (!$event) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'Event not found!',
                'data' => []
            ])->setStatusCode(404);
        }

        $event->image = asset($event->image_large);
        $event->event_start = Carbon::parse($event->event_start)->format('jS F Y H:iA');
        $event->event_end = Carbon::parse($event->event_end)->format('jS F Y H:iA');
        $event->gallery_img_1 = $event->gallery_img_1 ? asset($event->gallery_img_1) : '';
        $event->gallery_img_2 = $event->gallery_img_2 ? asset($event->gallery_img_2) : '';
        $event->gallery_img_3 = $event->gallery_img_3 ? asset($event->gallery_img_3) : '';
        $event->gallery_img_4 = $event->gallery_img_4 ? asset($event->gallery_img_4) : '';
        $event->gallery_img_5 = $event->gallery_img_5 ? asset($event->gallery_img_5) : '';
        $event->gallery_img_6 = $event->gallery_img_6 ? asset($event->gallery_img_6) : '';

        session()->forget('event_session_value');
        $events = Event::query()
            ->whereNotIn('id', [$event->id])
            ->select('id', 'title', 'image', 'street_add', 'suite', 'city', 'state', 'event_start', 'event_end', 'cost', 'slug')
            ->latest('id')
            ->chunk(3, fn ($q) => session()->push('event_session_value', $q));

        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'Event detail fetched successfully',
            'data' => $event,
            'relatedEvents' => is_array(session()->get('event_session_value')) ? array_slice(session()->get('event_session_value'), 0, 4) : new Event,
        ]);
    }

    public function update(UpdateBlogRequest $request, Event $event)
    {
        $event->update($request->all());
        return (new EventResource($event))->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Event $event)
    {
        abort_if(Gate::denies('event_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $event->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function home(Request $request)
    {
        $events = Event::query()->select('id', 'title', 'image', 'street_add', 'suite', 'city', 'state', 'description', 'slug', 'event_start', 'event_end', 'cost', 'created_at')->active()->latest()->homeFeatured()->take(4)->get();
        if ($blogs->isEmpty()) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'featured events not found!',
                'data' => []
            ])->setStatusCode(404);
        }
        return response()->json([
            'statusCode' => Response::HTTP_OK,
            'message' => 'featured events fetched successfully',
            'data' => $blogs
        ]);
    }



    public function stripeEventPayment(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'user_id' => ['required','exists:users,id','numeric'],
            'stripe_pm_id'=>['required'],
            'plan_id'=>['required'],
            'plan_price'=>['required'],
            'event_id'=>['required'],
            'coupon_code'=>['nullable'],
        ]);
        if ($validation->fails()) {
            return response()->json([
                'statusCode' => Response::HTTP_BAD_REQUEST,
                'message' => $validation->messages()->first(),
                'data' => $validation->messages(),
            ])->setStatusCode(Response::HTTP_BAD_REQUEST);
        }
        $user=User::where('id', $request->user_id)->first();
        //$user->createAsStripeCustomer();
        $pay=$user->createSetupIntent();
        $plan_name=Plan::EVENT_PLAN_DETAIL_FROM_STRIPE[$request->plan_id];
        $plan_price = Plan::EVENT_PLAN_PRICE[$request->plan_id];
        $amount = ($request->plan_price < $plan_price) ? $request->plan_price : $plan_price;

        try {
            if($amount > 0){
                $payment_details = $user->charge($amount*100, $request->input('stripe_pm_id'),
                    ['off_session' => true, 'description' => 'Event Submission Fees for :'.$request->title, 'customer' => $user->stripe_id, 'metadata' => array('event_id' => $request->event_id )]
                );
                $payment_info = ['id' => $payment_details->id, 'customer' => $payment_details->customer, 'coupon_code' => $request->coupon_code, 'description' => $payment_details->description, 'payment_method' => $payment_details->payment_method, 'status' => $payment_details->status,	'created' => gmdate("Y-m-d\TH:i:s\Z", $payment_details->created)];
                if($payment_details->status == 'succeeded'){
                    $events = Event::find($request->event_id);
                    $events->update(['status' => '2', 'payment_info'=> json_encode($payment_info)]);
                }
            }else{
                $payment_info = ['customer' => $user->stripe_id, 'coupon_code' => $request->coupon_code, 'description' => 'Event Submission Fees for :'.$request->title, 'payment_method' => 'Zero fee with coupon code', 'created' => now()];
                $events = Event::find($request->event_id);
                $events->update(['status' => '2', 'payment_info'=> json_encode($payment_info)]);
            }

            try {
                Mail::to('stuart@thedroningcompany.com')->send(new EventSubmitted($events));
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
            }

            return response()->json([
                'statusCode'=>Response::HTTP_OK,
                'message' => 'Event payment successfully completed',
                'data'=> $payment_info,
            ])->setStatusCode(Response::HTTP_OK);
        }catch (\Exception $e) {
            return response()->json([
                'statusCode'=>500,
                'message' =>$e->getMessage(),
                'data'=>$e->getMessage(),
            ])->setStatusCode(500);
        }
    }

}
