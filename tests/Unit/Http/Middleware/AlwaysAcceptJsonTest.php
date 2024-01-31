<?php

declare(strict_types=1);

use App\Http\Middleware\AlwaysAcceptJson;
use Illuminate\Http\Request;

it('sets the Accept header to application/json', function () {
    $request = new Request();
    $middleware = new AlwaysAcceptJson();

    $middleware->handle($request, function ($req) {
        expect($req->headers->get('Accept'))->toBe('application/json');
    });
});

it('overrides the Accept header if it is already set', function () {
    $request = new Request();
    $request->headers->set('Accept', 'text/html');
    $middleware = new AlwaysAcceptJson();

    $middleware->handle($request, function ($req) {
        expect($req->headers->get('Accept'))->toBe('application/json');
    });
});
