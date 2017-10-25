<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    public $timestamps = false;
    //
    protected $table = 'sms';

    protected $fillable = ['msisdn', 'to', 'messageId', 'text', 'type', 'keyword', 'message-timestamp', 'timestamp'];
}
