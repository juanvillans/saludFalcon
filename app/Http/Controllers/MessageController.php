<?php

namespace App\Http\Controllers;

use App\Events\MessageCreated;
use Exception;
use Illuminate\Http\Request;
use App\Services\MessageService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CreateMessageRequest;

class MessageController extends Controller
{
    public function store(CreateMessageRequest $request){

        try {

            $messageService = new MessageService;
            $message = $messageService->create($request->validated());

            broadcast(new MessageCreated($message))->toOthers();

            return response()->json(['message' => $message, 'status' => true]);

        } catch (Exception $e) {

            Log::info('Error creando el mensaje: ' . $e->getMessage() . ' --- Linea: ' . $e->getLine());

            return response()->json(['message' => 'Algo salio mal: ' . $e->getMessage(), 'status' => false], 500);

        }
    }
}
