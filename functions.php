<?php

autoLogin();
//getDontTag();

$apikey = "0a9b195ddb48019271ac2de755730dd4";
$userid = $_SESSION["user"];
$basethumburl = "https://image.tmdb.org/t/p/w92/";
$baseposterurl = "https://image.tmdb.org/t/p/w342/";
$basebackdropurl = "https://image.tmdb.org/t/p/w780/";
$basebigbackdropurl = "https://image.tmdb.org/t/p/w1280/";

define("basethumburl", "https://image.tmdb.org/t/p/w92/");
define("basepostermurl", "https://image.tmdb.org/t/p/w185/");
define("baseposterurl", "https://image.tmdb.org/t/p/w342/");

function addGenreNames() {
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.themoviedb.org/3/genre/movie/list?api_key=0a9b195ddb48019271ac2de755730dd4",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_POSTFIELDS => "{}",
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  $print = "cURL Error #:" . $err;
	} else {
	  $genrenames = json_decode($response);

		foreach ($genrenames->genres AS $g) {
			$query = "INSERT INTO `genrenames` (`id`, `name`)
			VALUES ('".$g->id."', '".$g->name."');
			";
			db_query($query);
			echo $query;
		}
	}
}


function getDontTag() {
$array[] = "and";
$array[] = "very";
$array[] = "but";
$array[] = "plus";
$array[] = "is";
$array[] = "and";
$array[] = "i";
$array[] = "in";
$array[] = "it";
$array[] = "at";
$array[] = "really";
$array[] = "them";
$array[] = "to";
$array[] = "make";
$array[] = "a";
$array[] = "about";
$array[] = "this";
$array[] = "we";
$array[] = "does";
$array[] = "doesnt";
$array[] = "are";
$array[] = "arent";

$string = implode($array, ", ");
file_put_contents('donttag.bin', $string);
return $array;
}

include("emoji/emoji.php");
include("template.php");

if (!isset($_SESSION["user"])) {
	makeDummyUser();
}

function newId($prefix) {

	do {
		$newid = uniqid();
		$id = $prefix.$newid;
		if ($prefix == "u") {
			$idfromdb = db_select("SELECT id FROM `user` WHERE id = '$id'");
		} else if ($prefix == "p") {
			$idfromdb = db_select("SELECT id FROM `post` WHERE id = '$id'");
		}
	} while ($id == $idfromdb[0]["id"]);

	return $id;
}

function getSession($user, $time) {

	$session = db_select("SELECT id FROM `session` WHERE user = '$user' AND time < $time ORDER BY time DESC LIMIT 1");

	$hash = $user.$session[0]["id"];

	return $hash;
}

function saveAutoLogin() {
	$user = $_SESSION["user"];
	$time = time();
	$hash = getSession($user, $time);

	$loginhash = createHash($hash);

	setcookie("user", $user, $time + (60*60*24*365), "/");
	setcookie("logintime", $time, $time + (60*60*24*365), "/");
	setcookie("loginhash", $loginhash, $time + (60*60*24*365), "/");

	return $loginhash;
}

function autoLogin() {

	if (isset($_COOKIE["user"]) && isset($_COOKIE["logintime"]) && isset($_COOKIE["loginhash"])) {

		$user = $_COOKIE["user"];
		$logintime = $_COOKIE["logintime"];
		$loginhash = $_COOKIE["loginhash"];

		$session = getSession($user, $logintime);

		if (hashOk($session, $loginhash)) {
			$_SESSION["loggedin"] = true;
			$_SESSION["user"] = $user;
			$userinfo = db_select("SELECT username FROM `user` WHERE id = '$user' LIMIT 1");
			$_SESSION["username"] = $userinfo[0]["username"];
		}

	}

}

function postMessage($id, $emoji, $movie, $msg, $user) {
	$time = time();
	$user = $_SESSION["user"];

	$proptags = explode(" ", $msg);
	$file = file_get_contents('donttag.bin');
	$notallowed = explode($file, ", ");
	foreach ($proptags AS $tag) {
		$tag = strtolower($tag);
		if (in_array($tag, $notallowed)) {

		} else {
			addTag("", $tag);
		}
	}

	$message = db_escape($msg);
	$query = "INSERT INTO `post` (`id`, `movieid`, `emoji`, `message`, `userid`, `timestamp`)
	VALUES ('$id', '$movie', '$emoji', '$message', '$user',  '$time');
	";
	db_query($query);
}

function postAsAnswer($thismsg, $opmsg) {
	$query = "INSERT INTO `reply` (`reply`, `original`)
	VALUES ('$thismsg', '$opmsg');
	";
	db_query($query);
}

function vote($post, $upvote = 0, $downvote = 0) {
	$timestamp = time();
	$user = $_SESSION["user"];
	$votes = db_select("SELECT * FROM `vote` WHERE post = '$post' AND user = '$user' AND `upvote` = $upvote AND `downvote` = $downvote");
	$votediff = 0;
	if ($votes) {
		$query = "DELETE FROM `vote` WHERE user = '$user' AND post = '$post';";
		$votediff = 0;
	} else {
		$query = "INSERT INTO `vote` (`post`, `user`, `timestamp`, `upvote`, `downvote`)
		VALUES ('$post', '$user', $timestamp, $upvote, $downvote)
		ON DUPLICATE KEY UPDATE
		  timestamp=$timestamp, upvote=$upvote, downvote=$downvote;
		";
		$votediff = $upvote-$downvote;
	}
	db_query($query);
	return $votediff;
}

function getVotes($post) {
	$votes = db_select("SELECT dv.downvote AS downvotes, uv.upvote AS upvotes, (uv.upvote - dv.downvote) AS diff
	FROM
	(SELECT count(downvote) AS downvote FROM `vote` WHERE downvote = true AND upvote = false AND post = '$post') AS dv,
	(SELECT count(upvote) AS upvote FROM `vote` WHERE downvote = false AND upvote = true AND post = '$post') AS uv");
	return $votes[0];
}

function getVotebtnActive($post, $upvote) {
	$user = $_SESSION["user"];
	if ($upvote == true) {
		$col = "upvote";
	} else {
		$col = "downvote";
	}
	$votes = db_select("SELECT * FROM `vote` WHERE `user` = '$user' AND `post` = '$post' AND `$col` = 1");
	if ($votes) {
		$active = "activebtn";
	} else {
		$active = "";
	}
	//$active = $votes[0]["timestamp"];
	return $active;
}

function getMessages($movie) {
	$posts = db_select("SELECT
	reply.reply,
	user.username AS username, user.id AS userid,
	post.timestamp AS timestamp, post.emoji,
	post.id, post.message, post.userid, post.movieid,
(SUM((10+od.upvote-od.downvote)*1000/(UNIX_TIMESTAMP()-post.timestamp)))
	AS votes
	FROM user
	LEFT JOIN post
	ON user.id = post.userid
	LEFT JOIN vote od
	ON post.id = od.post
	LEFT JOIN reply
	ON reply.reply = post.id
	WHERE post.movieid = '$movie' AND reply.reply IS NULL
	GROUP BY post.id
	ORDER BY `votes` DESC
");
	return $posts;
}

function getReplies($original) {
	$posts = db_select("SELECT user.username, user.id AS userid, post.id AS postid, post.message, post.timestamp FROM `post`
LEFT JOIN reply
ON reply.reply = post.id
LEFT JOIN user
ON user.id = post.userid
WHERE reply.original = '$original'");
	return $posts;
}

function getLatestMessage() {
	$posts = db_select("SELECT
	movie.imdbid, movie.poster, reply.reply,
	user.username AS username, user.id AS userid,
	post.timestamp AS timestamp, post.emoji,
	post.id, post.message, post.userid, post.movieid,
(SUM((10+od.upvote-od.downvote)*1000/(UNIX_TIMESTAMP()-post.timestamp)))
	AS votes
	FROM user
	LEFT JOIN post
	ON user.id = post.userid
	LEFT JOIN vote od
	ON post.id = od.post
	LEFT JOIN reply
	ON reply.reply = post.id
	LEFT JOIN movie
	ON movie.imdbid = post.movieid
	WHERE reply.reply IS NULL
	GROUP BY post.id
	ORDER BY `post`.timestamp DESC
LIMIT 1");
	return $posts;
}

function getTrendingMessage() {
	$posts = db_select("SELECT user.username AS username, user.id AS userid, post.timestamp AS timestamp, post.emoji, post.id, post.message, post.userid, post.movieid, (SUM((10+od.upvote-od.downvote)*1000/(UNIX_TIMESTAMP()-post.timestamp))) AS votes
FROM user
LEFT JOIN post
ON user.id = post.userid
LEFT JOIN vote od
ON post.id = od.post
GROUP BY post.id
ORDER BY `votes`  DESC
LIMIT 1");
	return $posts;
}

function getTopMessage() {
	$posts = db_select("SELECT user.username AS username, user.id AS userid, post.timestamp AS timestamp, post.emoji, post.id, post.message, post.userid, post.movieid, (SUM((od.upvote-od.downvote))) AS votes
FROM user
LEFT JOIN post
ON user.id = post.userid
LEFT JOIN vote od
ON post.id = od.post
GROUP BY post.id
ORDER BY `votes`  DESC
LIMIT 1");
	return $posts;
}

function getControversialMessage() {
	$posts = db_select("SELECT user.username AS username, user.id AS userid, post.timestamp AS timestamp, post.emoji, post.id, post.message, post.userid, post.movieid, (SUM((od.upvote))) AS upvotes, (SUM((od.downvote))) AS downvotes, (SUM(od.upvote)*SUM(od.downvote)) as multi
FROM user
LEFT JOIN post
ON user.id = post.userid
LEFT JOIN vote od
ON post.id = od.post
GROUP BY post.id
ORDER BY `multi`  DESC, downvotes DESC
LIMIT 1");
	return $posts;
}

function printMessage($postsarray) {
	$user = $_SESSION["user"];
	$q = new Template("templates/quack.html");
	$posts = "";
	foreach ($postsarray AS $post) {
		$upact = "";
		$downact = "";
		$replies = printReplies(getReplies($post["id"]));
		$upact = getVotebtnActive($post["id"], true);
		$downact = getVotebtnActive($post["id"], false);
		$q->set("replies", $replies);
		$q->set("username", $post["username"]);
		$q->set("userid", $post["userid"]);
		$q->set("upvoteactive", $upact);
		$q->set("downvoteactive", $downact);
		$q->set("msgemoji", getEmoji($post["emoji"]));
		$snop += 1;
		$q->set("snop", $snop);
		$q->set("message", $post["message"]);
		$q->set("movieid", $post["movieid"]);
		$q->set("postid", $post["id"]);
		$votes = getVotes($post["id"]);
		$q->set("votes", $votes["diff"]);
		$q->set("upvotes", $votes["upvotes"]);
		$q->set("downvotes", $votes["downvotes"]);
		$q->set("quacksize", 16+$votes["diff"]);
		$q->set("rawtimestamp", $post["timestamp"]);
		$q->set("timestamp", formatTimestamp($post["timestamp"]));
		$q->set("shorttime", formatTimestampSmart($post["timestamp"]));
		if ($user == $post["userid"]) {
			$removepostbtn = '<div data-post="'.$post["id"].'" class="simplebtn removepost">Remove post</div>';
		} else {
			$removepostbtn = "";
		}
		$q->set("removepostbtn", $removepostbtn);
		$posts .= $q->output();
	}

	return $posts;
}

function printReplies($postsarray) {

	$posts = "";
	foreach ($postsarray AS $post) {
		$posts .= "<div class='replymsg'><a class='small' href='/profile.php?id=".$post["userid"]."'>".$post["username"]."</a>";
		$posts .= "<div class='padding0'>".$post["message"]."</div></div>";
	}

	return $posts;
}

function printSpecMessage($sort) {
	if ($sort == 1) {
		$postarray = getLatestMessage();
	} elseif ($sort == 2) {
		$postarray = getTrendingMessage();
	} else {
		$postarray = getTopMessage();
	}
	$message = "<a href='/movie.php?id=".$postarray[0]["movieid"]."'>";
	$message .= "<img src='".$postarray[0]["poster"]."'>";
	$message .= "</a>";

	$message .= printMessage($postarray);
	return $message;
}

function printMessages($movie) {

	$postsarray = getMessages($movie);
	$messages = printMessage($postsarray);

	return $messages;
}

function skipMovie($movie) {

	$movies = unserialize($_COOKIE['skipmovies']);
	$movies[] = $movie;
	setcookie('skipmovies', serialize($movies), time()+60*60*24);

	return $movies;
}

function getRandomMovie() {

	$user = $_SESSION["user"];

	$skipsql = "";

	if (isset($_COOKIE["skipmovies"])) {
		$movies = unserialize($_COOKIE['skipmovies']);
		foreach ($movies AS $skipmovie) {
			$skipsql .= " AND m.id != '".$skipmovie."' ";
		}

	}

$sql = "SELECT m.id, m.title, m.poster, r.rating, li.item, l.listid FROM `movie` AS m
LEFT JOIN listitem AS li ON li.item = m.id
LEFT JOIN list AS l
ON l.listid = li.list AND l.user = '".$user."'
LEFT JOIN ratemovie AS r ON r.movie = m.id AND r.user = '".$user."'
WHERE l.listid IS NULL AND r.rating IS NULL AND m.poster != ''
".$skipsql."
ORDER BY rand()
LIMIT 1";

	$movie = db_select($sql);

if (empty($movie)) {
	setcookie('skipmovies', "", time()-3600);
	$sql = "SELECT m.id, m.title, m.poster, r.rating, li.item
	FROM `movie` AS m
	LEFT JOIN listitem AS li ON li.item = m.id
	LEFT JOIN list AS l ON l.listid = li.list
	LEFT JOIN ratemovie AS r ON r.movie = m.id AND r.user = '".$user."'
	WHERE li.item IS NULL AND r.rating IS NULL AND m.poster != ''
	".$skipsql."
	ORDER BY rand()
	LIMIT 1";

		$movie = db_select($sql);
}

	return $movie[0];
}

function printRandomMovie() {
	$user = $_SESSION["user"];
	$movie = getRandomMovie();
	$listarr = getLists($user);
	foreach ($listarr AS $list) {
		if ($list["name"] == "Watchlist") {
			$watchlist = $list["listid"];
		} else if ($list["name"] == "Recommend") {
			$recommendlist = $list["listid"];
		}
	}
	$emojicode = ":bust_in_silhouette:";
	$print = "";
	if (!empty($movie) && $_SESSION["loggedin"]) {
		$t = new Template("templates/movieprint.html");

		$t->set("upvoteactive", getVotebtnActive($movie["id"], true));
		$t->set("downvoteactive", getVotebtnActive($movie["id"], false));
		$t->set("title", $movie["title"]);
		$t->set("posterurl", basepostermurl.$movie["poster"]);
		$t->set("thumburl", basethumburl.$movie["poster"]);
		$t->set("rate", getMovieRating($movie["id"]));
		$t->set("urate", getUsersMovieRating($movie["id"], $user));
		$t->set("rating", printMovieRating($movie["id"], $rate, $urate));
		$t->set("recommendlist", $recommendlist);
		$t->set("watchlist", $watchlist);
		$t->set("movieid", $movie["id"]);
		$t->set("emojicode", $emojicode);
		$t->set("emoji", getEmoji($emojicode));

		$print = $t->output();
	} else {
		$print = "";
	}

	return $print;
}

function rateMovie($movie, $rating) {

	$user = $_SESSION["user"];
	$time = time();

	if ($rating == "null") {
		$query = "DELETE FROM ratemovie WHERE user = '$user' AND movie = '$movie'";
	} else {
	$query = "INSERT INTO `ratemovie` (`movie`, `user`, `rating`, `timestamp`)
	VALUES ('$movie', '$user', '$rating', '$time')
ON DUPLICATE KEY UPDATE
		  timestamp=".$time.", rating=".$rating."
	";
	}

	return db_query($query);

}

function getMovieRating($movie) {

	$sql = "SELECT SUM(rating) / COUNT(rating) AS overall
FROM `ratemovie`
WHERE movie = '".$movie."'";

	$movie = db_select($sql);
	return $movie[0]["overall"];
}

function getUsersMovieRating($movie, $user) {

	$sql = "SELECT rating
FROM `ratemovie`
WHERE movie = '".$movie."' AND user = '".$user."'
LIMIT 1";

	$movie = db_select($sql);
	return $movie[0]["rating"];
}

function printMovieRating($movie, $rating, $urate) {


	$starrating = round($rating)/2-0.5;

$starclass[] = "star1";
$starclass[] = "star2";
$starclass[] = "star3";
$starclass[] = "star4";
$starclass[] = "star5";



	for ($i = 0; $i < 5; $i++) {
		if ($i > $starrating) {
			$starico = "star_border";
		} else if ($i == $starrating) {
			$starico = "star_half";
		} else {
			$starico = "star";
		}

		if (isset($urate)) {
		if ($i > $urate/2-1) {
			$myrating = "myratingdark";
		} else if ($i == $urate/2-1) {
			$myrating = ($urate/2-1)." myrating actualvote ".$i;
		} else {
			$myrating = "myrating";
		}
	} else {
		$myrating = "notrated";
	}



		$starnr = "";
		foreach ($starclass AS $class) {
			$starnr .= " ".$class;
		}
		unset($starclass[$i]);


		$temp = '<div data-movie="'.$movie.'" data-starnr="'.($i+1).'" class="'.$myrating.' votestar '.$starnr.'"><i class="material-icons">'.$starico.'</i></div>';
		$print .= $temp;
	}

	//$print .= "<div class='red absolutecenter'>".(round($rating*10)/10)."</div>";
	return $print;
}

function getMovies($searchterm) {
	$sqlstart = "SELECT originaltitle AS originaltitle, title AS title, id, year, poster, tmdbid FROM  `movie` WHERE  ";
	$searchterm = mysqli_real_escape_string(db_connect(), $searchterm);
	$like = " `searchstring` LIKE '%".$searchterm."%' ";

	$length = strlen($searchterm);

	for ($i=0; $i<$length; $i++) {
		if ($i == 0 || $i == $length || $i == $length-1) {} else {

			$tsearchterm = substr_replace($searchterm, "_", $i, 1);
			$like .= " OR `searchstring` LIKE '%".$tsearchterm."%' ";

		}
	}
	$sql = $sqlstart.$like;
	$movies = db_select($sql);

	return $movies;
}

function getUsers($searchterm) {
	$searchterm = mysqli_real_escape_string(db_connect(), $searchterm);
	$users = db_select("SELECT id, username FROM  `user` WHERE  `username` LIKE  '%".$searchterm."%'");
	return $users;
}

function getExternalMovies($q) {
	global $apikey;

	$apiq = urlencode($q);

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.themoviedb.org/3/search/movie?include_adult=false&page=1&query=".$apiq."&api_key=".$apikey,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_POSTFIELDS => "{}",
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  $print = "cURL Error #:" . $err;
	} else {
	  $print = $response;
	}

$movies = getMovies($q);

	$html = json_decode($print, true);

		if (is_array($html["results"])) {
			foreach ($html["results"] AS $key => $val) {
				//$val = array_change_key_case($val, CASE_LOWER);
				$isinarray = false;
				foreach ($movies AS $mia) {
					if ($mia["tmdbid"] == $val["id"]) {
							$isinarray = true;
					}
				}
				if (!$isinarray) {
					$xmovies[] = $val;
					//addMovie($movie["imdbID"]);
				}
			}
		}
		//$return = sortByGenre($xmovies);
		return $xmovies;
}

function getExternalMoviesOldBroken($q) {
	global $apikey;
	$movies = getMovies($q);
	$xmovies = [];
		$apiq = urlencode($q);
	    $url = "https://api.themoviedb.org/3/search/movie?api_key=0a9b195ddb48019271ac2de755730dd4&query=casablanca&page=1&include_adult=false";
		$json = file_get_contents($url);
		$headers = var_dump($http_response_header);
		$html = json_decode($json, true);
		if (is_array($html["results"])) {
			foreach ($html["results"] AS $key => $val) {
				$val = array_change_key_case($val, CASE_LOWER);
				$isinarray = false;
				foreach ($movies AS $mia) {
					if ($mia["id"] == $val["id"]) {
						$isinarray = true;
					}
				}
				if (!$isinarray) {
					$xmovies[] = $val;
					//addMovie($movie["imdbID"]);
				}
			}
		}
		$return = sortByGenre($xmovies);
		return $return;
}

function sortByGenre($movies) {
	$shorts = [];
	$documentaries = [];
	$all = [];
	foreach ($movies AS $movie) {
		if (strpos($movie["genre"], "short")) {
			$shorts[] = $movie;
		} else if (strpos($movie["genre"], "documentary")) {
			$documentaries[] = $movie;
		} else {
			$all[] = $movie;
		}
		$return = array_merge($all, $documentaries, $shorts);
	}
	return $return;
}

function reAddMovie($id) {

	$movieinfo = db_select("SELECT * FROM  `movie` WHERE  `id` =  '".$id."'");
	$movie = $movieinfo[0];

	if ($movie["id"]) {
		db_query("DELETE FROM movie WHERE id = '".$id."'");
		addMovie($movie["id"]);
	}

}

function addMovie($id) {

	global $apikey;

	if (strpos($id, 'tt') !== false) {
		$sql = "SELECT * FROM  `movie` WHERE  `imdbid` =  '".$id."'";
	} else {
		$sql = "SELECT * FROM  `movie` WHERE  `id` =  '".$id."'";
	}

	$movieinfo = db_select($sql);
	$movie = $movieinfo[0];
	$mqid = $movie["id"];
	if (!$movie["id"]) {
		$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.themoviedb.org/3/movie/".$id."?api_key=".$apikey,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "{}",
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  $json = "cURL Error #:" . $err;
} else {
  $json = $response;
}
		$movie = json_decode($json, true);
		$movie = array_change_key_case($movie, CASE_LOWER);
		$printablemovie = $movie;
		//addPoster($movie["poster"]);
		//$movie = array_map('mysql_escape_string', $movie);

		foreach ($movie AS $key => $value) {
			if (!is_array($value)) {
				$movie[$key] = mysqli_real_escape_string(db_connect(), $value);
			}
		}

		$expdate = explode("-", $movie["release_date"]);
		$year = $expdate[0];
		$searcht = $movie["title"]." ".$movie["original_title"];
		$searchstring = iconv('UTF-8', 'ASCII//TRANSLIT', $searcht);//strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $searcht));
		$mqid = "m".$movie["id"];



		$query = "INSERT INTO `".dbname."`.`movie`
		(`id`, `title`, `originaltitle`, `year`, `releasedate`, `backdrop`, `budget`, `homepage`,
			`imdbid`, `language`, `overview`, `poster`, `revenue`, `runtime`, `status`, `tagline`, `tmdbid`, `searchstring`)
			VALUES
			('".$mqid."', '".$movie["title"]."', '".$movie["original_title"]."', '".$year."', '".$movie["release_date"]."',
			'".$movie["backdrop_path"]."', '".$movie["budget"]."', '".$movie["homepage"]."', '".$movie["imdb_id"]."',
			'".$movie["original_language"]."', '".$movie["overview"]."', '".$movie["poster_path"]."',
			'".$movie["revenue"]."', '".$movie["runtime"]."', '".$movie["status"]."', '".$movie["tagline"]."', '".$movie["id"]."', '".$searchstring."');";

		db_query($query);

		addGenresForMovie($movie["genres"], $mqid);
		addCompanies($movie["production_companies"], $mqid);
		addProdCountries($movie["production_countries"], $mqid);
		addLanguages($movie["spoken_languages"], $mqid);
		addCollections($movie["belongs_to_collection"], $mqid);

		//$movie = $printablemovie;
	}
//$movie["mqid"] = $mqid;
	return $mqid;
}

function addGenresForMovie($genres, $movie) {
	foreach ($genres AS $g) {
		$query = "INSERT INTO `genre` (`movie`, `genre`)
		VALUES ('".$movie."', '".$g["id"]."');
		";
		db_query($query);
		//echo $query;
	}
}

function addCompanies($comp, $movie) {
	foreach ($comp AS $c) {
		$query = "INSERT INTO `productioncompany` (`id`, `name`)
		VALUES ('".$c["id"]."', '".$c["name"]."');
		";
		db_query($query);
		$query = "INSERT INTO `producedby` (`movie`, `company`)
		VALUES ('".$movie."', '".$c["id"]."');
		";
		db_query($query);
	}
}

function addProdCountries($countries, $movie) {
	foreach ($countries AS $c) {
		$query = "INSERT INTO `producedin` (`movie`, `country`)
		VALUES ('".$movie."', '".$c["iso_3166_1"]."');
		";
		db_query($query);
	}
}

function addLanguages($langs, $movie) {
	foreach ($langs AS $l) {
		$query = "INSERT INTO `language` (`movie`, `lang`)
		VALUES ('".$movie."', '".$l["iso_639_1"]."');
		";
		db_query($query);
	}
}

function addCollections($col, $movie) {
	foreach ($col AS $l) {
		$query = "INSERT INTO `collection` (`id`, `name`, `poster`, `backdrop`)
		VALUES ('".$l["id"]."', '".$l["name"]."', '".$l["poster_path"]."', '".$l["backdrop_path"]."');
		";
		db_query($query);
		$query = "INSERT INTO `incollection` (`collection`, `movie`)
		VALUES ('".$l["id"]."', '".$movie."');
		";
		db_query($query);
	}
}

function addTag($movie, $tag) {

	$user = $_SESSION["user"];
	$tag = strtolower(preg_replace('/[^a-zA-Z0-9-_ ]/','', $tag));
	$time = time();

	$query = "INSERT INTO `".dbname."`.`tag` (`movie`, `user`, `tag`, `timestamp`) VALUES ('".$movie."', '".$user."', '".$tag."', '".$time."');";

	db_query($query);
	return $query;
}

function removeTag($movie, $tag) {
	$user = $_SESSION["user"];
	$sql = "DELETE FROM  `".dbname."`.`tag` WHERE  `tag`.`user` =  '".$user."' AND  `tag`.`movie` =  '".$movie."' AND  `tag`.`tag` = '".$tag."' LIMIT 1";
	return db_query($sql);
}

function getAllTags() {
	$tags = db_select("SELECT movie, user, tag, timestamp, COUNT(user) AS c FROM  `tag` GROUP BY tag ORDER BY tag ASC");
	return $tags;
}

function printAllTags($movie = null) {
	$tags = getAllTags();
	foreach ($tags AS $tag) {
		$print .= "<div class='tag' data-movie='".$movie."'>";
		$print .= $tag["tag"];
		$print .= "</div>";
	}
	return $print;
}

function getTagsByLetter($q) {
	$tags1 = db_select("SELECT tag FROM tag WHERE tag LIKE '$q%' GROUP BY tag ORDER BY tag DESC LIMIT 5");
	return $tags1;
}

function getTagsByUser($movie, $user) {
	$tags = db_select("SELECT movie, user, tag, timestamp, COUNT(user) AS c FROM  `tag` WHERE  `movie` =  '".$movie."' AND user = '".$user."' GROUP BY tag ORDER BY c DESC");
	return $tags;
}

function getTags($movie) {
	$user = $_SESSION["user"];
	$tags1 = db_select("SELECT movie, user, tag, timestamp, COUNT(user) AS c FROM  `tag` WHERE  `movie` =  '".$movie."' AND user = '".$user."' GROUP BY tag ORDER BY c DESC");
	$tags2 = db_select("SELECT movie, user, tag, timestamp, COUNT(user) AS c FROM  `tag` WHERE  `movie` =  '".$movie."' GROUP BY tag ORDER BY c DESC");

	$active = array();
	$nonactive = array();
	$entry = array();

	foreach ($tags1 AS $tag1) {
		$alreadyadded[] = $tag1["tag"];
	}

	foreach ($tags2 AS $tag2) {
			if (in_array($tag2["tag"], $alreadyadded)) {
				$entry["movie"] = $tag2["movie"];
				$entry["user"] = $tag2["user"];
				$entry["tag"] = $tag2["tag"];
				$entry["timestamp"] = $tag2["timestamp"];
				$entry["c"] = $tag2["c"];
				$entry["active"] = "activebtn";
				$active[] = $entry;
			} else {
				$nonactive[] = $tag2;
			}
		}

	if (empty($tags1)) {
		$tags = $tags2;
	} else {
		$tags = array_merge($active, $nonactive);
	}
	return $tags;
}

function printTags($tags, $movie) {
	$user = $_SESSION["user"];

	foreach ($tags AS $tag) {
		$active = $tag["active"];
		$fontsize = 14+$tag["c"];
		$print .= "<span style='font-size:".$fontsize."px' class='tag $active' data-movie='".$movie."'>";
		$print .= $tag["tag"];
		$print .= "</span> ";
	}

	if (empty($tags)) {
		$print = "<span class='grey smalltext inblock padding0'>No tags</span>";
	}
	return $print;
}

function getExternalStreams($title, $year = null)
{

	global $locale;

	$year = (int)$year;
    $data = array("query" => $title, "release_year_from" => $year, "release_year_until" => $year);
	$data_string = json_encode($data);

	$ch = curl_init('https://api.justwatch.com/titles/'.$locale.'/popular');
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
	);

	$result = curl_exec($ch);
	$result = json_decode($result, true);
	//$streams = $result["items"][0]["offers"];
	return $result;
}

function saveStreams($movieid, $title, $year) {

	$strms = getStreams($movieid);
	$week = 604800;

	if ($strms[0]["timestamp"] < time()-$week) {
	$streams = getExternalStreams($title, $year);


	$cleandbtitle = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $title));
	$cleanstreamtitle = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $streams["items"][0]["title"]));
	/*echo "<br>";
	if ($cleandbtitle == $cleanstreamtitle) {
		echo "yes <br>".$cleandbtitle ."<br>". $cleanstreamtitle;
	} else {
		echo "no <br>".$cleandbtitle ."<br>". $cleanstreamtitle;
	}
	echo "<br>";*/

	if ($cleandbtitle == $cleanstreamtitle && is_array($streams["items"][0]["offers"])) {

		$query = "DELETE FROM stream WHERE movieid = '$movieid'";

		db_query($query);

		$streams = $streams["items"][0]["offers"];

		$timestamp = time();

		foreach($streams AS $stream) {
			$region = "en_SE";
			$type = $stream["monetization_type"];
			$provider = $stream["provider_id"];
			$price = $stream["retail_price"];
			$currency = $stream["currency"];
			$link = $stream["urls"]["standard_web"];
			$def = $stream["presentation_type"];
			$dateproviderid = $stream["date_provider_id"];

			$query = "INSERT INTO `".dbname."`.`stream`
			(`movieid`, `region`, `type`, `provider`, `price`, `currency`, `link`, `def`, `dateproviderid`, `timestamp`)
			VALUES
			('$movieid', '$region', '$type', '$provider', '$price', '$currency', '$link', '$def', '$dateproviderid', '$timestamp')
				";

			db_query($query);
		}
	}
	}
}


function getStreams($movieid) {

	$streams = db_select("SELECT *
FROM  `stream`, `provider`
WHERE stream.movieid = '$movieid' AND stream.provider = provider.id
GROUP BY short
ORDER BY  `stream`.`price` ASC ");

	return $streams;
}

function printStreams($movieid) {
$streams = getStreams($movieid);
	if (!empty($streams)) {
		//$print = "<h3 class='marginbottom'>This title is available for streaming</h3>";
		foreach ($streams AS $stream) {
			$print .= "<a href='";
			$print .= $stream["link"];
			if ($stream["price"] > 0) {

			} else {
				$print .= "' class='free";
			}
			$print .= "'>";
			$print .= $stream["clear"];
			$print .= "</a>";
		}
	} else {
		$print = "<div class='padding'>No streams available</div>";
	}

	return $print;
}


function addPoster($link) {
	if ($link != null) {
		$linkparts = explode("/", $link);
		$filename = end($linkparts);
		array_pop($linkparts);
		$dir = implode("/", $linkparts);
		$imageContents = file_get_contents($link);
		$imageContentsEscaped = mysql_real_escape_string($imageContents);
		db_query("INSERT INTO poster (movie, filename, image) VALUES ('movie', '$filename', '$imageContentsEscaped')");
		echo "<h1>".$imageContents."</h1>";
	}
}

function makeDummyUser() {
	$ip = getUserIp();
	$user = db_select("SELECT * FROM  `user` WHERE `ip` = '$ip'");
	$user = $user[0];
	//print_r($user);
	if ($user["id"]) {
		newSession($user["id"], $user["username"]);
	} else {
		$id = newId("u");
		$username = "Visitor";


		$query = "INSERT INTO `user` (`id`, `username`, `password`, `email`, `ip`)
		VALUES ('$id', '$username', '', '', '$ip');
		";
		$return = db_query($query);

		newSession($id, $username);
		$_SESSION["loggedin"] = false;
	}

}


function newSession($user, $username) {

	$_SESSION["user"] = $user;
	$_SESSION["username"] = $username;

	$id = session_id();
	$browser = $_SERVER['HTTP_USER_AGENT'];
	$time = time();
	$ip = getClientIp();


	$query = "INSERT INTO `session` (`id`, `time`, `ip`, `browser`, `user`)
	VALUES ('$id', '$time', '$ip', '$browser', '$user');
	";
	$return = db_query($query);
}

function get_browser_name($user_agent)
{
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';

    return 'Other';
}

function checkiffollows($follower, $follows) {
	$user = db_select("SELECT `timestamp` FROM  `follow` WHERE `follower` = '$follower' AND `follows` = '$follows'");
	return $user[0]["timestamp"];
}

function follow($follows) {
	$follower = $_SESSION["user"];
	if (checkiffollows($follower, $follows)) {
		$query = "DELETE FROM `follow` WHERE `follower` = '$follower' AND `follows` = '$follows';";
		$ret = false;
	} else {
		$timestamp = time();
		$query = "INSERT INTO `".dbname."`.`follow`
		(`follower`, `follows`, `timestamp`)
		VALUES ('$follower', '$follows', '$timestamp');";
		$ret = true;
	}
	db_query($query);
	return $ret;
}

function getTagFeed($user = null) {

		if (is_array($user)) {
			foreach ($user AS $u) {
				$postusersql .= " OR tag.user = '$u'";
			}
		} else if (isset($user) && $user != "") {
			$postusersql = " OR tag.user = '$user'";
		} else if ($user == null) {
			$postusersql = " OR tag.user != 'q'";
		}

		$sql = "SELECT tag.user AS user1id, user.username AS user1, tag.movie, tag.timestamp, movie.id AS movieid, movie.poster, GROUP_CONCAT(tag.tag SEPARATOR ', ') AS tag
FROM `tag`
LEFT JOIN user
ON tag.user = user.id
LEFT JOIN movie
ON tag.movie = movie.id
WHERE tag.movie != '' AND (user = 'start' $postusersql)
GROUP BY tag.movie
ORDER BY `tag`.`timestamp` DESC
		LIMIT 30";
		$feed = db_select($sql);

		return $feed;

}

function getListFeed($user = null) {

		if (is_array($user)) {
			foreach ($user AS $u) {
				$postusersql .= " OR user.id = '$u'";
			}
		} else if (isset($user) && $user != "") {
			$postusersql = " OR user.id = '$user'";
		} else if ($user == null) {
			$postusersql = " OR user.id != 'q'";
		}

		$sql = "SELECT l.list, l.item, l.timestamp, list.name, movie.title, movie.id AS movieid, movie.poster, user.username AS user1, user.id AS user1id
		FROM `listitem` AS l
		LEFT JOIN list
		ON list.listid = l.list
		LEFT JOIN user
		ON user.id = list.user
LEFT JOIN movie
ON l.item = movie.id
WHERE user.id = 'start' $postusersql
ORDER BY l.timestamp DESC
		LIMIT 30";
		$feed = db_select($sql);

		return $feed;

}

function getPostsFeed($user = null) {

		if (is_array($user)) {
			foreach ($user AS $u) {
				$postusersql .= " OR userid = '$u'";
			}
		} else if (isset($user) && $user != "") {
			$postusersql = " OR userid = '$user'";
		} else if ($user == null) {
			$postusersql = " OR userid != 'q'";
		}

		$sql = "SELECT movie.title AS movietitle, movie.id AS movieid, movie.poster AS poster, reply.original AS origmsg,
		user.username AS user1, user.id AS user1id, post.message, post.emoji, post.timestamp AS timestamp
		FROM `post`
		LEFT JOIN movie
		ON post.movieid = movie.id
		LEFT JOIN user
		ON post.userid = user.id
		LEFT JOIN reply
		ON post.id = reply.reply
		WHERE (userid = 'start' $postusersql)
		ORDER BY `post`.`timestamp` DESC
		LIMIT 30";
		$feed = db_select($sql);

		return $feed;

}

function getVotesFeed($user) {

		if (is_array($user)) {

			foreach ($user AS $u) {
				$postusersql .= " OR vote.user = '$u'";
			}
		} else if (isset($user) && $user != "") {
			$postusersql .= " OR vote.user = '$user'";
		}

$sql = "SELECT movie.title AS movietitle, movie.id AS movieid, movie.poster AS poster,
							m2.title AS movietitle2, m2.id AS movieid2, m2.poster AS poster2,
		usa.username AS user1, usa.id AS user1id,
		op.username AS user2, op.id AS user2id,
		post.message, vote.post AS post, vote.upvote, vote.downvote, vote.timestamp AS timestamp
FROM vote
LEFT JOIN post
ON post.id = vote.post
LEFT JOIN movie
ON vote.post = movie.id
LEFT JOIN movie AS m2
ON post.movieid = m2.id
LEFT JOIN user AS usa
ON vote.user = usa.id
LEFT JOIN user AS op
ON post.userid = op.id
WHERE vote.user = 'start' $postusersql
ORDER BY `vote`.`timestamp`  DESC
LIMIT 30";

		$feed = db_select($sql);
//print_r($feed);
		return $feed;

}

function getRatingFeed($user) {

		if (is_array($user)) {

			foreach ($user AS $u) {
				$postusersql .= " OR r.user = '$u'";
			}
		} else if (isset($user) && $user != "") {
			$postusersql .= " OR r.user = '$user'";
		}

$sql = "SELECT u.username AS user1, u.id AS user1id, r.rating AS rating, m.id AS movieid, m.title, m.poster AS poster, r.timestamp
FROM `ratemovie` AS r
LEFT JOIN user AS u
ON r.user = u.id
LEFT JOIN movie AS m
ON r.movie = m.id
WHERE r.user = 'dude'
".$postusersql."
ORDER BY r.timestamp DESC
LIMIT 30";

		$feed = db_select($sql);
//echo $sql;
		return $feed;

}

function getFeed($user) {

	$ratingfeed = getRatingFeed($user);
	$tagfeed = getTagFeed($user);
	$listfeed = getListFeed($user);
	$postfeed = getPostsFeed($user);
	$votefeed = getVotesFeed($user);
	$feed1 = array_merge($postfeed, $votefeed, $listfeed, $tagfeed, $ratingfeed);
	//$feed1 = array_merge($feed1, );

	usort($feed1, function($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
	});

	//$feed = print_r($feed1, true);
	return $feed1;

}

function printFeed($feed) {
global $basethumburl;
	//$feed = getFeed($user);
	if (empty($feed)) {
		$print = "<span class='large'>Follow people to see their activity here</span>";
	} else {
		$print = "<table class='feed'>";
	}
	foreach ($feed AS $row) {
		$print .= "<tr>";
			$print .= "<td class='red relative paddingtop2'><div class='feedusername small grey'><a href='/profile.php?id=".$row["user1id"]."'>".$row["user1"]."</a></div>";
			//$print .= print_r($row, true);
			if ($row["rating"]) {
				$print .= "<i class='material-icons xlarge'>star</i>";
				$print .= "</td>";
				$print .= "<td class='large red'>";
				//$print .= "<a class='red' href='/movie.php?id=".$row["movieid"]."'>".$row["title"]."</a>";
				//$print .= "<div class='grey'>rated</div>";
				$print .= $row["rating"]/2;
				$print .= "</td>";
			} else if ($row["tag"]) {
				$print .= "<i class='material-icons xlarge'>label</i>";
				$print .= "</td>";
				$print .= "<td class='red'>";
				$print .= $row["tag"];
				$print .= "</td>";

			} else if ($row["list"]) {
				if ($row["name"] == "Recommend") {
					$print .= "<i class='material-icons xlarge'>favorite</i>";
				} else if ($row["name"] == "Watchlist") {
					$print .= "<i class='material-icons xlarge'>bookmark</i>";
				} else {
					$print .= "<i class='material-icons xlarge'>playlist_add</i>";
				}
				$print .= "</td>";
				$print .= "<td class='grey'>";
				$print .= "<a class='red' href='/movie.php?id=".$row["movieid"]."'>".$row["title"]."</a><br>added to<br><a class='red' href='/list.php?id=".$row["list"]."'>".$row["name"]."</a>";
				$print .= "</td>";

			} else if ($row["post"]) {

			$print .= "";
			if ($row["upvote"]) {
				$print .= "<i class='material-icons xlarge'>thumb_up</i>";
			} else if ($row["downvote"]) {
				$print .= "<i class='material-icons xlarge'>thumb_down</i>";
			}
			$print .= "</td>";
			$print .= "<td class='wordbreak '>";
			if (substr($row["post"], 0, 1) == "p") {



			//$print .= '<div class="bubble padding"><div class="quacktext">';
					$print .= '<div class="window padding small round inblock">'.$row["message"].'';
					//$print .= '</div>';
					$print .= '<div class="smalltext grey margintop0">- '.$row["user2"].'</div></div>';
						$print .= "</td>";
					//$print .= '</div>';
					$row["movieid"] = $row["movieid2"];
					$row["poster"] = $row["poster2"];
				} else {
					$print .= "<a href='/movie.php?id=".$row["movieid"]."' class='red large'>".$row["movietitle"]."</a>";
						$print .= "</td>";

				}


		} else {
			if ($row["origmsg"]) {
				$print .= "<i class='material-icons xlarge'>reply</i>";
			} else {
				$print .= getEmoji($row["emoji"])."</td>";//"<i class='material-icons xlarge'>comment</i></td>";
			}
			$print .= "<td class='wordbreak paddingleft'><div class='whitebubble padding'>".$row["message"]."</div></td>";

		}
		$print .= "<td class=''><a class='poster' href='/movie.php?id=".$row["movieid"]."'><img src='".$basethumburl.$row["poster"]."'></a></td>";

		$print .= "</tr>";
	}
	$print .= "</table>";
	return $print;
}


function makePermit($allcanread = 0, $allcaneditcontent = 0, $allcaneditlist) {
	$bin = $allcanread."".$allcaneditcontent."".$allcaneditlist;
	$dec = bindec($bin);
	return $dec;
}

function addToList($item, $list, $order = "0") {
	$time = time();
	$query = "INSERT INTO `listitem` (`list`, `item`, `timestamp`, `order`) VALUES ('$list', '$item', '$time', '$order');
	";
	$return = db_query($query);
}


function newList($name) {
	$name = mysqli_real_escape_string(db_connect(), $name);
	if (!strlen(trim($name)) == 0 && $name != "" && $name != null) {
		$listid = newId("l");
		$permit = makePermit();
		$user = $_SESSION["user"];
		$query = "INSERT INTO `".dbname."`.`list` (`user`, `listid`, `name`, `permit`, `deleted`) VALUES ('$user', '$listid', '$name', '$permit', false);
		";
		$return = db_query($query);
	}
}

function removeList($listid) {
	$user = $_SESSION["user"];
	$newid = newId("x");
	$sql = "UPDATE `".dbname."`.`list` SET `deleted` = 1, `name` = '$newid' WHERE `list`.`listid` = '$listid' AND list.user = '$user'";
	return db_query($sql);
}

function getLists($user) {
	$sql = "SELECT * FROM `list` WHERE user = '$user' AND deleted != 1";
	$lists = db_select($sql);
	return $lists;
}

function sortList($listid, $listorder) {
	//$sql = "UPDATE `listitem` SET `order` = '$ordernr' WHERE `listitem`.`list` = '$listid' AND `listitem`.`item` = '$listitem';";
	//$items = db_select($sql);
	$sql = "";
	$ordernr = 1;
	foreach ($listorder AS $listitem) {
		$sql .= "UPDATE `listitem` SET `order` = ".$ordernr." WHERE `listitem`.`list` = '".$listid."' AND `listitem`.`item` = '".$listitem."'; ";
		$ordernr ++;
	}
	$return = mysqli_multi_query(db_connect(), $sql);
	return $return;//print_r($listorder, true);
}

function getItemsFromList($list) {
	$user = $_SESSION["user"];
	$sql = "SELECT listitem.item, listitem.order, movie.poster, movie.title, movie.year, post.emoji, post.message, list.name
FROM `listitem`
LEFT JOIN list ON listitem.list = list.listid
LEFT JOIN ratemovie ON listitem.item = ratemovie.movie
LEFT JOIN movie ON listitem.item = movie.id
LEFT JOIN post ON listitem.item = post.movieid AND list.user = post.userid
LEFT JOIN reply ON reply.reply = post.id
WHERE listitem.list = '".$list."' AND reply.reply IS NULL
GROUP BY listitem.item
ORDER BY listitem.order ASC";
	$items = db_select($sql);

	return $items;
}

function printListItems($list) {
	$items = getItemsFromList($list);
	foreach($items AS $item) {
		$print .= $item["item"]."<br>";
	}
	return $print;
}

function printListSelect($selectedlist = "") {
	$user = $_SESSION["user"];
	$lists = getLists($user);
	$print = "<select class='select selectedlist'>";
	if (!empty($lists)) {
		foreach ($lists AS $list) {
			if ($selectedlist == $list["listid"]) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			$print .= "<option ".$selected." value='".$list["listid"]."'>".$list["name"]."</option>";
		}
	} else {
		$print .= "<option disabled>No list found</option>";
	}
	$print .= "</select>";
	return $print;
}

function printAddToList($item) {
	$user = $_SESSION["user"];
	$lists = getLists($user);
	$inlists = getListsForUserItem($user, $item);

	if (empty($lists)) {
		$print = "You must be <a href='/login.php'>signed in</a> to make lists";
	} else {
	$print = "<h3 class='marginbottom'>Add to</h3>";//print_r($inlists, true);

	foreach ($lists AS $list) {
		if (in_array($list["listid"], $inlists)) {
			$print .= "<div class='button addtolistbtn activebtn removefromlist' data-item='".$item."' data-list='".$list["listid"]."'>".$list["name"]."</div>";
		} else {
			$print .= "<div class='button addtolistbtn addtolist' data-item='".$item."' data-list='".$list["listid"]."'>".$list["name"]."</div>";
		}
	}
	}
	$print .= "";
	return $print;
}

function getListsForUserItem($user, $item) {
	$sql = "SELECT listitem.list AS list FROM `listitem`, list WHERE listitem.list = list.listid AND list.user = '".$user."' AND listitem.item = '".$item."' AND list.deleted != 1";
	$lists = db_select($sql);
	foreach ($lists AS $list) {
		$return[] = $list["list"];
	}
	return $return;
}

function removeFromList($item, $list) {
		$user = $_SESSION["user"];
		$sql = "SELECT * FROM `list` WHERE user = '$user' AND listid = '$list'";
		$lists = db_select($sql);
		if ($lists) {
			$sql = "DELETE FROM  `".dbname."`.`listitem` WHERE  `listitem`.`list` =  '".$list."' AND  `listitem`.`item` =  '".$item."' LIMIT 1";
			return db_query($sql);
		} else {
			return false;
		}
}

function removePost($post) {
	$user = $_SESSION["user"];
	$sql = "DELETE FROM `".dbname."`.`post` WHERE `post`.`id` = '".$post."' AND userid = '".$user."'";
	return db_query($sql);
}

function checkImage($url) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    $headers = curl_getinfo($ch);
    curl_close($ch);

	if ($headers['http_code']) {
		$return = $url;
	} else {
		$return = "https://images-na.ssl-images-amazon.com/images/M/MV5BMTUxMzQyNjA5MF5BMl5BanBnXkFtZTYwOTU2NTY3._V1_SX300.jpg";
	}

    return $return;
}

function checkImage2($posterurl) {

	$hdrs = @get_headers($posterurl);

    //echo @$hdrs[1]."\n";

    //return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false;


	/*if (!@GetImageSize($posterurl)) {
		$posterurl = "https://images-na.ssl-images-amazon.com/images/M/MV5BMTUxMzQyNjA5MF5BMl5BanBnXkFtZTYwOTU2NTY3._V1_SX300.jpg";
	}
	return $posterurl;*/
	return $posterurl;
}

function getFollowing($userid) {
	$userinfo = db_select("SELECT follows FROM  `follow` WHERE  `follower` =  '".$userid."'");
	foreach ($userinfo AS $user) {
		$users[] = $user["follows"];
	}
	return $users;
}



$timeforpageload = time();


$webpagetitle = "moviequack"
?>
