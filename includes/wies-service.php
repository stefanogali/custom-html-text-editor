<?php
function storeDataToFile($text, $attribute){
	$file = file_get_contents("text_var.ini");
	$file = preg_replace("/" .$attribute. " =(?![^\"]*\"(?:(?:[^\"]*\"){2})*[^\"]*$).*/i", $attribute." = \"" . trim(preg_replace('/\s+/', ' ', $text)) ."\"", $file);
	file_put_contents("text_var.ini", $file);
}

function getData($text){
	$occurrences = preg_match_all("/(\[([\w]+)[^\]]*\])(.*?)\[\/\\2\]/", $text, $matches, PREG_OFFSET_CAPTURE);
	$bold = '';
	$italic = '';
	$underline = '';
	$link = '[link=';
	$link2 = '[/l]';
	$count = 0;
	$breaker = 999;
	
	while($count < $occurrences){
		if($count >= $breaker){
			$response = array(
				'response' => 0,
				'status' => 400,
				'message' => 'There was an error..Please contact Panino Panini!'
			);
			return json_encode($response);
		}
		foreach ($matches as $index) {
			switch ($index[$count][0]) {
				case "[bi]":
					$boldUnderline  = $matches[0][$count][0];
					$replace = '<b><i>' . $matches[3][$count][0] . '</i></b>';
					$text = str_replace($boldUnderline, $replace, $text);
					break;

				case "[iu]":
					$italicUnderline  = $matches[0][$count][0];
					$replace = '<i><u>' . $matches[3][$count][0] . '</u></i>';
					$text = str_replace($italicUnderline, $replace, $text);
					break;

				case "[b]":
					$bold  = $matches[0][$count][0];
					$replace = '<b>' . $matches[3][$count][0] . '</b>';
					$text = str_replace($bold, $replace, $text);
					break;

				case "[i]":
					$italic  = $matches[0][$count][0];
					$replace = '<i>' . $matches[3][$count][0] . '</i>';
					$text = str_replace($italic, $replace, $text);
					break;

				case "[u]":
					$underline  = $matches[0][$count][0];
					$replace = '<u>' . $matches[3][$count][0] . '</u>';
					$text = str_replace($underline, $replace, $text);
					break;
			}
			if (strpos($index[$count][0], $link) !== false && strpos($index[$count][0], $link2) !== false){
				$checkLink = '';
				$arrayTextLink = $matches[0][$count][0];
				$checkLink = str_replace(array('[link=',']'), '', $matches[1][$count][0]);
				if(strpos($checkLink,"/") != 0){
					//external link
					$replace = '<a href ="' . $checkLink.'" target="_blank">'. $matches[3][$count][0] . '</a>';
				}else{
					//internal link
					$replace = '<a href ="' . $checkLink.'">'. $matches[3][$count][0] . '</a>';
				}
				$text = str_replace($arrayTextLink, $replace, $text);
			}
		}
		$count++;
	}
	$response = array(
				'response' => 1,
				'status' => 200,
				'message' => $text
			);
	return json_encode($response);
	//return $text;
}
//detect browser
if (isset($_SERVER['HTTP_USER_AGENT'])) {
	$agent = $_SERVER['HTTP_USER_AGENT'];
}
if(isset($_POST['value']) && isset($_POST['attr'])){
	$attribute = $_POST['attr'];
	$tagsToSanitize = array("<span class=\"red\">", "</span>", "\"");
	$sanitizedTags = array("", "", "&#34;");
	$text = str_replace($tagsToSanitize, $sanitizedTags, $_POST['value']);
	//if firefox remove last <br> tag that is added randomly by browser
	if (strlen(strstr($agent, 'Firefox')) > 0) {
	    $text = preg_replace('/(<br>)+$/', '', $text);
	}
	storeDataToFile($text, $attribute);
	$convertedToHTML = getData($text);
	echo $convertedToHTML;
}
?>