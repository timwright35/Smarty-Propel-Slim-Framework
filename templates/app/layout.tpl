<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>{block name=title}{#pageTitle#}{/block}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="{#pageDescription#}">
  <meta name="author" content="{#pageAuthor#}">

  <link href="css/bootstrap.css" rel="stylesheet">
  <style type="text/css">
  
    {block name=custom_css}
	
	{/block}

  </style>
  {block name=custom_css_files}

  {/block}

  <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
  <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body id="body">

<div class="container-fluid">

{block name=content}

{/block}

</div>
<!--/.fluid-container-->

<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
{block name=custom_js_files}

{/block}
{block name=custom_js}

{/block}
</body>
</html>
