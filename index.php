<?php
//Download
$download = false;
if($_SERVER['QUERY_STRING'] == 'download') {
	header('Content-Type: text/plain; charset=utf-8');
	header('Content-Disposition: attachment; filename=README.md');
	
	$outputFile = fopen('php://output', 'w');
	
	$download = true;
}

if (!empty($_POST['editor'])) {
	include 'lib/markdownify/markdownify_extra.php';

	$md = new Markdownify_Extra(MDFY_LINKS_EACH_PARAGRAPH, MDFY_BODYWIDTH, MDFY_KEEPHTML);
	
	if (ini_get('magic_quotes_gpc')) {
		$_POST['input'] = stripslashes($_POST['editor']);
	}
	
	$output = $md->parseString($_POST['editor']);
	
	if($download) { fwrite($outputFile, $output); fclose($outputFile); exit(); }
} else {
	$_POST['editor'] = '';
}
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>GitHub Markup Editor</title>
	<meta name="keywords" content="github, readme, markups, editor, markdown"/>
	<meta name="description" content="GitHub Markup Editor - Create Readme files to Github easily"/>
	
	<script src="lib/jquery1.5.js" type="text/javascript" charset="utf-8"></script>
	
	<!--Editor Script-->
	<script type="text/javascript" src="lib/jwysiwyg/jquery.wysiwyg.js" charset="utf-8"></script>
	<script type="text/javascript" src="lib/jwysiwyg/controls/wysiwyg.image.js"></script>
	<script type="text/javascript" src="lib/jwysiwyg/controls/wysiwyg.link.js"></script>
	<script type="text/javascript" src="lib/jwysiwyg/controls/wysiwyg.table.js"></script>
	<script type="text/javascript" charset="utf-8">
	//<![CDATA[
		$(document).ready(function(){
			$('#editor').wysiwyg({
			  controls: {
			  	undo : { visible : true },
				redo : { visible : true },
				
				bold          : { visible : true },
				italic        : { visible : true },
				underline     : { visible : false },
				strikeThrough : { visible : false },
				
				justifyLeft   : { visible : false },
				justifyCenter : { visible : false },
				justifyRight  : { visible : false },
				justifyFull   : { visible : false },
	
				indent  : { visible : false },
				outdent : { visible : false },
	
				subscript   : { visible : false },
				superscript : { visible : false },								
				
				insertOrderedList    : { visible : true },
				insertUnorderedList  : { visible : true },
				insertHorizontalRule : { visible : true },					
				
				cut   : { visible : false },
				copy  : { visible : false },
				paste : { visible : false },
				html  : { visible: true },
				removeFormat : { visible: false },
				increaseFontSize : { visible : false },
				decreaseFontSize : { visible : false }		
			  },
			  events: {
				click: function(event) {
					if ($("#click-inform:checked").length > 0) {
						event.preventDefault();
						alert("You have clicked jWysiwyg content!");
					}
				}
			  }
			});
	
			//$('#editor').wysiwyg("insertHtml", "<p></p><h1>Initial content</h1><p>Esse é apenas um Teste!<br></p><ol><li>Aqui tem</li><li>uma lista</li><li>numerada</li></ol><ul><li>e aqui</li><li>tem</li><li>uma lista</li><li>sem ser numerada</li></ul><p><br></p><h2>Novo titulo</h2><p><br>E no meu disso tudo um <a href='http://google.com'>link</a> bem aqui.</p>");
		});
	//]]>
	</script>
	<link rel="stylesheet" href="lib/jwysiwyg/jquery.wysiwyg.css" type="text/css" media="screen" charset="utf-8" />
	<style type="text/css" media="screen">
		#container{ width:600px; }
		textarea{ width:500px; height:300px; }
	</style>
</head>
<body>
	<div id="container">
		<form action="" method="post" name="editor-html" id="editor-html">
			<textarea id="editor" name="editor"><?php echo $_POST['editor']; ?></textarea>
			<input type="submit" name="send" value="enviar" />
		</form>
	</div>
	
	<?php if (!empty($_POST['editor'])): ?>
	<div id="markup-source">
		<pre><?php echo htmlspecialchars($output, ENT_NOQUOTES, 'UTF-8'); ?></pre>
		<form action="?download" method="post" name="editor-html" id="editor-html">
			<textarea id="editor-code" name="editor" style="display:none;"><?php echo $_POST['editor']; ?></textarea>
			<input type="submit" name="send" value="download" />
		</form>
	</div>
	<?php endif; ?>
	
</body>
</html>