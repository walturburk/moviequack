<script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>

<script>

jQuery(document).ready(function() {
	console.log(jQuery(".quack").text());
});
</script>

<?php 

include("template.php");

/*

class select {
	
	public function __construct($file) {
		$this->sourceHtmlFile($file);
	}
	
	public function sourceHtmlFile($file) {
		$this->file = $file;
		$html = file_get_contents($file);
		$this->html = $this->processHtml($html);
	}
	
	public function sourceHtml($html) {
		$this->html = $this->processHtml($html);
	}
	
	public function processHtml($html) {
		$htmlarray = explode("<", $html);
		$identifier = 0;

		foreach($htmlarray AS $key => $tag) {
			//$htmlarray[$key] = "<".$tag;
			if (strpos($tag, "/") === 0) {
				$identifier ++;
				//echo "KEY: ".$key." TAG: ".$tag." strpos: ".strpos($tag, "/")."<br>";
				$closingtagkey = $key;
				$closingtag = $tag;
				$searchkey = $key;
				
				$tagname2 = explode("/", $closingtag);
				$tagname3 = $tagname = explode(">", $tagname2[1]);
				$tagname4 = $tagname = explode(" ", $tagname3[0]);
				$tagname = $tagname4[0];
				
				while (strpos($htmlarray[$searchkey], "/") === 0 || strpos($htmlarray[$searchkey], "IDENTIFIERARE") > 0 || strpos($htmlarray[$searchkey], $tagname) === false) {
					//echo preg_match("/".$tagname."(?![0-9])+/s", $htmlarray[$searchkey])."<br>";
					
					$searchkey --;
					//echo $htmlarray[$searchkey]." S: ".strpos($htmlarray[$searchkey], $tagname)."<br>";
					//echo "ASDKEY: ".$tagname." TAG: ".$htmlarray[$searchkey]." strpos: ".strpos($htmlarray[$searchkey], $tagname)."<br>";
				}
				//echo "OPENINGTAG: ".$htmlarray[$searchkey]." CLOSINGTAG: ".$closingtag." STRPOS: ".strpos($htmlarray[$searchkey], $tagname)."<br>";
				//echo "STARTINGTAG: ".$htmlarray[$searchkey]."<br>";
				$idstamp = $tagname."IDENTIFIERARE".$identifier."";
				
				
				
				$htmlarray[$searchkey] = preg_replace('/'.$tagname.'/', $idstamp, $htmlarray[$searchkey], 1);//str_replace($tagname, $idstamp, $htmlarray[$searchkey]);
				$htmlarray[$closingtagkey] = preg_replace('/'.$tagname.'/', $idstamp, $htmlarray[$closingtagkey], 1);//str_replace($tagname, $idstamp, $htmlarray[$closingtagkey]);
			}
		}

		$html = implode("<", $htmlarray);
		return $html;
	}
	
	public function selectElement($identifier) {
		$regex = $this->chooseSelector($identifier);
		preg_match($regex, $this->html, $match);
		return $match[0];
	}
	
	
	public function prepareOutputHtml($html) {
		$regex = '/IDENTIFIERARE[0-9]+/s';
		$output = preg_replace($regex, "", $html);
		return $output;
	}
	
	public function html($selector) {
		$html = $this->selectElement($selector);
		return $this->prepareOutputHtml($html);
	}
	
	public function text($selector) {
		$html = $this->selectElement($selector);
		return $this->prepareOutputHtml($html);
	}
	
	public function chooseSelector($selectinput) {
		if (strpos($selectinput, ".") > -1) {
			
			$selectinput = explode(".", $selectinput);
			//$output = "asd".$selectinput[0]."DEL ".$selectinput[1];
			if ($selectinput[0] == "") {
				$element = "[A-Z][A-Z0-9]*";
			} else {
				$element = $selectinput[0];
			}
			$regex = '/<('.$element.'IDENTIFIERARE[0-9]+)([=a-z0-9\\ \'_{}"-]*?) class=(["\'])'.$selectinput[1].'\3([=a-z0-9\\ \'_{}"-]*?)>.*<\/\1>/si';
			
		} else if (strpos($selectinput, "#") > -1) {
			
			$selectinput = explode("#", $selectinput);
			//$output = "asd".$selectinput[0]."DEL ".$selectinput[1];
			if ($selectinput[0] == "") {
				$element = "[A-Z][A-Z0-9]*";
			} else {
				$element = $selectinput[0];
			}
			$regex = '/<('.$element.'IDENTIFIERARE[0-9]+)([=a-z0-9\\ \'_{}"-]*?) id=(["\'])'.$selectinput[1].'\3([=a-z0-9\\ \'_{}"-]*?)>.*<\/\1>/si';
			
		} else if (strpos($selectinput, "[") > -1) {
			
			
			
			$selectinput = explode("[", $selectinput);
			$selectinput[1] = str_replace("]", "", $selectinput[1]);
			//$selectinput[1] = str_replace("'", "\'", $selectinput[1]);
			
			if ($selectinput[0] == "") {
				$element = "[A-Z][A-Z0-9]*";
			} else {
				$element = $selectinput[0];
			}
			$regex = '/<('.$element.'IDENTIFIERARE[0-9]+)([=a-z0-9\ \'_{}"-]*?)('.$selectinput[1].')([=a-z0-9\ \'_{}"-]*?)>.*<\/\1>/si';
			
		} else {
			
			$regex = '/<('.$selectinput.'IDENTIFIERARE[0-9]+)([=a-z0-9\\ \'_{}"-]*?)>.*<\/\1>/si';
			
		}
		return $regex;
	}
	
  
}*/


$selector = new template("templates/moviepage.html");

$movietitle = "HEJ";
$year = "2016";

$selector->grabElement(".quack");

$html = $selector->output();


print_r($html);




//echo $html;

?>