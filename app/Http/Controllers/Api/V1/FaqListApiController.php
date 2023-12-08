<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFaqQuestionRequest;
use App\Http\Requests\UpdateFaqQuestionRequest;
use App\Http\Resources\Admin\FaqQuestionResource;
use App\Models\FaqList;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FaqListApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('faq_question_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new FaqQuestionResource(FaqQuestion::with(['category'])->get());
    }

    /**
     * Faq List
     *
     * If everything is okay, you'll get a `200` OK response with data.
     *
     * Otherwise, the request will fail with a `404` error, and a response recent featured blog post not found!
     *


    */

    public function allFaqList()
    {
        $faqlist = FaqList::query()
            ->select('id', 'question', 'answer')
            ->orderBy('id', 'DESC')
            ->get();

        if ($faqlist->isEmpty()) {
            return response()->json([
                'statusCode' =>404,
                'message' => 'FAQs not found!',
                'data' =>[]
            ])->setStatusCode(404);
        }

        return response()->json([
             'statusCode'=>Response::HTTP_OK,
             'message' => 'FAQs retrived successfully',
             'data'=>$faqlist
        ])->setStatusCode(Response::HTTP_OK);
    }
}
