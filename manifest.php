<?php

	/**
	 * Netflix Delivery Manifest Generator
	 */

	class NetflixProduct {
		public $ContentProvider;
		public $FirstReleaseYear;
		public $OriginalTitle;
	}

	class NetflixTvEpisode extends NetflixProduct {
		public $ShowName;
		public $Type = 'TV_EPISODE';
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

		// Output
		$output = $xml->asXML();
		$output = str_replace('<?xml version="1.0"?>', '<?xml version="1.0" encoding="UTF-8"?>', $output);
		echo htmlentities($output);
		die();
	}

	// Fake Episode
	$episode = new NetflixTvEpisode();
	$episode->ContentProvider = 'Augusto';
	$episode->ShowName = 'SHOW NAME';
	$episode->OriginalTitle = 'EPISODE TITLE';
	$episode->FirstReleaseYear = 2012;

	generateXml($episode);
