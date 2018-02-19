<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Lead;

use Response;

use DB;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postInput = file_get_contents('php://input');
        $data = json_decode($postInput, true);

        $lead = new Lead();
        $lead->fill([
            "offer_id" => $data['offer_id'],
            "stream_id" => $data['stream_id'],
            "tuser_id" => $data['user_id'],
            "name" => $data['name'],
            "phone" => $data['phone'],
            "tz" => $data['tz'],
            "address" => $data['address'],
            "country" => $data['country'],
            "utm_source" => $data['utm_source'],
            "utm_medium" => $data['utm_medium'],
            "utm_campaign" => $data['utm_campaign'],
            "utm_term" => $data['utm_term'],
            "utm_content" => $data['utm_content'],
            "check_sum" => $data['check_sum']
        ]);
        $lead->save();

        return response()->json(['success' => 'success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function postback(Request $request)
    {
        $postInput = file_get_contents('php://input');
        $data = json_decode($postInput, true);

        $lead = DB::table('leads')
            ->where('check_sum', $_GET['check_sum'])
            ->update([
                "tleads_id" => $data['id'],
                "status" => $data['status'],
                "cost" => $data['cost'],
                "comment" => $data['comment'],
                "action" => $data['action'],
                "fields" => $data['fields'],
                "date_create" => $data['date_create'],
                "country" => $data['country'],
                "stream_id" => $data['stream_id'],
                "utm_source" => $data['utm_source'],
                "utm_medium" => $data['utm_medium'],
                "utm_campaign" => $data['utm_campaign'],
                "utm_term" => $data['utm_term'],
                "utm_content" => $data['utm_content'],
                "sub_id" => $data['sub_id'],
                "sub_id_1" => $data['sub_id_1'],
                "sub_id_2" => $data['sub_id_2'],
                "sub_id_3" => $data['sub_id_3'],
                "sub_id_4" => $data['sub_id_4'],
                "ip" => $data['ip'],
                "user_agent" => $data['user_agent']
            ]);

        return response()->json(['success' => 'success'], 200);
    }


    public function status()
    {
        $id = '2504258';
        $api_key = 'b47ec732429765224d24b4e661bfca1e';

        $data = array(
            'id' => $id,
            'check_sum' => sha1($id . $api_key)
        );

        $response = self::post_request('http://tl-api.com/api/lead/status', json_encode($data));

        // return dd($response);

        if ($response['http_code'] == 200 && $response['errno'] === 0) {
            $body = json_decode($response['result'], true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $body;
            } else {
                throw new Exception('JSON response error');
            }
        } else {
            if (!empty($response['result'])) {
                $result = json_decode($response['result']);
                throw new Exception($result->error);
            } else {
                throw new Exception('HTTP request error. ' . $response['error']);
            }
        }
    }


    // public function tl_create($params)
    public function tl_create()
    {

        $params = [
            'offer_id' => '1652',
            'stream_id' => '32520',
            'user_id' => '4104',
            'name' => '1',
            'phone' => '1',
            'api_key' => 'b47ec732429765224d24b4e661bfca1e'
        ];

        $data = array(
            'offer_id' => $params['offer_id'],
            'stream_id' => $params['stream_id'],
            'user_id' => $params['user_id'],
            'name' => $params['name'],
            'phone' => $params['phone'],
            'tz' => isset($params['tz']) ? $params['tz'] : '',
            'address' => isset($params['address']) ? $params['address'] : '',
            'country' => empty($params['country']) ? '' : $params['country'],
            'utm_source' => isset($params['utm_source']) ? $params['utm_source'] : '',
            'utm_medium' => isset($params['utm_medium']) ? $params['utm_medium'] : '',
            'utm_campaign' => isset($params['utm_campaign']) ? $params['utm_campaign'] : '',
            'utm_term' => isset($params['utm_term']) ? $params['utm_term'] : '',
            'utm_content' => isset($params['utm_content']) ? $params['utm_content'] : ''
        );

        $data['check_sum'] = sha1(
            $params['user_id'] .
            $params['offer_id'] .
            $data['name'] .
            $data['phone'] .
            $params['api_key']
        );

        $url_get = http_build_query(array_filter($_GET, function ($k) {
            return $k != in_array($params[$k], array('utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'sub_id', 'sub_id_1', 'sub_id_2', 'sub_id_3', 'sub_id_4'));
        }));

        $response = self::post_request('http://tl-api.com/api/lead/create' . '?' . $url_get, json_encode($data));

        if ($response['http_code'] == 200 && $response['errno'] === 0) {
            header('Location: success.html');
        } else {
            if (!empty($response['result'])) {
                $result = json_decode($response['result']);
                throw new Exception($result->error);
            } else {
                throw new Exception('HTTP request error. ' . $response['error']);
            }
        }
    }


    static function post_request($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        $curl_error = curl_error($ch);
        $curl_errno = curl_errno($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $response = array(
            'error' => $curl_error,
            'errno' => $curl_errno,
            'http_code' => $http_code,
            'result' => $result,
        );

        return $response;
    }

}