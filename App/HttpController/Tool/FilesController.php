<?php


namespace App\HttpController\Tool;


use App\HttpController\JwtController;
use EasySwoole\Http\Message\UploadFile;

class FilesController extends JwtController
{

	// 上传图像
	public function image()
	{
		$request=  $this->request();
		$file = $request->getUploadedFile('image');

		if ( ! $file ) {
			return $this->apiResult( '缺少 image 字段' );
		}

		if ( $error = $file->getError() ) {
			return $this->apiResult( $error );
		}

		$type = explode('.', $file->getClientFilename());

		$imageName = time() . '-' .rand(10000,99999) . '.' . array_pop( $type );
		$path = './images/avatar/'. $imageName;

		if ( $file->moveTo($path) ) {
			return $this->writeJson(0,['url'=> \EasySwoole\EasySwoole\Config::getInstance()->getConf('URL') . 'images/avatar/'. $imageName ]);
		} else {
			return $this->apiResult('上传失败');
		}


	}


}
