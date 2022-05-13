<?php

function bixxs_events_orderPanelAccessStatusFunc($userRole)
{
	$status = 0;
	if (!empty($userRole)) {
		$user = wp_get_current_user();
		if (isset($user->roles)) {
			foreach ($userRole as $role) {
				if (in_array($role, $user->roles)) {
					$status = 1;
					break;
				}
			}
		}
	}

	return $status;
}

function bixxs_events_renderPostcodeCityTableHtmlFunc($records, $type = "postcode")
{
	$tableHtml = '';
	$tableHtml .= '<table class="wp-list-table widefat fixed striped tags" cellspacing=0 cellpadding=0 border=0>';
	$tableHtml .= '<thead>';
	$tableHtml .= '<tr>';
	$tableHtml .= '<th>' . __("ID", BIXXS_EVENTS_TEXTDOMAIN) . '</th>';
	$tableHtml .= '<th>' . __(ucfirst($type), BIXXS_EVENTS_TEXTDOMAIN) . '</th>';
	$tableHtml .= '<th>' . __("Bearbeiten", BIXXS_EVENTS_TEXTDOMAIN) . '</th>';
	$tableHtml .= '<th>' . __("L&#246;schen", BIXXS_EVENTS_TEXTDOMAIN) . '</th>';
	$tableHtml .= '</tr>';
	$tableHtml .= '</thead>';
	if (!empty($records)) {
		foreach ($records as $record) {
			if ($type == "city") {
				$listingType = $record->cities;
			} elseif ($type == "route") {
				$listingType = $record->routes;
			} else {
				$listingType = $record->postcodes;
			}
			$tableHtml .= '<tr>';
			$tableHtml .= '<td>' . $record->ID . '</td>';
			$tableHtml .= '<td>' . $listingType . '</td>';
			$tableHtml .= '<td>';
			$tableHtml .= '<a href="' . admin_url("admin.php?page=" . $type . "&action=edit-event&id=" . $record->ID) . '"><strong>' . __("Bearbeiten", BIXXS_EVENTS_TEXTDOMAIN) . '</strong></a>';
			$tableHtml .= '</td>';
			$tableHtml .= '<td>';
			$tableHtml .= '<a class="deleteRecords" href="' . admin_url("admin.php?page=" . $type . "&action=delete-event&id=" . $record->ID) . '"><strong>' . __("L&#246;schen", BIXXS_EVENTS_TEXTDOMAIN) . '</strong></a>';
			$tableHtml .= '</td>';
			$tableHtml .= '</tr>';
		}
	} else {
		$tableHtml .= '<tr>';
		$tableHtml .= '<td colspan="4">' . __("Es wurde noch nichts angelegt !<", BIXXS_EVENTS_TEXTDOMAIN) . '/td>';
		$tableHtml .= '</tr>';
	}
	$tableHtml .= '<tfoot>';
	$tableHtml .= '<tr>';
	$tableHtml .= '<th>' . __("ID", BIXXS_EVENTS_TEXTDOMAIN) . '</th>';
	$tableHtml .= '<th>' . __(ucfirst($type), BIXXS_EVENTS_TEXTDOMAIN) . '</th>';
	$tableHtml .= '<th>' . __("Bearbeiten", BIXXS_EVENTS_TEXTDOMAIN) . '</th>';
	$tableHtml .= '<th>' . __("L&#246;schen", BIXXS_EVENTS_TEXTDOMAIN) . '</th>';
	$tableHtml .= '</tr>';
	$tableHtml .= '</tfoot>';
	$tableHtml .= '</table>';
	return $tableHtml;
}

function bixxs_events_renderPostcodeCityFormHtmlFunc($name, $label, $postData, $type = "add", $extraData = [])
{
	$formHtml = '';
	$formHtml .= '<h2>' . ucfirst($type) . ' ' . $label . '</h2>';
	$formHtml .= '<form class="bixxs-events-admin-page-form" action="" method="post">';
	$formHtml .= wp_nonce_field('_wpnonce');
	$formHtml .= '<input type="hidden" value="' . $type . '" name="formType" />';
	$formHtml .= '<div class="form-field form-required term-name-wrap">';
	$formHtml .= '<label for="' . $name . '">' . __($label, BIXXS_EVENTS_TEXTDOMAIN) . '</label>';
	$formHtml .= '<input name="' . $name . '" id="' . $name . '" type="text" value="' . (isset($postData[$name]) ? $postData[$name] : "") . '" size="40" aria-required="true">';
	//$formHtml .='<p>So '.$name.' '.__("erscheint es auf Ihrer Route", BIXXS_EVENTS_TEXTDOMAIN).'.</p>';			

	if ($name == "route") {

		$formHtml .= '<label for="route_delivery_cost">' . __('Lieferkosten', BIXXS_EVENTS_TEXTDOMAIN) . '</label>';

		$formHtml .= '<input name="route_delivery_cost" id="route_delivery_cost" type="text" value="' . (isset($postData['route_delivery_cost']) ? $postData['route_delivery_cost'] : "") . '"><br><br>';

		$workingDays = [
			"Mo",
			"Di",
			"Mi",
			"Do",
			"Fr",
			"Sa",
			"So"
		];

		$formHtml .= '<label for="' . $name . '_working_days">' . __("An welchen Tagen liefern Sie", BIXXS_EVENTS_TEXTDOMAIN) . '</label><hr />';


		$counter = 1;

		foreach ($workingDays as $workingDay) {
			$checked = (isset($postData[$name . '_working_days']) && is_array($postData[$name . '_working_days']) && in_array($workingDay, $postData[$name . '_working_days'])) ? 'checked="checked"' : '';
			$formHtml .= '<div class="extra-option-wrapper">';
			$formHtml .= '<label class="extra-option-wrapper" for="' . $name . '_working_days_' . $counter . '">' . __($workingDay, BIXXS_EVENTS_TEXTDOMAIN) . '</label>';



			$formHtml .= '<input type="checkbox" "' . $workingDay . '" ' . $checked . ' value="' . $workingDay . '" name="' . $name . '_working_days[]" class="working_days" id="' . $name . '_working_days_' . $counter . '" />';
			$formHtml .= '</div>';
			$counter++;
		}

		$formHtml .= '<p>' . '.</p>';
		// OLD $formHtml .='<p>'.__("Die ".$name." Liefertage", BIXXS_EVENTS_TEXTDOMAIN).'.</p>';	
		if (!empty($extraData)) {
			foreach ($extraData as $dataType => $data) {
				$formHtml .= '<label for="' . $name . '_' . $dataType . '">' . ucfirst($dataType) . '</label><hr />';
				$formHtml .= '<ul class="' . $name . '_' . $dataType . ' postcodes_listing" id="' . $name . '_' . $dataType . '">';
				$counter = 1;
				if (!empty($data)) {
					foreach ($data as $value) {
						$checked = (isset($postData[$name . '_' . $dataType]) && is_array($postData[$name . '_' . $dataType]) && in_array($value->ID, $postData[$name . '_' . $dataType])) ? 'checked="checked"' : '';

						$formHtml .= '<li class="' . $name . '_' . $value->$dataType . '_' . $counter . '">';
						$formHtml .= '<input type="checkbox" id="' . $name . '_' . $value->$dataType . '_' . $counter . '" value="' . $value->ID . '" name="' . $name . '_' . $dataType . '[]" ' . $checked . '/>';
						$formHtml .= '<label for="' . $name . '_' . $value->$dataType . '_' . $counter . '"><strong>' . $value->$dataType . '</strong></label>';
						$formHtml .= '</li>';
						$counter++;
					}
				}
				$formHtml .= '</ul>';
				// $formHtml .='<p>'.__("The assign ".$dataType." to ".$name, BIXXS_EVENTS_TEXTDOMAIN).'.</p><hr/>';			
			}
		}
	}
	$formHtml .= '</div>';
	$formHtml .= '<p class="submit">';
	$formHtml .= '<input type="submit" name="submit" id="submit" class="button button-primary" value="' . ($type == 'add' ? __("Add", BIXXS_EVENTS_TEXTDOMAIN) : __("Update", BIXXS_EVENTS_TEXTDOMAIN)) . ' ' . __("New " . $label, BIXXS_EVENTS_TEXTDOMAIN) . '">';
	$formHtml .= '</p>';
	$formHtml .= '</form>';

	return $formHtml;
}

function bixxs_events_showPaginationsFunc($totalCount, $limitTo, $pageURL = [], $isAdmin = 0, $isNextPrevious = 0, $isQuery = 0)
{
	$paginationHtml = '';

	if ($totalCount > $limitTo && $limitTo > 0) {
		$pageURL = ($pageURL) ? $pageURL : home_url();
		$totalPages = $totalCount / $limitTo;
		$totalPages = ceil($totalPages);

		$adminClass = ($isAdmin) ? "pagination" : "";
		$activeClass = ($isAdmin) ? "active" : "";
		$nextPreviousClass = ($isAdmin) ? "" : "next-previous-class";
		$adjacents = 2;
		$querySap = (isset($pageURL[3]) && $pageURL[3] == 1) ? '&' : '?';

		$secondLast = $totalPages - 1;

		if ($totalPages > 0) {
			$checkPagination = explode("/", bixxs_events_currentPageURL());
			$pageNumber = end($checkPagination);
			$checkPagination = prev($checkPagination);
			$nextPage = (isset($_GET[$pageURL[1]])) ? $_GET[$pageURL[1]] + 1 : 2;
			$nextPage = ($checkPagination == "page" && is_numeric($pageNumber)) ? $pageNumber + 1 : $nextPage;
			$nextPage = ($nextPage >= $totalPages) ?  $totalPages : $nextPage;
			$prevPage = (isset($_GET[$pageURL[1]])) ? $_GET[$pageURL[1]] - 1 : 0;
			$prevPage = ($checkPagination == "page" && is_numeric($pageNumber)) ? $pageNumber - 1 : $prevPage;
			$prevPage = ($prevPage > $totalPages) ?  $totalPages : $prevPage;
			$adjacents = ((isset($_GET[$pageURL[1]]) && $_GET[$pageURL[1]] == 6) || ($checkPagination == "page" && is_numeric($pageNumber) && $pageNumber == 6)) ? 1 : $adjacents;
			$paginationHtml .= '<section class="tmdb">';
			$paginationHtml .= '<div align="center">';
			$paginationHtml .= '<div class="blog_row">';
			$paginationHtml .= '<nav class="post-pagination">';
			if (isset($pageURL[2]) && $pageURL[2]) {
				$paginationHtml .= '<span class="tmdb-pages-nb">';
				$paginationHtml .= '<li class="movies-list-next-page">';
				$paginationHtml .= '<a class="page-numbers" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, $nextPage, $isQuery) . '">Next</a>';
				$paginationHtml .= '</li>';
				$paginationHtml .= '</span>';
			}

			$paginationHtml .= '<ul class="page-numbers ' . $adminClass . '">';
			$paginationHtml .= ($isAdmin || $isNextPrevious) ? '<li><a class="' . $nextPreviousClass . ' page-numbers" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, $prevPage, $isQuery) . '">← Previous</a></li>' : '';
			if ($totalPages <= 10) {
				$paginationHtml .= bixxs_events_bixxs_events_randerPagination($totalPages, $activeClass, $pageURL, $querySap, $isQuery);
			} else if ($totalPages > 10) {
				if (isset($_GET[$pageURL[1]]) && $_GET[$pageURL[1]] <= 5) {
					$paginationHtml .= bixxs_events_randerPagination(8, $activeClass, $pageURL, $querySap, $isQuery);
					$paginationHtml .= bixxs_events_postPaginationRander($activeClass, $secondLast, $totalPages, $pageURL, $querySap, $isQuery);
				} else if (isset($_GET[$pageURL[1]]) && $_GET[$pageURL[1]] > 5 && $_GET[$pageURL[1]] < $totalPages - 5) {
					$paginationHtml .= bixxs_events_prePaginationRander($activeClass, $pageURL, $querySap, $isQuery);
					$paginationHtml .= bixxs_events_randerPagination($totalPages, $activeClass, $pageURL, $querySap, $isQuery, ($_GET[$pageURL[1]] - $adjacents), ($_GET[$pageURL[1]] + $adjacents));
					$paginationHtml .= bixxs_events_postPaginationRander($activeClass, $secondLast, $totalPages, $pageURL, $querySap, $isQuery);
				} else {
					$paginationHtml .= bixxs_events_prePaginationRander($activeClass, $pageURL, $querySap, $isQuery);
					$paginationHtml .= bixxs_events_randerPagination($totalPages, $activeClass, $pageURL, $querySap, $isQuery, ($totalPages - 6));
				}
			}
			$paginationHtml .= ($isAdmin || $isNextPrevious) ? '<li><a class="' . $nextPreviousClass . ' page-numbers" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, $nextPage, $isQuery) . '">Next →</a></li>' : '';
			$paginationHtml .= '</ul>';
			$paginationHtml .= '</nav>';
			$paginationHtml .= '</div>';
			$paginationHtml .= '</div>';
			$paginationHtml .= '</section>';
		}
	}
	return $paginationHtml;
}

function bixxs_events_getPaginationPageURL($pageURL, $querySap, $page, $isQuery, $isPrev = 0)
{
	$paginationURL = ($isQuery) ? $pageURL[0] . "/page/" . $page : $pageURL[0] . $querySap . http_build_query([$pageURL[1] => $page]);
	return ($page <= 0) ? $pageURL[0] : $paginationURL;
}

function bixxs_events_prePaginationRander($activeClass, $pageURL, $querySap, $isQuery)
{
	$currentClass = (isset($pageURL[0]) && $pageURL[0] == bixxs_events_currentPageURL()) ? '<li class="' . $activeClass . '"><span aria-current="page" class="page-numbers current">1</span></li>' : '<li><a class="page-numbers" pageNumber="1" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, 1, $isQuery) . '">1</a></li>';

	$paginationHtml = '';
	$paginationHtml .= $currentClass;
	$paginationHtml .= '<li><a class="page-numbers" pageNumber="2" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, 2, $isQuery) . '">2</a></li>';
	$paginationHtml .= '<li><a class="page-numbers" pageNumber="3" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, 3, $isQuery) . '">3</a></li>';
	$paginationHtml .= '<li><a class="page-numbers" pageNumber="4" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, 4, $isQuery) . '">4</a></li>';
	$paginationHtml .= '<li><a class="page-numbers" pageNumber="5" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, 5, $isQuery) . '">5</a></li>';
	$paginationHtml .= '<li class="' . $activeClass . '"><span aria-current="page" class="page-numbers current">...</span></li>';

	return $paginationHtml;
}

function bixxs_events_postPaginationRander($activeClass, $secondLast, $totalPages, $pageURL, $querySap, $isQuery)
{
	$paginationHtml = '';
	$paginationHtml .= '<li class="' . $activeClass . '"><span aria-current="page" class="page-numbers current">...</span></li>';
	$paginationHtml .= '<li><a class="page-numbers" pageNumber="' . $secondLast . '" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, $secondLast, $isQuery) . '">' . $secondLast . '</a></li>';
	$paginationHtml .= '<li><a class="page-numbers" pageNumber="' . $totalPages . '" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, $totalPages, $isQuery) . '">' . $totalPages . '</a></li>';

	return $paginationHtml;
}

function bixxs_events_randerPagination($totalPages, $activeClass, $pageURL, $querySap, $isQuery, $startCounter = 0, $endCounter = 0)
{
	$paginationHtml = "";

	if ($totalPages > 0) {
		$endCounter = ($endCounter == 0) ? $totalPages : $endCounter;
		$checkPagination = explode("/", bixxs_events_currentPageURL());
		$pageNumber = end($checkPagination);
		$checkPagination = prev($checkPagination);

		for ($counter = $startCounter; $counter < $endCounter; $counter++) {
			$number = $counter + 1;

			if ((isset($_GET[$pageURL[1]]) && $_GET[$pageURL[1]] > 0) || (isset($_POST['next_list_page']) && $_POST['next_list_page'] > 0) || ($checkPagination == "page" && is_numeric($pageNumber))) {
				if ((isset($_GET[$pageURL[1]]) && $number == $_GET[$pageURL[1]]) || (isset($_POST['next_list_page']) && $number == $_POST['next_list_page']) || ($checkPagination == "page" && is_numeric($pageNumber) && $number == $pageNumber)) {
					$paginationHtml .= '<li class="' . $activeClass . '"><span aria-current="page" class="page-numbers current">' . $number . '</span></li>';
				} else if ($counter == 0) {
					$paginationHtml .= '<li><a class="page-numbers" pageNumber="' . $number . '" href="' . $pageURL[0] . '">' . $number . '</a></li>';
				} else {
					$paginationHtml .= '<li><a class="page-numbers" pageNumber="' . $number . '" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, $number, $isQuery) . '">' . ($counter + 1) . '</a></li>';
				}
			} else {
				if ($counter == 1) {
					$paginationHtml .= '<li><a class="page-numbers" pageNumber="' . $number . '" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, $number, $isQuery) . '">' . $number . '</a></li>';
				} else if ($counter > 1) {
					$paginationHtml .= '<li><a class="page-numbers" pageNumber="' . $number . '" href="' . bixxs_events_getPaginationPageURL($pageURL, $querySap, $number, $isQuery) . '">' . $number . '</a></li>';
				} else {
					$paginationHtml .= '<li class="' . $activeClass . '"><span aria-current="page" class="page-numbers current">' . $number . '</span></li>';
				}
			}
		}
	}

	return $paginationHtml;
}

function bixxs_events_currentPageURL($urlType = "")
{
	$pageURL = 'http';
	$removePorts = [80, 443];
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if (!in_array($_SERVER["SERVER_PORT"], $removePorts)) {
		$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	} else {
		if ($urlType == "feed") {
			$requestURI = (strpos($_SERVER["REQUEST_URI"], "?") !== false) ? str_replace("?", "/feed?", $_SERVER["REQUEST_URI"]) : $_SERVER["REQUEST_URI"] . "/feed";
		} else {
			$requestURI = $_SERVER["REQUEST_URI"];
		}
		$pageURL .= $_SERVER["SERVER_NAME"] . $requestURI;
	}
	return $pageURL;
}
