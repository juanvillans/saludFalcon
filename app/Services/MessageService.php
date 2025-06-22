<?php

namespace App\Services;

use App\Models\EmergencyCase;
use App\Models\Messages;
use Illuminate\Support\Facades\DB;

class MessageService
{


    public function create($data)
    {
        DB::transaction(function () use ($data) {

            $newMessage = Messages::create($data);

            EmergencyCase::where('id',$newMessage->emergency_case_id)
            ->update([
                'last_message_id' => $newMessage->id,
            ]);

        });

    }

}
