<?php

	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	/**
	 * Netflix Delivery Manifest Generator
	 */

	class NetflixProduct {
		/**
		 * Name of the rights holder of the content
		 * @var string
		 */
		public $ContentProvider;

		/**
		 * First year the content was available
		 * @var int
		 */
		public $FirstReleaseYear;

		/**
		 * Name of the Movie or TV Episode
		 * @var string
		 */
		public $OriginalTitle;

		/**
		 * @var array(NetflixFile)
		 */
		public $Files = array();
	}

	class NetflixTvEpisode extends NetflixProduct {
		/**
		 * Name of the series
		 * @var string
		 */
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
		public $TextLanguage;

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
		public $Language;

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

	function Generate_TvEpisode() {
		$episode = new NetflixTvEpisode();
		$episode->ContentProvider = $_POST['inputContentProvider'];
		$episode->ShowName = $_POST['inputShowName'];
		$episode->OriginalTitle = $_POST['inputOriginalTitle'];
		$episode->FirstReleaseYear = $_POST['inputFirstReleaseYear'];

		$videoFile = new NetflixVideo($_POST['inputVideoFileName']);
		$videoFile->TextLanguage = $_POST['optionsContentLanguage'];
		$audio = new NetflixAudio();
		$audio->Language = $_POST['optionsContentLanguage'];

		switch ($_POST['optionsAudioChannels']) {
			case '2-0':
				$audio->Channels = array('L', 'R');
				break;

			case '5-1';
				$audio->Channels = array('L', 'R', 'C', 'LFE', 'LS', 'RS');
				break;

			case '7-1';
				$audio->Channels = array('L', 'R', 'C', 'LFE', 'LS', 'RS', 'LT', 'RT');
				break;
			
			default:
				throw new Exception($_POST['optionsAudioChannels']);
				
		}
		
		$videoFile->Audio[] = $audio;
		$episode->Files[] = $videoFile;

		GenerateXml($episode);
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		switch ($_POST['optionsContentType']) {
			case 'TV_EPISODE':
				Generate_TvEpisode();
				break;
			
			default:
				throw new Exception($_POST['optionsContentType']);
				
		}
	}

	// Fake Episode
	
