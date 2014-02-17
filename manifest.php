<?php

	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	/**
	 * Netflix Delivery Manifest Generator
	 */

	class NetflixProduct {
		public $ContentProvider;
		public $FirstReleaseYear;
		public $OriginalTitle;

		/**
		 * @var array(NetflixFile)
		 */
		public $Files = array();
	}

	class NetflixTvEpisode extends NetflixProduct {
		public $ShowName;
		public $Type = 'TV_EPISODE';
	}

	class NetflixFile {
		public function NetflixFile($fileName) {
			$this->FileName = $fileName;
		}

		/**
		 * File name
		 * @var string
		 */
		public $FileName;
	}

	class NetflixVideo extends NetflixFile {
		/**
		 * Language of the title cards and credits
		 * @var string
		 */
		public $TextLanguage = 'en';

		/**
		 * Audio embedded in the video file
		 * @var array(NetflixAudio)
		 */
		public $Audio = array();
	}

	class NetflixAudio {
		/**
		 * Spoken language
		 * @var string
		 */
		public $Language = 'en';

		/**
		 * Audio channel mapping, in order
		 * @var array(string)
		 */
		public $Channels = array();
	}

	/**
	 * Process the data and output the XML
	 */
	function generateXml($product) {
		// Base element
		$xml = new SimpleXmlElement('<Product/>');
		$xml->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

		// Product Information
		$productInformation = $xml->addChild('ProductInformation');

		// Content Prodivder
		$productInformation->addChild('ContentProvider', $product->ContentProvider);

		// Type
		$productInformation->addChild('Type', $product->Type);

		// Original Title
		$originalTitle = $productInformation->addChild('OriginalTitle');
		$originalTitle->addChild('Name', $product->OriginalTitle);
		$originalTitle->addChild('LanguageCode', 'en');

		// Show Name
		$productInformation->addChild('ShowName', $product->ShowName);

		// First Release Year
		$productInformation->addChild('FirstReleaseYear', $product->FirstReleaseYear);

		// Packages
		$packages = $xml->addChild('ProviderPackages');

		// Package
		$package = $packages->addChild('ProviderPackage');

		// Package Type
		$package->addChild('ProviderPackageType', $product->Type);

		// Version Region
		$versionRegion = $package->addChild('VersionRegion');
		$versionRegion->addChild('VersionRegionProviderDescription', 'International');

		// Files
		$files = $package->addChild('Files');

		foreach ($product->Files as $file) {
			$fileXml = $files->addChild('File');
			$fileXml->addChild('FileName', $file->FileName);

			$assets = $fileXml->addChild('Assets');

			// What type of file is it?
			switch (get_class($file)) {
				case 'NetflixVideo';
					$video = $assets->addChild('Video');
					$text = $video->addChild('TextInVideo');
					$text->addChild('ContentLanguageCode', $file->TextLanguage);

					$audios = $assets->addChild('Audios');

					foreach ($file->Audio as $audio) {
						$a = $audios->addChild('Audio');
						$a->addChild('LanguageCode', $audio->Language);
						$a->addChild('AudioChannelMapping', implode('_', $audio->Channels));
					}
					break;
				
				default:
					throw new Exception(get_class($file));
			}
		}

		// Output
		$output = $xml->asXML();
		$output = str_replace('<?xml version="1.0"?>', '<?xml version="1.0" encoding="UTF-8"?>', $output);
		Header('Content-type: text/xml');
		echo $output;
		die();
	}

	// Fake Episode
	$episode = new NetflixTvEpisode();
	$episode->ContentProvider = 'Augusto';
	$episode->ShowName = 'SHOW NAME';
	$episode->OriginalTitle = 'EPISODE TITLE';
	$episode->FirstReleaseYear = 2012;

	$videoFile = new NetflixVideo('FILE_NAME');
	$audio = new NetflixAudio();
	$audio->Channels = array('L', 'R', 'C', 'LFE', 'LS', 'RS', 'LT', 'RT');
	$videoFile->Audio[] = $audio;
	$episode->Files[] = $videoFile;

	//GenerateXml($episode);
