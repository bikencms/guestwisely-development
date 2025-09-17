<?php

if (!class_exists('API365')) {

class API365
{
	const API_BASE_URL = "https://dev83.365villas.com/vros/api";
	const API_BASE_URL_PRIVATE = "https://dev83.365villas.com/vros";
	const API_CACHE_GROUP = "365APICacheGroup";
	const API_TIMEOUT = 30;
	const API_LOGGING = false;

	//Get the API base URL.
	//We can override the above so we can set a testing URL.
	private static function APIBaseURL()
	{
		$baseURL = API365::API_BASE_URL;
		if(defined('VILLAS_365_API_URL') && !is_null(VILLAS_365_API_URL))
		{
			$baseURL = VILLAS_365_API_URL;
		}
		return $baseURL;
	}

	//Get the API base URL.
	//We can override the above so we can set a testing URL.
	private static function APIBaseURLPrivate()
	{
		$baseURLPrivate = API365::API_BASE_URL_PRIVATE;
		if(defined('VILLAS_365_API_URL_PRIVATE') && !is_null(VILLAS_365_API_URL_PRIVATE))
		{
			$baseURLPrivate = VILLAS_365_API_URL_PRIVATE;
		}
		return $baseURLPrivate;
	}

	private static function Cache365Calls()
	{
		if(!class_exists('Helpers365') || (class_exists('Helpers365') && Helpers365::Cache365Calls()))
		{
			return true;
		}

		return false;
	}

	private static function APITimeout()
	{
		$timeout = API365::API_TIMEOUT;
		if(defined('VILLAS_365_API_TIMEOUT') && !is_null(VILLAS_365_API_TIMEOUT) && is_numeric(VILLAS_365_API_TIMEOUT) && (VILLAS_365_API_TIMEOUT >= 30))
		{
			$timeout = VILLAS_365_API_TIMEOUT;
		}
		return $timeout;
	}

	private static function APILogging()
	{
		$logging = API365::API_LOGGING;
		if(defined('VILLAS_365_API_LOGGING') && !is_null(VILLAS_365_API_LOGGING) && is_bool(VILLAS_365_API_LOGGING))
		{
			$logging = VILLAS_365_API_LOGGING;
		}
		return $logging;
	}

	private static function APILoggingDomain()
	{
		$loggingDomain = null;
		if(defined('VILLAS_365_API_LOGGING_DOMAIN') && !is_null(VILLAS_365_API_LOGGING_DOMAIN) && is_string(VILLAS_365_API_LOGGING_DOMAIN) && (trim(VILLAS_365_API_LOGGING_DOMAIN) !== ""))
		{
			$loggingDomain = VILLAS_365_API_LOGGING_DOMAIN;
		}
		return $loggingDomain;
	}

	private static function CallEndpoint($endpoint, $key, $data = [], $method = "POST", $privateAPI = false, $includeLanguage = false, $additionalUrlParameters = null)
	{
		//mode: multiple brand
		$data['domain'] = get_option('home');

		$url = null;
		if($privateAPI)
		{
			$url = API365::APIBaseURLPrivate() . "/" . $endpoint;
		}
		else
		{
			$url = API365::APIBaseURL() . "/" . $endpoint . "/owner_token/" . $key;
		}

		if($includeLanguage && $method === "GET")
		{
			$url .= "?lang=" . Helpers365::Get365LanguageCode();
		}
		elseif($includeLanguage && !array_key_exists("lang", $data))
		{
			$data["lang"] = Helpers365::Get365LanguageCode();
		}

		if(!is_null($additionalUrlParameters) && is_array($additionalUrlParameters) && (count($additionalUrlParameters) > 0))
		{
			if(!str_contains($url, "?"))
			{
				$url .= "?";
			}
			else
			{
				$url .= "&";
			}

			$url .= http_build_query($additionalUrlParameters);
		}

		$cacheKey = $url . "?" . http_build_query($data);

		if(API365::Cache365Calls())
		{
			$cachedResponseObject = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
			if($cachedResponseObject !== false)
			{
				return $cachedResponseObject;
			}
		}
		
		$remoteOutput = wp_remote_request($url, [
			"method" => $method,
			"body" => $data,
			"timeout" => API365::APITimeout()
		]);

		if(API365::APILogging())
		{
			$site = get_site();
			$domain = "";
			if(!is_null($site))
			{
				$domain = $site->domain;
			}

			$loggingDomain = API365::APILoggingDomain();
			if(is_null($loggingDomain) || (!is_null($loggingDomain) && ($loggingDomain === $domain)))
			{
				$exception = new Exception();

				$logMessage = PHP_EOL . "------------------------- Start -------------------------" . PHP_EOL . PHP_EOL;
				$logMessage .= "Site: " . $domain . PHP_EOL . PHP_EOL;

				$logMessage .= "----------------------------" . PHP_EOL . PHP_EOL;
				
				$logMessage .= "Stack trace: " . $exception->getTraceAsString() . PHP_EOL . PHP_EOL;
				
				$logMessage .= "----------------------------" . PHP_EOL . PHP_EOL;

				$logMessage .= "Request URL: " . $url . PHP_EOL;
				$logMessage .= "Request method: " . $method . PHP_EOL;
				$logMessage .= "Request timeout: " . API365::APITimeout() . PHP_EOL;
				$logMessage .= "Request data: " . json_encode($data) . PHP_EOL . PHP_EOL;

				$logMessage .= "----------------------------" . PHP_EOL . PHP_EOL;

				$logMessage .= "Response: " . json_encode($remoteOutput["response"]) . PHP_EOL;
				$logMessage .= "Response body: " . $remoteOutput["body"] . PHP_EOL . PHP_EOL;
				$logMessage .= "-------------------------- End --------------------------";

				unset($exception);

				Helpers365::LogToFile($logMessage);
			}
		}

		//Check if we have an error from the above and return this if we do.
		if(is_wp_error($remoteOutput))
		{
			return $remoteOutput;
		}

		$response = $remoteOutput["response"];
		$responseObject = json_decode($remoteOutput["body"]);

		if($response["code"] !== 200 || is_null($responseObject))
		{
			return null;
		}

		if(API365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $responseObject, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}
		
		return $responseObject;
	}

	public static function GetDefaultSettings($data = [])
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);
		$apiResponse = API365::CallEndpoint("default-config", $key, $data, "GET");

		if(!is_null($apiResponse) && !is_wp_error($apiResponse))
		{
			$cacheKey = API365::APIBaseURL() . "/default-config/owner_token/" . $key . "?" . http_build_query($data) . "keyed";
			if(API365::Cache365Calls())
			{
				$cachedSettings = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
				if($cachedSettings !== false)
				{
					return $cachedSettings;
				}
			}

			if(API365::Cache365Calls())
			{
				wp_cache_set($cacheKey, $apiResponse, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
			}

			return $apiResponse;
		}

		return null;
	}

	//Pass no data to get all properties
	public static function GetAllPropertyInfo($data = [])
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$apiResponse = API365::CallEndpoint("get-all-property-info", $key, $data, "POST", false, true);

		if(!is_null($apiResponse))
		{
			if(property_exists($apiResponse, "properties"))
			{
				$properties = $apiResponse->properties;

				//If we have only one property then return it.
				if(!is_array($properties))
				{
					return $properties;
				}

				$includeLanguage = "lang=" . Helpers365::Get365LanguageCode();
				$cacheKey = API365::APIBaseURL() . "/get-all-property-info/owner_token/" . $key . "?" . $includeLanguage . "&" . http_build_query($data) . "keyed";
				if(API365::Cache365Calls())
				{
					$cachedKeyedProperties = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
					if($cachedKeyedProperties !== false)
					{
						return $cachedKeyedProperties;
					}
				}

				//If we have an array of properties then create a new array keyed by each property ID and return this.
				$keyedProperties = [];
				foreach($properties as $property)
				{
					if(!array_key_exists($property->id, $keyedProperties))
					{
						$keyedProperties[$property->id] = $property;
						$keyedProperties[$property->id]->slug = Helpers365::MakePropertySlug($property);
					}
				}

				$propertiesObject = [
					"cacheKey" => $cacheKey,
					"count" => count($keyedProperties),
					"perPage" => count($keyedProperties),
					"totalPages" => 1,
					"properties" => [
						0 => $keyedProperties
					]
				];

				if(API365::Cache365Calls())
				{
					wp_cache_set($cacheKey, $propertiesObject, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
				}

				return $propertiesObject;
			}
		}

		return null;
	}

	public static function GetAllPropertiesGalleries($data = [])
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$apiResponse = API365::CallEndpoint("property-gallery", $key, $data);

		if(!is_null($apiResponse))
		{
			$cacheKey = API365::APIBaseURL() . "/property-gallery/owner_token/" . $key . "?" . http_build_query($data) . "keyed";
			if(API365::Cache365Calls())
			{
				$cachedKeyedPropertiesGalleries = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
				if($cachedKeyedPropertiesGalleries !== false)
				{
					return $cachedKeyedPropertiesGalleries;
				}
			}
			
			//If we have an array of properties galleries then create a new array keyed by each property ID and return this.
			$keyedPropertiesGalleries = [];
			foreach(get_object_vars($apiResponse) as $key => $propertyGallery)
			{
				$propertyId = str_replace("property_gallery_", "", $key);
				if(!array_key_exists($propertyId, $keyedPropertiesGalleries))
				{
					$keyedPropertiesGalleries[$propertyId] = $propertyGallery;
				}
			}

			if(API365::Cache365Calls())
			{
				wp_cache_set($cacheKey, $keyedPropertiesGalleries, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
			}

			return $keyedPropertiesGalleries;
		}

		return null;
	}

	public static function GetProperty($propertySlug, $data = [])
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$propertyId = null;
		preg_match('/^(\d+)-/', $propertySlug, $propertySlugParts);
		if(array_key_exists(1, $propertySlugParts))
		{
			$propertyId = $propertySlugParts[1];
		}

		if(is_null($propertyId))
		{
			return null;
		}

		if(!array_key_exists("property_id", $data))
		{
			$data["property_id"] = $propertyId;
		}

		$allProperties = API365::GetAllPropertyInfo($data);

		$property = null;
		if(
			!is_null($allProperties) && 
			is_array($allProperties) &&
			array_key_exists("properties", $allProperties) &&
			!is_null($allProperties["properties"]) &&
			is_array($allProperties["properties"]) &&
			(count($allProperties["properties"]) == 1) &&
			array_key_exists($propertyId, $allProperties["properties"][0])
		)
		{
			$property = $allProperties["properties"][0][$propertyId];
		}
		elseif(
			!is_null($allProperties) && 
			is_object($allProperties) &&
			Helpers365::CheckObjectPropertiesExist($allProperties, ["id"]) &&
			($allProperties->id == $propertyId)
		)
		{
			$property = $allProperties;
		}

		if(!is_null($property) && !is_null($propertySlug))
		{
			$propertySlugActual = Helpers365::MakePropertySlug($property);
			$propertySlug = preg_replace('/[-]+/', '-', strtolower($propertySlug));

			//We need to check that the property slug for the returned property matches the slug we queried for.
			//As we are having to split the queried slug up to just use the property ID part the rest of the slug can
			//actually be nonsense. So we need to check the whole slug is what we want after we have retrieved a property
			//from the 365 API or there could be multiple pages showing the same content.
			if ($propertySlug == $propertySlugActual)
			{
				return $property;
			}
		}

		return null;
	}

	public static function GetPropertySlugFromId($propertyId, $data = [])
	{
		if(is_null($propertyId))
		{
			return null;
		}

		if(!array_key_exists("property_id", $data))
		{
			$data["property_id"] = $propertyId;
		}

		$allProperties = API365::GetAllPropertyInfo($data);

		$property = null;
		if(
			!is_null($allProperties) && 
			is_array($allProperties) &&
			array_key_exists("properties", $allProperties) &&
			!is_null($allProperties["properties"]) &&
			is_array($allProperties["properties"]) &&
			(count($allProperties["properties"]) == 1) &&
			array_key_exists($propertyId, $allProperties["properties"][0])
		)
		{
			$property = $allProperties["properties"][0][$propertyId];
		}
		elseif(
			!is_null($allProperties) && 
			is_object($allProperties) &&
			Helpers365::CheckObjectPropertiesExist($allProperties, ["id"]) &&
			($allProperties->id == $propertyId)
		)
		{
			$property = $allProperties;
		}

		if(!is_null($property))
		{
			$propertySlugActual = Helpers365::MakePropertySlug($property);
			
			if(!is_null($propertySlugActual))
			{
				return $propertySlugActual;
			}
		}

		return null;
	}

	public static function GetPropertyGallery($propertyId, $data = [])
	{
		if(!array_key_exists("property_id", $data))
		{
			$data["property_id"] = $propertyId;
		}

		$allPropertiesGalleries = API365::GetAllPropertiesGalleries($data);

		if(!is_null($allPropertiesGalleries) && array_key_exists($propertyId, $allPropertiesGalleries))
		{
			return $allPropertiesGalleries[$propertyId];
		}

		return null;
	}

	public static function GetPropertyRelated($data = [])
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);
		$apiResponse = API365::CallEndpoint("get-property-related", $key, $data, "POST", false, false, ["languages" => 1]);

		if(!is_null($apiResponse))
		{
			if(property_exists($apiResponse, "property_related"))
			{
				$properties = $apiResponse->property_related->properties;

				//If we have only one property then return it.
				if(!is_array($properties))
				{
					return $properties;
				}

				$cacheKey = API365::APIBaseURL() . "/get-property-related/owner_token/" . $key . "?" . http_build_query($data) . "keyed";
				if(API365::Cache365Calls())
				{
					$cachedKeyedProperties = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
					if($cachedKeyedProperties !== false)
					{
						return $cachedKeyedProperties;
					}
				}

				//If we have an array of properties then create a new array keyed by each property ID and return this.
				$keyedProperties = [];
				foreach($properties as $property)
				{
					if(!array_key_exists($property->id, $keyedProperties))
					{
						$keyedProperties[$property->id] = $property;
						$keyedProperties[$property->id]->slug = Helpers365::MakePropertySlug($property);
					}
				}

				$propertiesObject = [
					"cacheKey" => $cacheKey,
					"count" => count($keyedProperties),
					"perPage" => count($keyedProperties),
					"totalPages" => 1,
					"properties" => [
						0 => $keyedProperties
					]
				];

				if(API365::Cache365Calls())
				{
					wp_cache_set($cacheKey, $propertiesObject, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
				}

				return $propertiesObject;
			}
		}

		return null;
	}

	/*
	# Request Data
	page			Integer 	Page value.
	limit		 	Integer 	Limit value.
	isfeatured		Integer 	1 = Feature properties only.
	category_id		Integer 	Category's ID (optional).
	property_id		Integer 	Property's ID (optional).
	keyterm		 	String		Key word (optional).
	checkin		 	String		Filter check-in date (YYYY-MM-DD) (optional).
	checkout		String		Filter check-out date (YYYY-MM-DD) (optional).
	flexible		Integer 	This parameter will add +/- check-in and check-out parameters (optional).
	numberadults	Integer 	Max of guest (optional).
	children		Integer 	0 = Do not allow children / 1 = Allow children (optional).
	airconditioner	Integer 	0 = Without air conditioner / 1 = With air conditioner (optional).
	parking		 	Integer 	0 = Without parking / 1 = With parking (optional).
	ocean		 	Integer 	0 = Without ocean view / 1 = With ocean view (optional).
	allowpet		Integer 	0 = Do not allow pet / 1 = Allow pet (optional).
	allowsmoking	Integer 	0 = Do not allow smoking / 1 = Allow smoking (optional).

	# Response Data
	property_count	Integer 	Total properties that match the search conditions.
	page_count	 	Integer 	Total page.
	page		 	Integer 	Current page.
	offset		 	Integer 	The starting position of the first property to return.
	limit		 	Integer 	The number of property will be returned.
	image_path	 	String 		Location of the image.
	properties	 	Array 		List of properties that match the search conditions.
	*/
	public static function SearchProperties($data = [], $get = null)
	{
		//Make sure we get the discounts
		if(!array_key_exists("include", $data))
		{
			$data["include"] = [
				"discounttexts",
				"discountdescription"
			];
		}

		//Set a high limit so we get all the properties.
		if(!array_key_exists("limit", $data))
		{
			$data["limit"] = 10;
		}

		//If we only want discounted properties then set that value.
		if(array_key_exists("discountsonly", $data) && ($data["discountsonly"] === true))
		{
			$data["discountsonly"] = 1;
		}

		//If we have the get value then override anything in $data.
		if(isset($get) && !is_null($get) && is_array($get) && (count($get) > 0))
		{
			if(array_key_exists("q", $get) && !is_null($get["q"]) && (trim($get["q"]) !== ""))
			{
				$data["keyterm"] = $get["q"];
			}
			
			if(array_key_exists("categoryid", $get) && !is_null($get["categoryid"]) && (trim($get["categoryid"]) !== ""))
			{
				$data["category_id"] = $get["categoryid"];
			}

			if(array_key_exists("propertyid", $get) && !is_null($get["propertyid"]) && (trim($get["propertyid"]) !== ""))
			{
				$data["property_id"] = $get["propertyid"];
			}

			//If we have multiple ids.
			if(array_key_exists("propertyids", $get) && !is_null($get["propertyids"]) && (trim($get["propertyids"]) !== ""))
			{
				$data["property_id"] = explode(",", $get["propertyids"]);
			}

			$hasCheckin = false;
			if(array_key_exists("checkin", $get) && !is_null($get["checkin"]) && (trim($get["checkin"]) !== ""))
			{
				$data["checkin"] = $get["checkin"];
				$hasCheckin = true;
			}

			$hasCheckout = false;
			if(array_key_exists("checkout", $get) && !is_null($get["checkout"]) && (trim($get["checkout"]) !== ""))
			{
				$data["checkout"] = $get["checkout"];
				$hasCheckout = true;
			}

			if($hasCheckin && $hasCheckout)
			{
				//If this isn't overridden then always get the full booking prices to show a grand total.
				if(!array_key_exists("includebookingprice", $data))
				{
					$data["includebookingprice"] = true;
				}

				if(!array_key_exists("include", $data))
				{
					$data["include"] = [
						"grandTotalBeforeDiscountedValue"
					];
				}
				else
				{
					$data["include"][] = "grandTotalBeforeDiscountedValue";
				}
			}

			if(array_key_exists("guests", $get) && !is_null($get["guests"]) && (trim($get["guests"]) !== ""))
			{
				$data["numberadults"] = $get["guests"];
			}
			else
			{
				$totalGuests = 0;
				if(array_key_exists("adultguests", $get) && !is_null($get["adultguests"]) && (trim($get["adultguests"]) !== ""))
				{
					$totalGuests += (int)$get["adultguests"];
				}

				if(array_key_exists("childguests", $get) && !is_null($get["childguests"]) && (trim($get["childguests"]) !== ""))
				{
					$totalGuests += (int)$get["childguests"];
				}

				if($totalGuests != 0)
				{
					$data["numberadults"] = $totalGuests;
				}
			}

			if(array_key_exists("bedrooms", $get) && !is_null($get["bedrooms"]) && (trim($get["bedrooms"]) !== ""))
			{
				$data["bedrooms"] = $get["bedrooms"];
			}

			if(array_key_exists("city", $get) && !is_null($get["city"]) && (trim($get["city"]) !== ""))
			{
				$data["city"] = $get["city"];
			}

			if(array_key_exists("price", $get) && !is_null($get["price"]) && (trim($get["price"]) !== "") && ($get["price"] != 0))
			{
				$price = $get["price"];

				//If the price selected has a "+" then it is the last and largest value so we should use this as the min price.
				//Otherwise use the value as the max price.
				if((class_exists('Helpers365') ? Helpers365::StrContains($price, "+") : false))
				{
					$data["minprice"] = str_replace("+", "", $price);
				}
				else
				{
					$data["maxprice"] = $price;
				}
			}
			else
			{
				if(array_key_exists("minprice", $get) && !is_null($get["minprice"]) && (trim($get["minprice"]) !== "") && ($get["minprice"] != 0))
				{
					$data["minprice"] = $get["minprice"];
				}
				
				if(array_key_exists("maxprice", $get) && !is_null($get["maxprice"]) && (trim($get["maxprice"]) !== "") && ($get["maxprice"] != 0))
				{
					$data["maxprice"] = $get["maxprice"];
				}
			}

			if(array_key_exists("tag_id", $get) && !is_null($get["tag_id"]) && (trim($get["tag_id"]) !== ""))
			{
				$data["tag_id"] = $get["tag_id"];
			}

			if(array_key_exists("sort", $get) && !is_null($get["sort"]) && (trim($get["sort"]) !== "") && (trim($get["sort"]) !== "default"))
			{
				$sortBy = null;
				$sortDirection = "asc";
				switch($get["sort"])
				{
					case "price-high":
						$sortBy = 4;
						$sortDirection = "desc";
						break;
					case "price-low":
						$sortBy = 4;
						break;
					case "bedrooms":
						$sortBy = 3;
						break;
					case "name":
						$sortBy = 2;
						break;
					default:
						break;
				}

				if(!is_null($sortBy))
				{
					$data["sortby"] = $sortBy;
					$data["sorttype"] = $sortDirection;
				}
			}

			if(array_key_exists("searchoptions", $get) && is_array($get["searchoptions"]) && (count($get["searchoptions"]) > 0))
			{
				foreach($get["searchoptions"] as $searchOptionKey => $searchOption)
				{
					$data[$searchOptionKey] = ($searchOption == "on" ? 1 : 0);
				}
			}

			if(array_key_exists("amenity", $get) && is_array($get["amenity"]) && (count($get["amenity"]) > 0))
			{
				foreach($get["amenity"] as $amenityKey => $amenity)
				{
					if($amenity == "on")
					{
						$data["amenity"][] = $amenityKey;
					}
				}
			}

			if(array_key_exists("ppage", $get) && !is_null($get["ppage"]) && (trim($get["ppage"]) !== ""))
			{
				$data["page"] = $get["ppage"];
			} else {
				$page = API365::usePageSlashToPaging();
			}

			//Get Amenity value and store to request data
			if(array_key_exists("amenityvalue", $get) && !is_null($get["amenityvalue"]) && (trim($get["amenityvalue"]) !== "")) {
                $data["amenity"] = $get["amenityvalue"];
			}
		}

		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$apiResponse = API365::CallEndpoint("search-rental", $key, $data, "POST", false, true);
		if(!is_null($apiResponse))
		{
			if(property_exists($apiResponse, "properties"))
			{
				$properties = $apiResponse->properties;
				$includeLanguage = "lang=" . Helpers365::Get365LanguageCode();
				$cacheKey = API365::APIBaseURL() . "/search-rental/owner_token/" . $key . "?" . $includeLanguage . "&" . http_build_query($data) . "keyed";
				if(API365::Cache365Calls())
				{
					$cachedKeyedProperties = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
					if($cachedKeyedProperties !== false)
					{
						return $cachedKeyedProperties;
					}
				}

				//If we have an array of properties then create a new array keyed by each property ID and return this.
				$keyedProperties = [];
				foreach($properties as $property)
				{
					if(array_key_exists("discountsonly", $data) && ($data["discountsonly"] == 1))
					{
						$propertySlug = Helpers365::MakePropertySlug($property);
						$propertyData = $property;
						$key = $property->id . wp_generate_password(12, false);

						if(!is_null($propertyData))
						{
							$keyedProperties[$key] = $propertyData;
							$keyedProperties[$key]->slug = $propertySlug;
						}
					}
					elseif(!array_key_exists($property->id, $keyedProperties))
					{
						$propertySlug = Helpers365::MakePropertySlug($property);
						$propertyData = $property;

						if(!is_null($propertyData))
						{
							$keyedProperties[$property->id] = $propertyData;
							$keyedProperties[$property->id]->slug = $propertySlug;
						}
					}
				}

				$totalPages = 1;
				if(property_exists($apiResponse, "page_count"))
				{
					$totalPages = $apiResponse->page_count;
				}

				$limit = 10;
				if(property_exists($apiResponse, "limit"))
				{
					$limit = $apiResponse->limit;
				}

				$totalProperties = count($keyedProperties);
				if(property_exists($apiResponse, "property_count"))
				{
					$totalProperties = $apiResponse->property_count;
				}

				$propertiesObject = [
					"cacheKey" => $cacheKey,
					"count" => count($keyedProperties),
					"perPage" => $limit,
					"totalPages" => $totalPages,
					"totalProperties" => $totalProperties,
					"properties" => $keyedProperties
				];

				if(API365::Cache365Calls())
				{
					wp_cache_set($cacheKey, $propertiesObject, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
				}

				return $propertiesObject;
			}
		}

		return null;
	}

	//Returns the current search parameters for displaying on the page.
	public static function GetSearchParameters($data = null)
	{
		$outputData = [
			"q" => "",
			"categoryid" => "",
			"propertyid" => "",
			"checkin" => "",
			"checkout" => "",
			"guests" => "",
			"adultguests" => "",
			"childguests" => "",
			"minprice" => "",
			"maxprice" => "",
			"price" => "",
			"city" => "",
			"view" => "",
			"propertyids" => ""
		];

		//If we have the get value then override anything in $data.
		if(isset($data) && !is_null($data) && is_array($data) && (count($data) > 0))
		{
			if(array_key_exists("q", $data) && !is_null($data["q"]) && (trim($data["q"]) !== ""))
			{
				$outputData["q"] = $data["q"];
			}
			
			if(array_key_exists("categoryid", $data) && !is_null($data["categoryid"]) && (trim($data["categoryid"]) !== ""))
			{
				$outputData["categoryid"] = $data["categoryid"];
			}

			if(array_key_exists("propertyid", $data) && !is_null($data["propertyid"]) && (trim($data["propertyid"]) !== ""))
			{
				$outputData["propertyid"] = $data["propertyid"];
			}

			if(array_key_exists("checkin", $data) && !is_null($data["checkin"]) && (trim($data["checkin"]) !== ""))
			{
				$outputData["checkin"] = $data["checkin"];
			}

			if(array_key_exists("checkout", $data) && !is_null($data["checkout"]) && (trim($data["checkout"]) !== ""))
			{
				$outputData["checkout"] = $data["checkout"];
			}

			if(array_key_exists("guests", $data) && !is_null($data["guests"]) && (trim($data["guests"]) !== ""))
			{
				$outputData["guests"] = $data["guests"];
			}
			
			if(array_key_exists("adultguests", $data) && !is_null($data["adultguests"]) && (trim($data["adultguests"]) !== ""))
			{
				$outputData["adultguests"] = $data["adultguests"];
			}

			if(array_key_exists("childguests", $data) && !is_null($data["childguests"]) && (trim($data["childguests"]) !== ""))
			{
				$outputData["childguests"] = $data["childguests"];
			}

			if(array_key_exists("bedrooms", $data) && !is_null($data["bedrooms"]) && (trim($data["bedrooms"]) !== ""))
			{
				$outputData["bedrooms"] = $data["bedrooms"];
			}

			if(array_key_exists("city", $data) && !is_null($data["city"]) && (trim($data["city"]) !== ""))
			{
				$outputData["city"] = $data["city"];
			}

			if(array_key_exists("price", $data) && !is_null($data["price"]) && (trim($data["price"]) !== "") && ($data["price"] !== 0))
			{
				$outputData["price"] = $data["price"];
			}

			if(array_key_exists("minprice", $data) && !is_null($data["minprice"]) && (trim($data["minprice"]) !== "") && ($data["minprice"] !== 0))
			{
				$outputData["minprice"] = $data["minprice"];
			}

			if(array_key_exists("maxprice", $data) && !is_null($data["maxprice"]) && (trim($data["maxprice"]) !== "") && ($data["maxprice"] !== 0))
			{
				$outputData["maxprice"] = $data["maxprice"];
			}

			if(array_key_exists("tag_id", $data) && !is_null($data["tag_id"]) && (trim($data["tag_id"]) !== "") && ($data["tag_id"] !== 0))
			{
				$outputData["tag_id"] = $data["tag_id"];
			}

			if(array_key_exists("sort", $data) && !is_null($data["sort"]) && (trim($data["sort"]) !== "") && ($data["sort"] !== 0) && ($data["sort"] != "default"))
			{
				$outputData["sort"] = $data["sort"];
			}

			if(array_key_exists("searchoptions", $data) && is_array($data["searchoptions"]) && (count($data["searchoptions"]) > 0))
			{
				$outputData["searchoptions"] = [];
				foreach($data["searchoptions"] as $searchOptionKey => $searchOption)
				{
					$outputData["searchoptions"][$searchOptionKey] = ($searchOption == "on" ? "on" : "");
				}
			}

			if(array_key_exists("amenity", $data) && is_array($data["amenity"]) && (count($data["amenity"]) > 0))
			{
				$outputData["amenity"] = [];
				foreach($data["amenity"] as $searchAmenityKey => $searchAmenity)
				{
					$outputData["amenity"][$searchAmenityKey] = ($searchAmenity == "on" ? "on" : "");
				}
			}

			if(array_key_exists("view", $data))
			{
				$outputData["view"] = $data["view"];
			}

			if(array_key_exists("propertyids", $data))
			{
				$outputData["propertyids"] = $data["propertyids"];
			}

			if(array_key_exists("ppage", $data)) {
				$outputData["ppage"] = $data["ppage"];
			}

			if(array_key_exists("amenityvalue", $data)) {
				$outputData["amenityvalue"] = $data["amenityvalue"];
			}

		}


		
		return $outputData;
	}

	public static function GetCategories($data = [], $hideCategoriesMarkedAsHidden = true)
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);
		$apiResponse = API365::CallEndpoint("property-category", $key, $data);

		if(!is_null($apiResponse) && is_array($apiResponse) && (count($apiResponse) > 0))
		{
			$cacheKey = API365::APIBaseURL() . "/property-category/owner_token/" . $key . "?" . http_build_query($data) . "keyed" . Helpers365::Get365LanguageCode();
			if(API365::Cache365Calls())
			{
				$cachedKeyedCategories = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
				if($cachedKeyedCategories !== false)
				{
					return $cachedKeyedCategories;
				}
			}
			
			//If we have an array of categories then create a new array keyed by each category ID and return this.
			$keyedCategories = [];
			foreach($apiResponse as $category)
			{
				if(!array_key_exists($category->id, $keyedCategories))
				{
					if($hideCategoriesMarkedAsHidden && Helpers365::CheckObjectPropertiesExist($category, ["show_search_engine"], false) && ($category->show_search_engine === "0"))
					{
						continue;
					}

					$categoryId = $category->id;
					$categoryName = Helpers365::Get365TranslationValue($category, ["name"], ["languages"]);
					$isProperty = false;

					//If the ID starts with "p" there are no categories only properties so group them in an unknown group.
					if(substr(strtolower($categoryId), 0, 1) == "p")
					{
						$categoryId = "-1";
						$categoryName = "Unknown";
						$isProperty = true;
					}
					
					if(!array_key_exists($categoryId, $keyedCategories))
					{
						$keyedCategories[$categoryId]["name"] = $categoryName;
						$keyedCategories[$categoryId]["properties"] = [];
						$keyedCategories[$categoryId]["gallery"] = [];
					}

					if(property_exists($category, "properties") && is_array($category->properties) && (count($category->properties) > 0))
					{
						foreach($category->properties as $property)
						{
							if(!array_key_exists($property->id, $keyedCategories[$categoryId]["properties"]))
							{
								$keyedCategories[$categoryId]["properties"][$property->id]["name"] = Helpers365::Get365TranslationValue($property, ["name"], ["languages"]);
							}
						}
					}

					if(Helpers365::CheckObjectPropertiesExist($category, ["gallery"]) && is_array($category->gallery) && (count($category->gallery) > 0))
					{
						foreach($category->gallery as $image)
						{
							$keyedCategories[$categoryId]["gallery"][] = $image;
						}
					}
					
					if($isProperty)
					{
						if(!array_key_exists($category->id, $keyedCategories[$categoryId]["properties"]))
						{
							$propertyId = substr(strtolower($category->id), 1);
							$keyedCategories[$categoryId]["properties"][$propertyId]["name"] = $category->name;
						}
					}
				}
			}

			if(API365::Cache365Calls())
			{
				wp_cache_set($cacheKey, $keyedCategories, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
			}

			return $keyedCategories;
		}

		return null;
	}

	public static function GetTags()
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$cacheKey = "search-tags" . $key . "keyed" . Helpers365::Get365LanguageCode();
		if(API365::Cache365Calls())
		{
			$cachedSearchTags = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
			if($cachedSearchTags !== false)
			{
				return $cachedSearchTags;
			}
		}

		//Get the settings from the API.
		$settings = API365::GetDefaultSettings();
		if(is_null($settings) || is_wp_error($settings) || !property_exists($settings, "tags_list"))
		{
			return [];
		}

		$keyedData = [];

		foreach($settings->tags_list as $tag)
		{
			$keyedData[$tag->id] = Helpers365::Get365TranslationValue($tag, ["name"], ["languages"]);
		}

		if(API365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}

		return $keyedData;
	}

	public static function GetMinMaxPrice($data = [])
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);
		$apiResponse = API365::CallEndpoint("get-min-max-price-property", $key, $data, "GET");

		if(!is_null($apiResponse) && !is_wp_error($apiResponse))
		{
			$cacheKey = API365::APIBaseURL() . "/get-min-max-price-property/owner_token/" . $key . "?" . http_build_query($data) . "keyed";
			if(API365::Cache365Calls())
			{
				$cachedMixMaxPrice = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
				if($cachedMixMaxPrice !== false)
				{
					return $cachedMixMaxPrice;
				}
			}

			if(API365::Cache365Calls())
			{
				wp_cache_set($cacheKey, $apiResponse, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
			}

			return $apiResponse;
		}

		return null;
	}

	public static function PaginateProperties($propertiesObject, $perPage = 10)
	{
		if(is_null($perPage) || !is_numeric($perPage) || ($perPage <= 0) || ($propertiesObject["count"] == 0))
		{
			return $propertiesObject;
		}

		$cacheKey = $propertiesObject["cacheKey"] . $perPage;
		if(API365::Cache365Calls())
		{
			$cachedPaginatedProperties = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
			if($cachedPaginatedProperties !== false)
			{
				return $cachedPaginatedProperties;
			}
		}

		$propertiesToPaginate = [];
		if($propertiesObject["totalPages"] == 1)
		{
			$propertiesToPaginate = $propertiesObject["properties"][0];
		}
		else
		{
			foreach($propertiesObject["properties"] as $propertiesPage)
			{
				$propertiesToPaginate = array_merge($propertiesToPaginate, $propertiesPage);
			}
		}

		$pagedProperties = array_chunk($propertiesToPaginate, $perPage, true);

		$propertiesObject["cacheKey"] = $cacheKey;
		$propertiesObject["perPage"] = $perPage;
		$propertiesObject["totalPages"] = count($pagedProperties);
		$propertiesObject["properties"] = $pagedProperties;

		if(API365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $propertiesObject, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}

		return $propertiesObject;
	}

	public static function GetPriceOptions()
	{
		//Get the settings from the API.
		$settings = API365::GetDefaultSettings();
		if(is_null($settings) || is_wp_error($settings) || !property_exists($settings, "rate_type") || !property_exists($settings, "currency_symbol"))
		{
			return [];
		}

		//Get the min/max prices from the API.
		$minMaxPrice = API365::GetMinMaxPrice();
		if(is_null($minMaxPrice) || is_wp_error($minMaxPrice) || !property_exists($minMaxPrice, "minprice") || !property_exists($minMaxPrice, "maxprice"))
		{
			return [];
		}

		$currencySymbol = $settings->currency_symbol;
		$rateType = $settings->rate_type;
		$minPrice = $minMaxPrice->minprice;
		$maxPrice = $minMaxPrice->maxprice;

		if(is_null($rateType) || (trim($rateType) == "") || !in_array($rateType, ["daily", "weekly", "monthly"]))
		{
			return [];
		}

		//Generate a list of incrementing prices.
		$generatePrices = function($start, $end, $increments) use ($currencySymbol) {
			$prices = [
				0 => ""
			];
			
			//fix issue out of memory
			$index = 0;
			for($counter = $start; $counter <= $end; $counter += $increments)
			{
				$prices[$counter] = $currencySymbol . $counter;
				$index++;
				if ($index > 100) break;
			}

			return $prices;
		};

		$priceOptions = [];
		switch($rateType)
		{
			case "daily":
			{
				$priceOptions = $generatePrices($minPrice, $maxPrice, 50);
				break;
			}
			case "weekly":
			{
				$priceOptions = $generatePrices($minPrice, $maxPrice, 100);
				break;
			}
			case "monthly":
			{
				$priceOptions = $generatePrices($minPrice, $maxPrice, 100);
				break;
			}
			default:
				break;
		}

		//We need to set the last item to have a "+" so we can use the min price rather than the max price when searching.
		if(count($priceOptions) > 0)
		{
			$lastIndex = null;

			//The "array_key_last" function is only in PHP 7.3+
			if(!function_exists("array_key_last"))
			{
				$priceOptionsKeys = array_keys($priceOptions);
				$lastIndex = $priceOptionsKeys[(count($priceOptionsKeys) - 1)];
			}
			else
			{
				$lastIndex = array_key_last($priceOptions);
			}
			
			//Remove and re-add the last item to change the key.
			$priceOptions[$lastIndex . "+"] = array_pop($priceOptions) . "+";
		}

		return [
			"rate_type" => $rateType,
			"price_options" => $priceOptions,
			"currency_symbol" => $currencySymbol
		];
	}

	public static function GetRateTable($propertyId)
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$data = [
			"key" => get_option("villas-365_api_key_365_api"),
			"pass" => get_option("villas-365_api_password_365_api"),
			"propertyId" => $propertyId,
			"action" => "getratetable"
		];

		$apiResponse = API365::CallEndpoint("external-booking", $key, $data, "POST", true);

		if(!is_null($apiResponse) && !is_wp_error($apiResponse) && ($apiResponse->message == "Success"))
		{
			$cacheKey = API365::APIBaseURLPrivate() . "/external-booking?" . http_build_query($data) . "keyed";
			if(API365::Cache365Calls())
			{
				$cachedRateTable = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
				if($cachedRateTable !== false)
				{
					return $cachedRateTable;
				}
			}

			$keyedData = $apiResponse->data;

			//Get the settings from the API.
			$settings = API365::GetDefaultSettings();
			if(is_null($settings) || is_wp_error($settings) || !property_exists($settings, "rate_type") || !property_exists($settings, "currency_symbol"))
			{
				return [];
			}

			$keyedData->currencySymbol = $settings->currency_symbol;

			if(API365::Cache365Calls())
			{
				wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
			}

			return $keyedData;
		}

		return null;
	}

	public static function GetNonAvailableDates($propertyId)
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$data = [
			"key" => get_option("villas-365_api_key_365_api"),
			"pass" => get_option("villas-365_api_password_365_api"),
			"propertyId" => $propertyId,
			"action" => "getnonavailabledate"
		];

		$apiResponse = API365::CallEndpoint("external-booking", $key, $data, "POST", true);

		if(!is_null($apiResponse) && !is_wp_error($apiResponse) && ($apiResponse->message == "Success"))
		{
			$cacheKey = API365::APIBaseURLPrivate() . "/external-booking?" . http_build_query($data) . "keyed";
			if(API365::Cache365Calls())
			{
				$cachedNonAvailableDates = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
				if($cachedNonAvailableDates !== false)
				{
					return $cachedNonAvailableDates;
				}
			}

			$keyedData = $apiResponse->data;

			if(API365::Cache365Calls())
			{
				wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
			}

			return $keyedData;
		}

		return null;
	}

	public static function GetDiscounts($propertyId)
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$data = [
			"key" => get_option("villas-365_api_key_365_api"),
			"pass" => get_option("villas-365_api_password_365_api"),
			"propertyId" => $propertyId,
			"action" => "getdiscounts"
		];

		$apiResponse = API365::CallEndpoint("external-booking", $key, $data, "POST", true);

		if(!is_null($apiResponse) && !is_wp_error($apiResponse) && ($apiResponse->message == "Success"))
		{
			$cacheKey = API365::APIBaseURLPrivate() . "/external-booking?" . http_build_query($data) . "keyed";
			if(API365::Cache365Calls())
			{
				$cachedDiscounts = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
				if($cachedDiscounts !== false)
				{
					return $cachedDiscounts;
				}
			}

			$keyedData = $apiResponse->data;

			if(API365::Cache365Calls())
			{
				wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
			}

			return $keyedData;
		}

		return null;
	}

	public static function GetSearchOptions(array $enabledOptions = null)
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$cacheKey = "search-options" . $key . "keyed" . Helpers365::Get365LanguageCode();
		if(API365::Cache365Calls())
		{
			$cachedSearchOptions = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
			if($cachedSearchOptions !== false)
			{
				return $cachedSearchOptions;
			}
		}

		//Get the settings from the API.
		$settings = API365::GetDefaultSettings();
		if(is_null($settings) || is_wp_error($settings) || !property_exists($settings, "searchconfig"))
		{
			return [];
		}

		$keyedData = [];

		foreach($settings->searchconfig as $searchConfigKey => $searchConfig)
		{
			if(!is_null($enabledOptions) && is_array($enabledOptions) && (count($enabledOptions) > 0))
			{
				if(array_key_exists($searchConfigKey, $enabledOptions) && ($enabledOptions[$searchConfigKey] == 1))
				{
					$keyedData[$searchConfigKey] = Helpers365::Get365TranslationValue($searchConfig, ["alias"], ["languages"]);
				}
			}
			elseif(Helpers365::CheckObjectPropertiesExist($searchConfig, ["gs"]) && (Helpers365::GetValueFromObjectProperties($searchConfig, ["gs"]) == 1))
			{
				$keyedData[$searchConfigKey] = Helpers365::Get365TranslationValue($searchConfig, ["alias"], ["languages"]);
			}
		}

		if(API365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}

		return $keyedData;
	}

	public static function GetSearchAmenityList(array $enabledOptions = null)
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$cacheKey = "amenity-list" . $key . "keyed" . Helpers365::Get365LanguageCode();
		if(API365::Cache365Calls())
		{
			$cachedSearchOptions = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
			if($cachedSearchOptions !== false)
			{
				return $cachedSearchOptions;
			}
		}

		//Get the settings from the API.
		$settings = API365::GetDefaultSettings();
		if(is_null($settings) || is_wp_error($settings) || !property_exists($settings, "amenity_list"))
		{
			return [];
		}

		$keyedData = [];

		foreach($settings->amenity_list as $amenityListKey => $amenity)
		{
			if(!is_null($enabledOptions) && is_array($enabledOptions) && (count($enabledOptions) > 0))
			{
				if(array_key_exists($amenityListKey, $enabledOptions) && ($enabledOptions[$amenityListKey] == 1))
				{
					$keyedData[$amenityListKey] = Helpers365::Get365TranslationValue($amenity, ["alias"], ["languages"]);
				}
			}
			else
			{
				$keyedData[$amenityListKey] = Helpers365::Get365TranslationValue($amenity, ["name"], ["languages"]);
			}
		}

		if(API365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}

		return $keyedData;
	}

	public static function GetMaxGuests()
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$cacheKey = "max-guests" . $key . "keyed";
		if(API365::Cache365Calls())
		{
			$cachedMaxGuests = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
			if($cachedMaxGuests !== false)
			{
				//This comes out of the cache as a string so cast it to an int.
				return (int)$cachedMaxGuests;
			}
		}

		//Get the settings from the API.
		$settings = API365::GetDefaultSettings();
		if(is_null($settings) || is_wp_error($settings) || !property_exists($settings, "property_max_guest"))
		{
			return 20;
		}

		$maxGuests = $settings->property_max_guest;
		if(is_null($maxGuests) || is_wp_error($maxGuests) || !is_numeric($maxGuests) || ($maxGuests == 0))
		{
			return 20;
		}

		if(API365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $maxGuests, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}

		return $maxGuests;
	}

	public static function GetSearchTerms()
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$cacheKey = "search-terms" . $key . "keyed" . Helpers365::Get365LanguageCode();
		if(API365::Cache365Calls())
		{
			$cachedSearchOptions = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
			if($cachedSearchOptions !== false)
			{
				return $cachedSearchOptions;
			}
		}

		//Get the settings from the API.
		$settings = API365::GetDefaultSettings();
		if(is_null($settings) || is_wp_error($settings) || !property_exists($settings, "searchconfig"))
		{
			return [];
		}

		$keyedData = [];

		foreach($settings->searchconfig as $searchConfigKey => $searchConfig)
		{
			if(!property_exists($searchConfig, "gs"))
			{
				$keyedData[$searchConfigKey] = Helpers365::Get365TranslationValue($searchConfig, ["alias"], ["languages"]);
			}
		}

		if(API365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}

		return $keyedData;
	}

	public static function GetSearchPluginOptions()
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		//Get the settings from the API.
		$settings = API365::GetDefaultSettings();
		if(is_null($settings) || is_wp_error($settings) || !property_exists($settings, "searchplugin"))
		{
			return [];
		}

		$keyedData = [];

		foreach($settings->searchplugin as $searchpluginValueKey => $searchpluginValue)
		{
			$keyedData[$searchpluginValueKey] = $searchpluginValue;
		}

		$cacheKey = "search-plugin-options" . $key . "keyed";
		if(API365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}

		return $keyedData;
	}

	public static function GetSearchSettings($location = "homepage")
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$cacheKey = "search-settings-" . $location . $key . "keyed" . Helpers365::Get365LanguageCode();
		if(API365::Cache365Calls())
		{
			$cachedSearchSettings = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
			if($cachedSearchSettings !== false)
			{
				return $cachedSearchSettings;
			}
		}

		//Get the settings from the API.
		$settings = API365::GetDefaultSettings();
		if(is_null($settings) || is_wp_error($settings) || (!property_exists($settings, "homepage_settings") && !property_exists($settings, "searchpage_settings")))
		{
			return null;
		}

		$locationField = null;
		if(!is_null($location) && in_array($location, ["homepage", "searchpage"]))
		{
			switch($location)
			{
				case "homepage":
					$locationField = "homepage_settings";
					break;
				case "searchpage":
					$locationField = "searchpage_settings";
					break;
				default:
					break;
			}
		}

		if(is_null($locationField))
		{
			return null;
		}

		if(!Helpers365::CheckObjectPropertiesExist($settings->{$locationField}, ["searchplugin", "searchconfig"], false))
		{
			return null;
		}

		$keyedData = [];
		foreach($settings->{$locationField}->searchplugin as $searchSettingKey => $searchSetting)
		{
			if($searchSetting == 1)
			{
				$keyedData["fields"][$searchSettingKey] = true;
			}
			else
			{
				$keyedData["fields"][$searchSettingKey] = false;
			}
		}

		$keyedData["searchconfig"] = API365::GetSearchOptions((array)$settings->{$locationField}->searchconfig);

		if(Helpers365::CheckObjectPropertiesExist($settings, ["amenity_list"]))
		{
			$keyedData["amenity_list"] = API365::GetSearchAmenityList();
		}

		if(API365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}

		return $keyedData;
	}

	public static function GetBedroomsRange()
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$cacheKey = "bedrooms-range" . $key . "keyed" . Helpers365::Get365LanguageCode();
		if(API365::Cache365Calls())
		{
			$cachedBedroomsRange = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
			if($cachedBedroomsRange !== false)
			{
				return $cachedBedroomsRange;
			}
		}

		//Get the settings from the API.
		$settings = API365::GetDefaultSettings();
		if(is_null($settings) || is_wp_error($settings) || !property_exists($settings, "bedroom"))
		{
			return [
				"min" => 0,
				"max" => 20
			];
		}

		$keyedData = [];

		foreach($settings->bedroom as $bedroomKey => $bedroom)
		{
			$keyedData[$bedroomKey] = $bedroom;
		}

		if(!array_key_exists("min", $keyedData))
		{
			$keyedData["min"] = 0;
		}

		if(!array_key_exists("max", $keyedData))
		{
			$keyedData["max"] = $keyedData["min"] + 20;
		}

		if(API365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}

		return $keyedData;
	}

	public static function GetBathroomsRange()
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$cacheKey = "bathrooms-range" . $key . "keyed" . Helpers365::Get365LanguageCode();
		if(API365::Cache365Calls())
		{
			$cachedBathroomsRange = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
			if($cachedBathroomsRange !== false)
			{
				return $cachedBathroomsRange;
			}
		}

		//Get the settings from the API.
		$settings = API365::GetDefaultSettings();
		if(is_null($settings) || is_wp_error($settings) || !property_exists($settings, "bathroom"))
		{
			return [
				"min" => 0,
				"max" => 20
			];
		}

		$keyedData = [];

		foreach($settings->bathroom as $bathroomKey => $bathroom)
		{
			$keyedData[$bathroomKey] = $bathroom;
		}

		if(!array_key_exists("min", $keyedData))
		{
			$keyedData["min"] = 0;
		}

		if(!array_key_exists("max", $keyedData))
		{
			$keyedData["max"] = $keyedData["min"] + 20;
		}

		if(API365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}

		return $keyedData;
	}

	public static function GetAmenities($data)
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);
		$apiResponse = API365::CallEndpoint("property-amenity", $key, $data, "POST");

		if(!is_null($apiResponse) && !is_wp_error($apiResponse))
		{
			$cacheKey = API365::APIBaseURL() . "/property-amenity/owner_token/" . $key . "?" . http_build_query($data) . "keyed";
			if(API365::Cache365Calls())
			{
				$cachedAmenities = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
				if($cachedAmenities !== false)
				{
					return $cachedAmenities;
				}
			}

			if(API365::Cache365Calls())
			{
				wp_cache_set($cacheKey, $apiResponse, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
			}

			return $apiResponse;
		}

		return null;
	}

	public static function GetCities()
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$cacheKey = "cities" . $key . "keyed" . Helpers365::Get365LanguageCode();
		if(API365::Cache365Calls())
		{
			$cachedCities = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
			if($cachedCities !== false)
			{
				return $cachedCities;
			}
		}

		//Get the settings from the API.
		$settings = API365::GetDefaultSettings();
		if(is_null($settings) || is_wp_error($settings) || !property_exists($settings, "city_list"))
		{
			return [];
		}

		$keyedData = [];

		foreach($settings->city_list as $city)
		{
			$keyedData[$city] = $city;
		}

		if(API365::Cache365Calls())
		{
			wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
		}

		return $keyedData;
	}

	public static function GetTemporaryBooking($propertyId, $checkIn, $checkOut, $adults, $children)
	{
		$key = Helpers365::GetOption("_owner_key_365_api", "villas-365", false);

		$data = [
			"key" => get_option("villas-365_api_key_365_api"),
			"pass" => get_option("villas-365_api_password_365_api"),
			"propertyId" => $propertyId,
			"action" => "getinfo",
			"checkin" => $checkIn,
			"checkout" => $checkOut,
			"numberofadults" => $adults,
			"include" => [
				"discounttexts",
				"grandTotalBeforeDiscounted"
			]
		];

		if(!is_null($children) && ($children !== ""))
		{
			$data["numberofchildren"] = $children;
		}

		$apiResponse = API365::CallEndpoint("external-booking", $key, $data, "POST", true);

		if(!is_null($apiResponse) && !is_wp_error($apiResponse) && ($apiResponse->message == "Success"))
		{
			$cacheKey = API365::APIBaseURLPrivate() . "/external-booking?" . http_build_query($data) . "keyed";
			if(API365::Cache365Calls())
			{
				$cachedBookingInformation = wp_cache_get($cacheKey, API365::API_CACHE_GROUP);
				if($cachedBookingInformation !== false)
				{
					return $cachedBookingInformation;
				}
			}

			$keyedData = $apiResponse->data;

			//Set the perNight value.
			$keyedData->perNight = number_format(abs(($keyedData->totalRent / $keyedData->qtyofnights)), 2, ".", ",");

			//Get the settings from the API.
			$settings = API365::GetDefaultSettings();
			if(is_null($settings) || is_wp_error($settings) || !property_exists($settings, "rate_type") || !property_exists($settings, "currency_symbol"))
			{
				return [];
			}

			$keyedData->currencySymbol = $settings->currency_symbol;

			if(API365::Cache365Calls())
			{
				wp_cache_set($cacheKey, $keyedData, API365::API_CACHE_GROUP, (class_exists('Helpers365') ? Helpers365::Cache365CallsDuration() : 3600));
			}

			return $keyedData;
		}
		elseif(!is_null($apiResponse->message) && ($apiResponse->message !== ""))
		{
			return [
				"status" => "error",
				"message" => $apiResponse->message
			];
		}

		return null;
	}

	public static function usePageSlashToPaging() {
		$page = 1;
		$pattern="/page\/([0-9]{1,})/";
		if ( preg_match($pattern , $_SERVER['REQUEST_URI'], $matches) ) {
			if ( isset($matches[1]) ) {
				$page = $matches[1];
			}
		}
		return $page;
	}
}

}