<?php
/**
 * Created by Brandt Williamson
 * Rotten tomatoes API key was not activated however I found a public host
 */

function get_html_source($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}
$url = 'http://209.237.233.58/api/public/v1.0/movies/?apikey=kc98cahphfzuh2mjpkrupzzh&q=';
$searchterms = ['red', 'green', 'blue', 'yellow'];
$results = [];
foreach ($searchterms as $term){
	$movies = json_decode(get_html_source($url.$term))->{'movies'};
	foreach ($movies as $entry){
		$title = $entry->{'title'};
		foreach($searchterms as $colour){
			$pos = stripos($title, $colour);
			if($pos !== false){
				if(isset($index)){
					if($pos < $index){
						$index = $pos;
						$first_colour = $colour;
					}
				}else{
					$index = $pos;
					$first_colour = $colour;
				}
			}
		}unset($index);
		$data = [$entry->{'title'}, $entry->{'year'}, $entry->{'runtime'}, $first_colour];
		if (!in_array($data, $results)){
			array_push($results, $data);
		}
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<style>
			table {
				font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
				font-size: 12px;
				text-align: left;
				border-collapse: collapse;
				margin: 0 auto;
			}
			th {
				font-size: 100%;
				vertical-align: baseline;
				text-align: center;
			}
			td {
				border-top: 1px solid #e8edff;
				padding: 10px 15px;
				text-align: left;
			}
			.red {
				color: #e74c3c;
			}
			.red:hover{
				background: #e74c3c;
				color: black;
			}
			.blue {
				color: #3498db;
			}
			.blue:hover{
				background: #3498db;
				color: black;
			}
			.yellow {
				color: #f1c40f;
			}
			.yellow:hover{
				background: #f1c40f;
				color: black;
			}
			.green {
				color: #27ae60;
			}
			.green:hover{
				background: #27ae60;
				color: black;
			}

		</style>
	</head>
	<body>
		<table>
			<tr>
				<th>Title</th>
				<th>Year</th>
				<th>Run Time</th>
			</tr>
				<?
				foreach ($results as $movie){
					print "<tr class=$movie[3]><td>$movie[0]</td><td>$movie[1]</td><td>$movie[2]</td>";
				}
				?>
		</table>
	</body>
</html>
