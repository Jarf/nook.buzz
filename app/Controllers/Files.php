<?php namespace App\Controllers;

use ScssPhp\ScssPhp\Compiler;

class Files extends BaseController
{
	public function styles(){

		$this->response->setHeader('Content-Type', 'text/css');
		$csspath = FCPATH . 'css/main.css';
		if(!file_exists($csspath)){
			$scss = new Compiler();
			$scss->addImportPath('scss');
			$scss->setFormatter('ScssPhp\ScssPhp\Formatter\Crunched');
			$css = $scss->compile('@import "main.scss";');
			file_put_contents($csspath, $css);
		}else{
			$css = file_get_contents($csspath);
		}


		print $css;
	}

	//--------------------------------------------------------------------

}