<?php

namespace App\Http\Controllers;

use App\Adapters\MailerLiteAdapter;
use App\Http\Middleware\RequireApiKey;
use App\Http\Requests\StoreSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;
use App\Models\ApiKey;
use App\Services\MailerLite\MailerLiteException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SubscriberApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(RequireApiKey::class);
    }

    public function all(Request $request)
    {
        $api = ApiKey::first();
        $adapter = new MailerLiteAdapter($api->key);

        $total = $adapter->getTotalSubscribers();

        $draw = $request->query->get('draw');
        $start = $request->query->get('start');
        $length = $request->query->get('length') ?: 10;
        $search = $request->query->all('search');
        $nextUrl = $request->query->get('next');
        $prevUrl = $request->query->get('previous');
        $isNext = (int)$request->query->get('isNext');
        $isPrev = (int)$request->query->get('isPrev');

        $searchValue = "";
        if ($search) {
            $searchValue = $search['value'];
        }

        try {
            if ($searchValue) {
                $data = $adapter->searchByEmail($searchValue);
            } else {
                $cursor = "";
                $url = "";
                if ($isPrev) {
                    $url = parse_url($prevUrl);
                }
                if ($isNext) {
                    $url = parse_url($nextUrl);
                }
                if ($url) {
                    parse_str($url['query'], $query);

                    $cursor = $query['cursor'];
                }

                $data = $adapter->getSubscribers($length, $cursor);
            }

            return [
                'draw' => $draw,
                'search' => $searchValue,
                'length' => $length,
                'recordsFiltered' => $total,
                'recordsTotal' => $total,
                'start' => $start,
                'isNext' => $isNext,
                'isPrev' => $isPrev,

                'data' => $data['subscribers'],
                'pagination' => $data['pagination']
            ];
        } catch (MailerLiteException $exception) {
            return Response::json($exception->data, $exception->getCode());
        }
    }

    public function store(StoreSubscriberRequest $request)
    {
        $api = ApiKey::first();
        $adapter = new MailerLiteAdapter($api->key);

        try {
            $subscriber = $adapter->createSubscriber(
                $request->get('name'),
                $request->get('email'),
                $request->get('country')
            );

            return Response::json($subscriber->toArray(), 201);
        } catch (MailerLiteException $exception) {
            return Response::json($exception->data, $exception->getCode());
        }
    }

    public function update(UpdateSubscriberRequest $request, string $id)
    {
        $api = ApiKey::first();
        $adapter = new MailerLiteAdapter($api->key);

        try {
            $subscriber = $adapter->updateSubscriber(
                $id,
                $request->get('name'),
                $request->get('country')
            );

            return Response::json($subscriber->toArray(), 200);
        } catch (MailerLiteException $exception) {
            return Response::json($exception->data, $exception->getCode());
        }
    }

    public function delete(string $id)
    {
        $api = ApiKey::first();
        $adapter = new MailerLiteAdapter($api->key);

        try {
            $adapter->deleteSubscriber($id);

            return Response::noContent();
        } catch (MailerLiteException $exception) {
            return Response::json($exception->data, $exception->getCode());
        }
    }
}
