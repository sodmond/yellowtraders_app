<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $fillable = [
        'trader_id', 'message',
    ];

    public function changeStatus($data)
    {
        $conv_data = [];
        foreach($data as $note){
            if ($note['status'] == 1) {
                $conv_data[] = array_replace($note, ['status' => 'read']);
                continue;
            }
            $conv_data[] = array_replace($note, ['status' => 'unread']);
        }
        return $conv_data; //['message' => 'Done'];
    }
}
