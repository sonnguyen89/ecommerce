<?php
/*
 * News Image Helper
 * 
 * 
 * @author magento
 */

class Magentostudy_News_Helper_Image extends Mage_Core_Helper_Abstract
{
	/*
	 * Media path to extension images
	 * 
	 * @var string
	 * 
	 */
	
	const MEDIA_PATH = 'news';
	
	/**
	 * maximum size for image in bytes
	 * Default value is 1M
	 * 
	 * @var int
	 */
	const MAX_FILE_SIZE = 1048576;
	
	/**
	 * Minimum image height in pixels
	 * 
	 * @var int
	 */
	const MIN_HEIGHT = 50;
	
	/**
	 * Maximum image height in pixel
	 * 
	 * @var int
	 */
	const MAX_HEIGHT = 800;
	
	/**
	 * Minimum image Width in pixel
	 *
	 * @var int
	 */
	const MIN_WIDTH = 50;
	
	/**
	 * Maximum image Width in pixel
	 *
	 * @var int
	 */
	const MAX_WIDTH = 800;
	
	
	
	/**
	 * Array of image size limitation
	 *
	 * @var array
	 */
	protected $_imagesize = array(
		'minheight' =>self::MIN_HEIGHT,
		'minwidth' =>self::MIN_WIDTH,
		'maxheight' =>self::MAX_HEIGHT,
		'maxwidth' =>self::MAX_WIDTH,
	);
	
	/**
	 * Array of allowed file extensions
	 *
	 * @var array
	 */
	protected $_allowedExtensions = array('jpg','gif','png');
	
	/*
	 * Return the base media directory for news Item images
	 * 
	 * @return string
	 */
	public function getBaseDir()
	{
		return Mage::getBaseDir('media').DS.self::MEDIA_PATH;
	}
	
	/**
	 * return the base URL for News Item images
	 * 
	 * @return string
	 */
	public function getBaseUrl()
	{
		return Mage::getBaseUrl('media').'/'.self::MEDIA_PATH;
	}
	
	/**
	 * remove news item image by image filename
	 * 
	 * @param string $imageFile
	 * @return bool
	 */
	public function removeImage($imageFile)
	{
		$io = new Varien_Io_File();
		$io->open(array('path'=>$this->getBaseDir()));
		
		if ($io->fileExists($imageFile)){
			
			return $io->rm($imageFile);
		}
		return false;
		
	}
	/*
	 * Upload image and return uploaded image file name or false
	 * 
	 * @throws Mage_Core_Exception
	 * @param string $scope the request key for file
	 * @return bool|string
	 */
	
	public function uploadImage($scope)
	{
		$adapter = new Zend_File_Transfer_Adapter_Http();
		$adapter->addValidator('ImageSize',true,$this->_imagesize);
		$adapter->addValidator('Size',true,self::MAX_FILE_SIZE);
		if ($adapter->isUploaded()){
			//validate image
			if (!$adapter->isValid($scope))
			{
				Mage::throwException(Mage::helper('magentostudy_news')->__('Upload image is not valid'));
			}
			$upload = new Varien_File_Uploader($scope);
			$upload->setAllowCreateFolders(true);
			$upload->setAllowedExtensions($this->_allowedExtensions);
			$upload->setAllowRenameFiles(true);
			$upload->setFilesDispersion(false);
			
			if ($upload->save($this->getBaseDir())){
					return $upload->getUploadedFileName();
			}
		}
		return false;
		
	}
	
	/**
	 * return URL for resized new item image
	 * 
	 * @param Magentostudy_news_Model_Item $item
	 * @param integer $width
	 * @param integer $height
	 * @return bool|string
	 */
	public function resize(Magentostudy_News_Model_News $item, $width, $height= null)
	{
		if (!$item->getImage()){
			return false;
		}
		
		if ($width < self::MIN_WIDTH || $width > self::MAX_WIDTH){
			return false;
		}
		$width= (int) $width;
		
		if (!is_null($height)){
			if ($height < self::MIN_HEIGHT||$height > self::MAX_HEIGHT){
				return false;
			}
			$height=(int)$height;
		}
		$imageFile = $item->getImage();
		$cacheDir= $this->getBaseDir().DS.'cache'.DS.$width;
		$cacheUrl= $this->getBaseUrl().'/'.'cache'.'/'.$width.'/';
		
		$io = new Varien_Io_File();
		$io->checkAndCreateFolder($cacheDir);
		$io->open(array('path'=>$cacheDir));
		
		if($io->fileExists($iamgeFile)){
			return $cacheUrl. $imageFile;
		}
		
		try{
			$image =new Varien_Image($this->getBaseDir().DS.$imageFile);
			$image->resize($width, $height);
			$image->save($cacheDir.DS.$imageFile);
			return $cahceUrl . $imageFile;
		}catch (Exception $e){
			Mage::logException($e);
			return false;
			
		}
		
	}
	/**
	 * remove folder with cached images
	 * 
	 * @return boolean
	 */
	public function flushImageCache()
	{
     $cacheDir = $this->getBaseDir(). DS . 'cache' .DS;
     $io = new Varien_Io_File();
     if ($io->fileExist($cacheDir,false)){
     	return $io->rmdir($cacheDir,true);
     }
     return true;
	}
}