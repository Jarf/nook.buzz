<?php namespace App\Controllers;

use ScssPhp\ScssPhp\Compiler;

class Files extends BaseController
{
	public function styles(){

		$this->response->setHeader('Content-Type', 'text/css');

		$scss = new Compiler();
		$scss->addImportPath('scss');
		$scss->setFormatter('ScssPhp\ScssPhp\Formatter\Crunched');
		$css = $scss->compile('@import "main.scss";');
		print $css;
	}

	//--------------------------------------------------------------------

}