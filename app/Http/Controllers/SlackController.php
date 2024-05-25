<?php

namespace App\Http\Controllers;

use App\Http\Handlers\SlackCommandHandler;
use App\Http\Handlers\VerificationHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SlackController extends Controller
{
    /**
     * @var SlackCommandHandler[]|array
     */
    protected array $handlers;

    /**
     * SlackController constructor.
     */
    public function __construct()
    {
        $this->handlers = [
            new SlackCommandHandler(),
            new VerificationHandler(),
        ];
    }

    /**
     * Handle the incoming webhook payload.
     *
     * @return Response
     */
    public function __invoke(Request $request)
    {

        foreach ($this->handlers as $handler) {
            if ($handler->shouldHandle($request->all())) {
                return $handler->handle($request->all());
            }
        }

        return response(['error' => 'No handler available for this request'], 400);
    }
}
