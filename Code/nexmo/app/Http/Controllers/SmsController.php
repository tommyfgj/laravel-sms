<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sms;

class SmsController extends Controller
{
    public function randomSpace() {
        $ret = '';
        for($i = 0; $i < rand(1, 10); $i++) {
            $ret .= ' ';
        }
        return $ret;
    }

    public function receive(Request $request) {
        $this->validate($request, [
            'msisdn' => 'required',
            'to' => 'required',
            'messageId' => 'required',
            'text' => 'required',
            'type' => 'required',
            'keyword' => 'required',
            'message-timestamp' => 'required',
        ]);

        $ts = time();
        $ret = Sms::create([
            'msisdn' => $request->msisdn,
            'to' => $request->to,
            'messageId' => base64_encode($ts.$request->{'messageId'}),
            'text' => base64_encode($request->text),
            'type' => $request->type,
            'keyword' => base64_encode($request->keyword),
            'message-timestamp' => $request->{'message-timestamp'},
            'timestamp' => time(),
        ]);

        $sendRet = $this->sc_send($request->msisdn."发给".$request->to, 
            $request->{'text'}.$this->randomSpace());

        if (!$ret || !$sendRet) {
            return 'fail';
        } else {
            return view('static_pages/empty');
        }
    }

    private function sc_send($text, $desp = '', $key = '') {
        $postdata = http_build_query(
            array(
                'text' => $text,
                'desp' => $desp
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        return $result = file_get_contents('https://sc.ftqq.com/'.
            $key.'.send', false, $context);
    }
}
