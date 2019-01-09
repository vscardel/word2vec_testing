<?php

	$fila = array(); //fila que será usada na bfs

	$myFile = fopen("corpus.txt","a");

	function get_links($pagina){ // funcao com expressao regular simples para coletar links

		global $fila;
		$inicio = "https://pt.wikipedia.org";
		$pattern = '/\/wiki\/[a-zA-Z_1-9%]+/';
		preg_match_all($pattern, $pagina, $matches, PREG_PATTERN_ORDER);
		$count = count($matches[0]);

		for($i=0; $i<$count;$i++){ // varre matches e armazena os links em um vetor $links
			$var = $matches[0][$i];
			$var = $inicio.$var; //concatena para criar link completo

			if( count($fila) == 0){ //checa se link ja existe no array
				array_push($fila, $var);
			}
			else{
				if(!in_array($var,$fila)){
					array_push($fila, $var);
				}
			}
		}
	}

	function escreve_text_arquivo($pagina){ //funcao que extrai o texto da pag html e grava em arquivo

		global $myFile;
		$pagina = strip_tags($pagina,'<p>'); //tira tags html do texto deixando apenas as tags <p>
		$pattern = '/<p>(.*)\s<\/p>/';
		preg_match_all($pattern, $pagina, $matches, PREG_PATTERN_ORDER);
		$cont = count($matches[1]);

		for($i=0;$i<$cont;$i++){
			fwrite($myFile, $matches[1][$i]);
		}
	}

	$txt = file_get_contents("https://pt.wikipedia.org/wiki/Johann_Sebastian_Bach"); //raiz
	get_links($txt);
	escreve_text_arquivo($txt);

	$cont = 1;

	$visitados = array();

	while($cont <= 10000){ // itera no maximo sobre 10 links

		$link_atual = array_shift($fila);

		if(!in_array($link_atual, $visitados)){
			$text_atual = file_get_contents($link_atual);
			array_push($visitados, $link_atual);
		}

		if($text_atual){
			get_links($text_atual);
			escreve_text_arquivo($text_atual);
		}

		$cont++;
	}

	$destination = 'corpus.zip';
	$zip = new ZipArchive();
	$zip->open($destination, ZipArchive::CREATE);
	$zip->addFile("corpus.txt");
	
	$zip->close();
	fclose($myFile);

?>
