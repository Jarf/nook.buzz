<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
	<meta http-equiv="content-language" content="en"/>
	<meta name="google" content="notranslate" />

	<meta property="og:type" content="website"/>
    <meta property="og:title" content="nook.buzz"/>
    <meta property="og:url" content="<?=current_url()?>"/>
    <meta property="og:description" content="A mobile friendly tool to help track bugs/insects in Animal Crossing: New Horizons"/>
    <meta property="og:image" content="<?=base_url('/images/icon-honeybee.png')?>"/>

	<link rel="stylesheet" href="/main.css" type="text/css" media="screen">

	<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16" />


    <title>nook.buzz</title>
</head>
<body>
	<?php
	if(!isset($hemisphere)){
		$hemisphere = 'north';
	}
	?>
	<div class="headermenu">
		<a href="/fish/<?=strtolower(date('F'))?>/<?=$hemisphere?>" class="headerbutton fish <?=strpos(uri_string(), 'fish') === 0 ? 'active' : ''?>">
		</a>
		<a href="/insect/<?=strtolower(date('F'))?>/<?=$hemisphere?>" class="headerbutton insect <?=strpos(uri_string(), 'insect') === 0 ? 'active' : ''?>">
		</a>
	</div>