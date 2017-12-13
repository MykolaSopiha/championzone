<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
	protected $fillable = [
		"tleads_id",
		"offer_id",
		"stream_id",
		"tuser_id",
		"name",
		"phone",
		"tz",
		"address",
		"country",
		"check_sum",
		"status",
		"cost",
		"comment",
		"action",
		"fields",
		"date_create",
		"utm_source",
		"utm_medium",
		"utm_campaign",
		"utm_term",
		"utm_content",
		"sub_id",
		"sub_id_1",
		"sub_id_2",
		"sub_id_3",
		"sub_id_4",
		"ip",
		"user_agent",
	];
}
