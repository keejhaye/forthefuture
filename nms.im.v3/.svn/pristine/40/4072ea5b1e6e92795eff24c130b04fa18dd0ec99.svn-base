<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;

class Generate extends Controller {

	public function personas(){

			$data_to_insert = array();
			for($x = 0; $x < 10000; $x++){  
				array_push($data_to_insert, [
					'name' => "generated_persona_name_{$x}",
					'service_id' => rand(1,62),
				]);
			}
			\TblPersonas::insert($data_to_insert);

	}

	public function subscribers(){

		\Benchmark::start("auto_response_routine");

		$data_to_insert = array();
		for($x = 0; $x < 20000; $x++){  
			$total = $x+(20000 * 6);
			array_push($data_to_insert, [
				'name' => "generated_subscriber_name_{$total}",
				'service_id' => rand(1,62),
			]);
		}
		\TblSubscribers::insert($data_to_insert);

		\Benchmark::stop("auto_response_routine");
	    $elapsed = \Benchmark::get("auto_response_routine");
	    $total_time["time"] = $elapsed["time"];
	    $total_time["memory"] = $elapsed["memory"];
	    $summary = "({$total_time["time"]}s)";
	    echo("{$summary} | {$total_time["memory"]}");


	}

	public function conversations(){

		\Benchmark::start("auto_response_routine");

		$data_to_insert = array();
		for($x = 0; $x < 15000; $x++){  

			array_push($data_to_insert, [
				'service_id' => rand(1,62),
				'subscriber_id' => rand(1,21),
				'persona_id' => rand(1,32),
				'user_id' => 92
			]);
		}
		\TblConversations::insert($data_to_insert);

		\Benchmark::stop("auto_response_routine");
	    $elapsed = \Benchmark::get("auto_response_routine");
	    $total_time["time"] = $elapsed["time"];
	    $total_time["memory"] = $elapsed["memory"];
	    $summary = "({$total_time["time"]}s)";
	    echo("{$summary} | {$total_time["memory"]}");


	}

	public function messages(){

		\Benchmark::start("auto_response_routine");

		$data_to_insert = array();
		for($x = 0; $x < 5000; $x++){  

			array_push($data_to_insert, [
				'conversation_id' => rand(190000,290000),
				'user_id' => 92,
				'message' => "test message {$x}",
				'status' => 'handled'
			]);
		}
		\TblMessages::insert($data_to_insert);

		\Benchmark::stop("auto_response_routine");
	    $elapsed = \Benchmark::get("auto_response_routine");
	    $total_time["time"] = $elapsed["time"];
	    $total_time["memory"] = $elapsed["memory"];
	    $summary = "({$total_time["time"]}s)";
	    echo("{$summary} | {$total_time["memory"]}");


	}

	public function rpt_messages(){

		\Benchmark::start("auto_response_routine");

		$data_to_insert = array();
		for($x = 0; $x < 15000; $x++){  

			array_push($data_to_insert, [
				'conversation_id' => rand(190000,290000),
				'user_id' => 92,
				'message' => "test message {$x}",
				'status' => 'handled',
			]);
		}
		\RptMessages::insert($data_to_insert);

		\Benchmark::stop("auto_response_routine");
	    $elapsed = \Benchmark::get("auto_response_routine");
	    $total_time["time"] = $elapsed["time"];
	    $total_time["memory"] = $elapsed["memory"];
	    $summary = "({$total_time["time"]}s)";
	    echo("{$summary} | {$total_time["memory"]}");


	}
}