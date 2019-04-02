<?php
$dir = 'https://htmlstream.com/preview/front-v2.1.1/html/home/';
$path = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'front-v2.1.1/html/home';

echo '<form method="post"><ul>';
if (isset($_POST['paso2'])) {
	extract($_POST);
	$urlContent = file_get_contents($dir);
	$dom = new DOMDocument();
	@$dom->loadHTML($urlContent);
	$xpath = new DOMXPath($dom);

	foreach ($urls as $key => $url) {		

		if (!filter_var($dir.$url, FILTER_VALIDATE_URL) === false) {

	    	if (!is_dir($path.DIRECTORY_SEPARATOR.dirname($url))){
				mkdir($path.DIRECTORY_SEPARATOR.dirname($url),0777, true);
				echo "<li>Carpeta ".basename($path.DIRECTORY_SEPARATOR.dirname($url))." fue creado en la ubicacion '".$path.DIRECTORY_SEPARATOR.dirname($url)."' con exito</li>";
			}

			$postContent = file_get_contents($dir.$url);
			$file = basename($url); 
			$fichero = $path.DIRECTORY_SEPARATOR.dirname($url).DIRECTORY_SEPARATOR.$file;
			if (!file_exists($fichero)) {
				$new_file = fopen($fichero, "w+");
				if ($new_file === false) {
				   echo "opening '$file' failed";
				}

				if (fwrite(($new_file), $postContent)){       
				   fclose($new_file);
				   echo "<li>Archivo ".$file." fue creado en la ubicacion '".$path.DIRECTORY_SEPARATOR.$url."' con exito</li>";
				}	
			}		
	    }
	}

}else if(isset($_POST['paso1'])){
	echo '<input type="hidden" name="paso2" value="1">';
	extract($_POST);
	
	if (!is_dir($path.DIRECTORY_SEPARATOR.dirname($url))){
		mkdir($path.DIRECTORY_SEPARATOR.dirname($url),0777, true);
		echo "<li>Carpeta ".basename($path.DIRECTORY_SEPARATOR.dirname($url))." fue creado en la ubicacion '".$path.DIRECTORY_SEPARATOR.dirname($url)."' con exito</li>";
	}

	$postContent = file_get_contents($dir.$url);
	$file = basename($url); 
	$fichero = $path.DIRECTORY_SEPARATOR.dirname($url).DIRECTORY_SEPARATOR.$file;
	if (!file_exists($fichero)) {
		$new_file = fopen($fichero, "w+");
		if ($new_file === false) {
		   echo "opening '$file' failed";
		}

		if (fwrite(($new_file), $postContent)){       
		   fclose($new_file);
		   echo "<li>Archivo ".$file." fue creado en la ubicacion '".$path.DIRECTORY_SEPARATOR.$url."' con exito</li>";
		}	
	}

	echo "<hr>";

	$urlContent = file_get_contents($dir.$url);
	$dom = new DOMDocument();
	@$dom->loadHTML($urlContent);
	$xpath = new DOMXPath($dom);

	$hrefs = $xpath->evaluate("/html/head//link");
		echo "<h2>links</h2>";
		for($i = 0; $i < $hrefs->length; $i++){
		    $href = $hrefs->item($i);
		    $url = $href->getAttribute('href');
		    $url = filter_var($url, FILTER_SANITIZE_URL);
		    $file = explode('/', $url);

			echo '<li><input type="checkbox" name="urls[]" value="'.$url.'" '.$checked.'>'.$url.'</li>';

			preg_match_all('/url\(([\s])?([\"|\'])?(.*?)([\"|\'])?([\s])?([\)|\?|\#])/i', @file_get_contents($dir.$url), $urls);
			echo "<ol>Url";
			$urlss = array();
			foreach ($urls[3] as $urlcss) {
				$filecss = explode('/', $urlcss);
				if (!(in_array(end($filecss), $urlss))) {
		    		echo '<li><input type="checkbox" name="urls[]" value="'.str_replace(end($file), "", $url).$urlcss.'" '.$checked.'>'.$urlcss.'</li>';
		    		$urlss[$i] = end($filecss);
		   		}
				
			}
			echo "</ol>";
		}

		$jss = $xpath->evaluate("/html/head//script");
		echo "<h2>js</h2>";
		for($i = 0; $i < $jss->length; $i++){
		    $js = $jss->item($i);
		    $url = $js->getAttribute('src');
		    $url = filter_var($url, FILTER_SANITIZE_URL);
		    $file = explode('/', $url);
		    echo '<li><input type="checkbox" name="urls[]" value="'.$url.'" '.$checked.'>'.$url.'</li>';
		}

		$imgs = $xpath->evaluate("/html/body//img");
		echo "<h2>img</h2>";
		$urls = array();
		for($i = 0; $i < $imgs->length; $i++){
		    $img = $imgs->item($i);
		    $url = $img->getAttribute('src');
		    $url = filter_var($url, FILTER_SANITIZE_URL);
		    $file = explode('/', $url);
		    if (!(in_array(end($file), $urls))) {
		    	echo '<li><input type="checkbox" name="urls[]" value="'.$url.'" '.$checked.'>'.$url.'</li>';
		    	$urls[$i] = end($file);
		   	}	
		}

		$scripts = $xpath->evaluate("/html/body//script");
		echo "<h2>script</h2>";
		for($i = 0; $i < $scripts->length; $i++){
		    $script = $scripts->item($i);
		    $url = $script->getAttribute('src');
		    $url = filter_var($url, FILTER_SANITIZE_URL);
		    $file = explode('/', $url);
		    echo '<li><input type="checkbox" name="urls[]" value="'.$url.'" '.$checked.'>'.$url.'</li>';
		}

	echo "<button type='submit'>Paso 3</button>";
}else{
	echo '<input type="hidden" name="dir" value="'.$dir.'">';
	echo '<input type="hidden" name="paso1" value="1">';

	$urlContent = file_get_contents($dir);
	$dom = new DOMDocument();
	@$dom->loadHTML($urlContent);
	$xpath = new DOMXPath($dom);

	$scripts = $xpath->evaluate("/html/body//a");
	echo "<h2>href</h2>";
	echo "<p>seleccione el archivo que desea copiar</p>";
	$urls = array();
	echo '<li><select name="url">';

	for($i = 0; $i < $scripts->length; $i++){
	    $script = $scripts->item($i);
	    $url = $script->getAttribute('href');
	    $url = filter_var($url, FILTER_SANITIZE_URL);
	    $file = explode('/', $url);
	    if (!(in_array(end($file), $urls))) {
	    	echo '<option value="'.$url.'">'.$url.'</option>';
	    	$urls[$i] = end($file);
	   	}	  
	}
	echo '</li></select>';
	echo '<li><input type="checkbox" name="checked" value="checked">Todos los archivos en Checked</li><hr>';

	echo "<button type='submit'>Paso 2</button>";
}
echo '</ul></form>';