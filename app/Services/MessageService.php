<?php

namespace App\Services;

use Exception;
use App\Models\Messages;
use App\Models\EmergencyCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageService
{


    public function create($data)
    {
        return DB::transaction(function () use ($data) {

            try {

                $newMessage = Messages::create($data);
                EmergencyCase::where('id',$newMessage->emergency_case_id)
                ->update([
                    'last_message_id' => $newMessage->id,
                ]);

            } catch (Exception $e) {

                Log::error('MessageService -  Error al crear mensaje: '. $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;


            }

        });

    }

}
