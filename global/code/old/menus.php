<?php

/**
 * This file defines all functions relating to the menus / internal navigation within Form Tools.
 *
 * @copyright Benjamin Keen 2014
 * @author Benjamin Keen <ben.keen@gmail.com>
 * @package 2-2-x
 * @subpackage Menus
 */


// -------------------------------------------------------------------------------------------------

use FormTools\Pages;


/**
 * This function creates a blank client menu with no menu items.
 *
 * @return integer $menu_id
 */
function ft_create_blank_client_menu()
{
	global $g_table_prefix, $LANG;

	// to ensure that even new blank menus have unique names, query the database and find
	// the next free menu name of the form "Client Menu (X)" (where "Client Menu" is in the language
	// of the current user)
	$menus = ft_get_menu_list();
	$menu_names = array();
	foreach ($menus as $menu_info)
		$menu_names[] = $menu_info["menu"];

	$base_client_menu_name = $LANG["phrase_client_menu"];
	$new_menu_name = $base_client_menu_name;

	if (in_array($new_menu_name, $menu_names))
	{
		$count = 1;
		$new_menu_name = "$base_client_menu_name ($count)";

		while (in_array($new_menu_name, $menu_names))
		{
			$count++;
			$new_menu_name = "$base_client_menu_name ($count)";
		}
	}

	mysql_query("
    INSERT INTO {$g_table_prefix}menus (menu, menu_type)
    VALUES ('$new_menu_name', 'client')
     ");

	$menu_id = mysql_insert_id();
	return $menu_id;
}

/**
 * Retrieves a list of all menus. Note, this is only called by administrators
 * and relies on the number of menus per page being set in Sessions.
 *
 * @return array a hash of view information
 */
function ft_get_menus($page_num = 1)
{
	global $g_table_prefix;

	$num_menus_per_page = $_SESSION["ft"]["settings"]["num_menus_per_page"];

	// determine the LIMIT clause
	$limit_clause = "";
	if (empty($page_num))
		$page_num = 1;
	$first_item = ($page_num - 1) * $num_menus_per_page;
	$limit_clause = "LIMIT $first_item, $num_menus_per_page";

	$result = mysql_query("
    SELECT *
    FROM 	 {$g_table_prefix}menus
    ORDER BY menu
     $limit_clause
      ");
	$count_result = mysql_query("
    SELECT count(*) as c
    FROM 	 {$g_table_prefix}menus
      ");
	$count_hash = mysql_fetch_assoc($count_result);

	// select all account associated with this menu
	$info = array();
	while ($row = mysql_fetch_assoc($result))
	{
		$menu_id = $row["menu_id"];

		$account_query = mysql_query("
      SELECT account_id, first_name, last_name, account_type
      FROM   {$g_table_prefix}accounts a
      WHERE  menu_id = $menu_id
        ");

		$accounts = array();
		while ($account_row = mysql_fetch_assoc($account_query))
			$accounts[] = $account_row;

		$row["account_info"] = $accounts;
		$info[] = $row;
	}

	$return_hash["results"] = $info;
	$return_hash["num_results"]  = $count_hash["c"];

	extract(Hooks::processHookCalls("end", compact("return_hash"), array("return_hash")), EXTR_OVERWRITE);

	return $return_hash;
}


/**
 * Returns an array of menu hashes for all menus in the database. Ordered by menu name.
 *
 * @return array
 */
function ft_get_menu_list()
{
	global $g_table_prefix;

	$query = mysql_query("
    SELECT *
    FROM   {$g_table_prefix}menus
    ORDER BY menu
      ");

	$menus = array();
	while ($row = mysql_fetch_assoc($query))
		$menus[] = $row;

	extract(Hooks::processHookCalls("end", compact("menus"), array("menus")), EXTR_OVERWRITE);

	return $menus;
}


/**
 * Returns the one (and only) administration menu, and all associated menu items.
 *
 * @return array
 */
function ft_get_admin_menu()
{
	global $g_table_prefix;

	$result = mysql_query("
    SELECT *
    FROM   {$g_table_prefix}menus
    WHERE  menu_type = 'admin'
      ");

	$menu_info = mysql_fetch_assoc($result);
	$menu_id = $menu_info["menu_id"];

	// now get all the menu items and stash them in a "menu_items" key in $menu_info
	$menu_item_query = mysql_query("
    SELECT *
    FROM   {$g_table_prefix}menu_items
    WHERE  menu_id = $menu_id
    ORDER BY list_order
      ");

	$menu_items = array();
	while ($item = mysql_fetch_assoc($menu_item_query))
		$menu_items[] = $item;

	$menu_info["menu_items"] = $menu_items;

	extract(Hooks::processHookCalls("end", compact("menu_info"), array("menu_info")), EXTR_OVERWRITE);

	return $menu_info;
}


/**
 * Returns everything about a client menu. Bit of a misnomer, since it also returns the admin menu.
 *
 * @param integer $menu_id
 * @return array
 */
function ft_get_client_menu($menu_id)
{
	global $g_table_prefix;

	$result = mysql_query("
    SELECT *
    FROM   {$g_table_prefix}menus
    WHERE  menu_id = $menu_id
      ");

	$menu_info = mysql_fetch_assoc($result);
	$menu_info["menu_items"] = ft_get_menu_items($menu_id);

	// get all associated client accounts
	$menu_clients_query = mysql_query("
    SELECT *
    FROM   {$g_table_prefix}accounts
    WHERE  menu_id = $menu_id
    ORDER BY first_name
      ");

	$menu_clients = array();
	while ($client = mysql_fetch_assoc($menu_clients_query))
		$menu_clients[] = $client;

	$menu_info["clients"] = $menu_clients;

	extract(Hooks::processHookCalls("end", compact("menu_info"), array("menu_info")), EXTR_OVERWRITE);

	return $menu_info;
}


/**
 * Returns all menu items for a particular menu.
 *
 * @param integer $menu_id
 * @return array an array of menu hashes
 */
function ft_get_menu_items($menu_id)
{
	global $g_table_prefix;

	// now get all the menu items and stash them in a "menu_items" key in $menu_info
	$menu_item_query = mysql_query("
    SELECT *
    FROM   {$g_table_prefix}menu_items
    WHERE  menu_id = $menu_id
    ORDER BY list_order
      ");

	$menu_items = array();
	while ($item = mysql_fetch_assoc($menu_item_query))
		$menu_items[] = $item;

	extract(Hooks::processHookCalls("end", compact("menu_items", "menu_id"), array("menu_items")), EXTR_OVERWRITE);

	return $menu_items;
}


/**
 * A wrapper function for ft_get_client_menu (and ft_get_admin_menu). Returns all info
 * about a menu, regardless of type. If it's an admin menu, it'll be returned with an empty "clients"
 * hash key.
 *
 * @param integer $menu_id
 * @return
 */
function ft_get_menu($menu_id)
{
	return ft_get_client_menu($menu_id);
}


/**
 * This function builds the dropdown menu that lists all available pages for an administrator account.
 *
 * @param string $default_value
 * @param array $attributes a hash of attributes, e.g. "name" => "row1", "onchange" => "myfunc()"
 * @param boolean $omit_pages this determines which fields should be OMITTED from the generated
 *   HTML; it's an array whose values correspond to the page names found at the top of this file.
 * @return string
 */
function ft_get_admin_menu_pages_dropdown($selected, $attributes, $is_building_menu, $omit_pages = array())
{
	global $LANG;

	// stores the non-option lines of the select box: <select>, </select> and the optgroups
	$select_lines   = array();
	$select_lines[] = array("type" => "select_open");

	if (!in_array("", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "", "v" => $LANG["phrase_please_select"]);

	if (!in_array("custom_url", $omit_pages))
	{
		$select_lines[] = array("type" => "optgroup_open", "label" => $LANG["word_custom"]);
		$select_lines[] = array("type" => "option", "k" => "custom_url", "v" => $LANG["phrase_custom_url"]);
		$select_lines[] = array("type" => "optgroup_close");
	}

	$select_lines[] = array("type" => "optgroup_open", "label" => $LANG["word_forms"]);

	if (!in_array("admin_forms", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "admin_forms", "v" => $LANG["word_forms"]);
	if (!in_array("option_lists", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "option_lists", "v" => $LANG["phrase_option_lists"]);
	if (!in_array("add_form", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "add_form_choose_type", "v" => $LANG["phrase_add_form"]);
	if (!in_array("add_form_internal", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "add_form_internal", "v" => $LANG["phrase_add_form_internal"]);
	if (!in_array("add_form1", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "add_form1", "v" => $LANG["phrase_add_form_external"]);

	if ($is_building_menu)
	{
		if (!in_array("form_submissions", $omit_pages))
			$select_lines[] = array("type" => "option", "k" => "form_submissions", "v" => $LANG["phrase_form_submissions"]);
		if (!in_array("edit_form", $omit_pages))
			$select_lines[] = array("type" => "option", "k" => "edit_form", "v" => $LANG["phrase_edit_form"]);
		if (!in_array("edit_form_main", $omit_pages))
			$select_lines[] = array("type" => "option", "k" => "edit_form_main", "v" => "{$LANG["phrase_edit_form"]} - {$LANG["word_main"]}");
		if (!in_array("edit_form_fields", $omit_pages))
			$select_lines[] = array("type" => "option", "k" => "edit_form_fields", "v" => "{$LANG["phrase_edit_form"]} - {$LANG["word_fields"]}");
		if (!in_array("edit_form_views", $omit_pages))
			$select_lines[] = array("type" => "option", "k" => "edit_form_views", "v" => "{$LANG["phrase_edit_form"]} - {$LANG["word_views"]}");
		if (!in_array("edit_form_emails", $omit_pages))
			$select_lines[] = array("type" => "option", "k" => "edit_form_emails", "v" => "{$LANG["phrase_edit_form"]} - {$LANG["word_emails"]}");
	}

	$select_lines[] = array("type" => "optgroup_close");

	$select_lines[] = array("type" => "optgroup_open", "label" => $LANG["word_clients"]);
	if (!in_array("clients", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "clients", "v" => $LANG["word_clients"]);
	if (!in_array("add_client", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "add_client", "v" => $LANG["phrase_add_client"]);

	if ($is_building_menu)
	{
		if (!in_array("edit_client", $omit_pages))
			$select_lines[] = array("type" => "option", "k" => "edit_client", "v" => $LANG["phrase_edit_client"]);
		if (!in_array("edit_client_main", $omit_pages))
			$select_lines[] = array("type" => "option", "k" => "edit_client_main", "v" => $LANG["word_main"]);
		if (!in_array("edit_client_permissions", $omit_pages))
			$select_lines[] = array("type" => "option", "k" => "edit_client_permissions", "v" => $LANG["word_permissions"]);
	}

	$select_lines[] = array("type" => "optgroup_close");
	$select_lines[] = array("type" => "optgroup_open", "label" => $LANG["word_modules"]);
	if (!in_array("modules", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "modules", "v" => $LANG["word_modules"]);

	$modules = ft_get_modules();
	for ($i=0; $i<count($modules); $i++)
	{
		$module_id = $modules[$i]["module_id"];
		$module    = $modules[$i]["module_name"];
		$select_lines[] = array("type" => "option", "k" => "module_{$module_id}", "v" => $module);
	}
	$select_lines[] = array("type" => "optgroup_close");

	// if the Pages module is enabled, display any custom pages that have been defined. Note: this would be better handled
	// in the hook added below
	if (ft_check_module_enabled("pages"))
	{
		ft_include_module("pages");
		$pages_info = pg_get_pages("all");
		$pages = $pages_info["results"];

		$select_lines[] = array("type" => "optgroup_open", "label" => $LANG["phrase_pages_module"]);
		for ($i=0; $i<count($pages); $i++)
		{
			$page_id = $pages[$i]["page_id"];
			$page_name = $pages[$i]["page_name"];
			$select_lines[] = array("type" => "option", "k" => "page_{$page_id}", "v" => $page_name);
		}

		$select_lines[] = array("type" => "optgroup_close");
	}

	extract(Hooks::processHookCalls("middle", compact("select_lines"), array("select_lines")), EXTR_OVERWRITE);

	$select_lines[] = array("type" => "optgroup_open", "label" => $LANG["word_other"]);
	$select_lines[] = array("type" => "option", "k" => "your_account", "v" => $LANG["phrase_your_account"]);
	$select_lines[] = array("type" => "option", "k" => "settings_themes", "v" => "{$LANG["word_themes"]}");
	$select_lines[] = array("type" => "option", "k" => "settings", "v" => $LANG["word_settings"]);
	$select_lines[] = array("type" => "option", "k" => "settings_main", "v" => "{$LANG["word_settings"]} - {$LANG["word_main"]}");
	$select_lines[] = array("type" => "option", "k" => "settings_accounts", "v" => "{$LANG["word_settings"]} - {$LANG["word_accounts"]}");
	$select_lines[] = array("type" => "option", "k" => "settings_files", "v" => "{$LANG["word_settings"]} - {$LANG["word_files"]}");
	$select_lines[] = array("type" => "option", "k" => "settings_menus", "v" => "{$LANG["word_settings"]} - {$LANG["word_menus"]}");
	if (!in_array("logout", $omit_pages))
	{
		$select_lines[] = array("type" => "option", "k" => "logout", "v" => $LANG["word_logout"]);
	}
	$select_lines[] = array("type" => "optgroup_close");

	$select_lines[] = array("type" => "select_close");


	// now build the HTML
	$dd = "";
	foreach ($select_lines as $line)
	{
		switch ($line["type"])
		{
			case "select_open":
				$attribute_str = "";
				while (list($key, $value) = each($attributes))
					$attribute_str .= " $key=\"$value\"";
				$dd .= "<select $attribute_str>";
				break;
			case "select_close":
				$dd .= "</select>";
				break;
			case "optgroup_open":
				$dd .= "<optgroup label=\"{$line["label"]}\">";
				break;
			case "optgroup_close":
				$dd .= "</optgroup>";
				break;
			case "option":
				$key   = $line["k"];
				$value = $line["v"];
				$dd .= "<option value=\"{$key}\"" . (($selected == $key) ? " selected" : "") . ">$value</option>\n";
				break;
		}
	}

	return $dd;
}


/**
 * Builds a dropdown of available pages for a client. This is used by the administrator
 * to display the list of pages available for the clients menus, etc.
 *
 * @param string $selected
 * @param array $attributes
 * @param array $omit_pages
 */
function ft_get_client_menu_pages_dropdown($selected, $attributes, $omit_pages = array())
{
	global $LANG;

	// stores the non-option lines of the select box: <select>, </select> and the optgroups
	$select_lines   = array();
	$select_lines[] = array("type" => "select_open");

	if (!in_array("", $omit_pages))
		$select_lines[] = array("type" => "option", "v" => $LANG["phrase_please_select"]);

	if (!in_array("custom_url", $omit_pages))
	{
		$select_lines[] = array("type" => "optgroup_open", "label" => $LANG["word_custom"]);
		$select_lines[] = array("type" => "option", "k" => "custom_url", "v" => $LANG["phrase_custom_url"]);
		$select_lines[] = array("type" => "optgroup_close");
	}

	$select_lines[] = array("type" => "optgroup_open", "label" => $LANG["word_main"]);

	if (!in_array("client_forms", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "client_forms", "v" => $LANG["word_forms"]);
	if (!in_array("client_form_submissions", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "client_form_submissions", "v" => $LANG["phrase_form_submissions"]);
	if (!in_array("client_account", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "client_account", "v" => $LANG["word_account"]);
	if (!in_array("client_account_login", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "client_account_login", "v" => $LANG["phrase_login_info"]);
	if (!in_array("client_account_settings", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "client_account_settings", "v" => $LANG["phrase_account_settings"]);
	if (!in_array("logout", $omit_pages))
		$select_lines[] = array("type" => "option", "k" => "logout", "v" => $LANG["word_logout"]);

	$select_lines[] = array("type" => "optgroup_close");

	// if the Pages module is enabled, display any custom pages that have been defined. Only show the optgroup
	// if there's at least ONE page defined
	if (ft_check_module_enabled("pages"))
	{
		ft_include_module("pages");
		$pages_info = pg_get_pages("all");
		$pages = $pages_info["results"];

		if (count($pages) > 0)
		{
			$select_lines[] = array("type" => "optgroup_open", "label" => $LANG["phrase_pages_module"]);
			foreach ($pages as $page)
			{
				$page_id = $page["page_id"];
				$page_name = $page["page_name"];
				$select_lines[] = array("type" => "option", "k" => "page_{$page_id}", "v" => $page_name);
			}
			$select_lines[] = array("type" => "optgroup_close");
		}
	}

	extract(Hooks::processHookCalls("middle", compact("select_lines"), array("select_lines")), EXTR_OVERWRITE);

	$select_lines[] = array("type" => "select_close");

	// now build the HTML
	$dd = "";
	foreach ($select_lines as $line)
	{
		switch ($line["type"])
		{
			case "select_open":
				$attribute_str = "";
				while (list($key, $value) = each($attributes))
					$attribute_str .= " $key=\"$value\"";
				$dd .= "<select $attribute_str>";
				break;
			case "select_close":
				$dd .= "</select>";
				break;
			case "optgroup_open":
				$dd .= "<optgroup label=\"{$line["label"]}\">";
				break;
			case "optgroup_close":
				$dd .= "</optgroup>";
				break;
			case "option":
				$key   = $line["k"];
				$value = $line["v"];
				$dd .= "<option value=\"{$key}\"" . (($selected == $key) ? " selected" : "") . ">$value</option>\n";
				break;
		}
	}

	return $dd;
}


/**
 * Updates the (single) administration menu.
 *
 * @param array $info
 */
function ft_update_admin_menu($info)
{
	global $g_table_prefix, $g_pages, $g_root_url, $LANG;

	$menu_id     = $info["menu_id"];
	$account_id  = $info["account_id"];
	$sortable_id = $info["sortable_id"];

	$sortable_rows       = explode(",", $info["{$sortable_id}_sortable__rows"]);
	$sortable_new_groups = explode(",", $info["{$sortable_id}_sortable__new_groups"]);

	$menu_items = array();
	foreach ($sortable_rows as $i)
	{
		// if this row doesn't have a page identifier, just ignore it
		if (!isset($info["page_identifier_$i"]) || empty($info["page_identifier_$i"]))
			continue;

		$page_identifier = $info["page_identifier_$i"];
		$display_text    = $info["display_text_$i"];
		$custom_options  = isset($info["custom_options_$i"]) ? $info["custom_options_$i"] : "";
		$is_submenu      = isset($info["submenu_$i"]) ? "yes" : "no";

		// construct the URL for this menu item
		$url = Pages::constructPageURL($page_identifier, $custom_options);

		$menu_items[] = array(
			"url"               => $url,
			"page_identifier"   => $page_identifier,
			"display_text"      => $display_text,
			"custom_options"    => $custom_options,
			"is_submenu"        => $is_submenu,
			"is_new_sort_group" => (in_array($i, $sortable_new_groups)) ? "yes" : "no"
		);
	}

	mysql_query("DELETE FROM {$g_table_prefix}menu_items WHERE menu_id = $menu_id");

	$order = 1;
	foreach ($menu_items as $hash)
	{
		$url               = $hash["url"];
		$page_identifier   = $hash["page_identifier"];
		$display_text      = $hash["display_text"];
		$custom_options    = $hash["custom_options"];
		$is_submenu        = $hash["is_submenu"];
		$is_new_sort_group = $hash["is_new_sort_group"];

		mysql_query("
      INSERT INTO {$g_table_prefix}menu_items (menu_id, display_text, page_identifier, custom_options, url,
        is_submenu, list_order, is_new_sort_group)
      VALUES ($menu_id, '$display_text', '$page_identifier', '$custom_options', '$url', '$is_submenu',
        $order, '$is_new_sort_group')
        ");
		$order++;
	}

	// update the administrator's cache, so the menu automatically updates
	ft_cache_account_menu($account_id);

	$success = true;
	$message = $LANG["notify_admin_menu_updated"];
	extract(Hooks::processHookCalls("end", compact("success", "message", "info"), array("success", "message")), EXTR_OVERWRITE);

	return array($success, $message);
}


/**
 * Called whenever an item is removed from a menu - OUTSIDE of the main administrator update menu
 * pages (client & admin). This updates the order to ensure its consistent (i.e. no gaps). Note:
 * this doesn't update the cached menu. If that's needed, you need to call the ft_cache_account_menu
 * function separately
 *
 * @param integer $menu_id
 */
function ft_update_menu_order($menu_id)
{
	global $g_table_prefix;

	// this returns the menu items ordered by list order
	$menu_items = ft_get_menu_items($menu_id);

	// now update the list orders to ensure no gaps
	$order = 1;
	foreach ($menu_items as $menu_item)
	{
		$menu_item_id = $menu_item["menu_item_id"];

		mysql_query("
      UPDATE {$g_table_prefix}menu_items
      SET    list_order = $order
      WHERE  menu_item_id = $menu_item_id
        ");
		$order++;
	}

	extract(Hooks::processHookCalls("end", compact("menu_id"), array()), EXTR_OVERWRITE);
}


/**
 * Updates a client menu.
 *
 * @param array $info
 */
function ft_update_client_menu($info)
{
	global $g_table_prefix, $LANG;

	$menu_id = $info["menu_id"];
	$menu    = trim($info["menu"]);
	$sortable_id = $info["sortable_id"];

	mysql_query("
    UPDATE {$g_table_prefix}menus
    SET    menu    = '$menu'
    WHERE  menu_id = $menu_id
      ");

	$sortable_rows       = explode(",", $info["{$sortable_id}_sortable__rows"]);
	$sortable_new_groups = explode(",", $info["{$sortable_id}_sortable__new_groups"]);

	$menu_items = array();
	foreach ($sortable_rows as $i)
	{
		// if this row doesn't have a page identifier, just ignore it
		if (!isset($info["page_identifier_$i"]) || empty($info["page_identifier_$i"]))
			continue;

		$page_identifier = $info["page_identifier_$i"];
		$display_text    = $info["display_text_$i"];
		$custom_options  = isset($info["custom_options_$i"]) ? $info["custom_options_$i"] : "";
		$is_submenu      = isset($info["submenu_$i"]) ? "yes" : "no";

		// construct the URL for this menu item
		$url = ft_construct_page_url($page_identifier, $custom_options);

		$menu_items[] = array(
			"url" => $url,
			"page_identifier"   => $page_identifier,
			"display_text"      => $display_text,
			"custom_options"    => $custom_options,
			"is_submenu"        => $is_submenu,
			"is_new_sort_group" => (in_array($i, $sortable_new_groups)) ? "yes" : "no"
		);
	}

	ksort($menu_items);
	mysql_query("DELETE FROM {$g_table_prefix}menu_items WHERE menu_id = $menu_id");

	$order = 1;
	foreach ($menu_items as $hash)
	{
		$url               = $hash["url"];
		$page_identifier   = $hash["page_identifier"];
		$display_text      = $hash["display_text"];
		$custom_options    = $hash["custom_options"];
		$is_submenu        = $hash["is_submenu"];
		$is_new_sort_group = $hash["is_new_sort_group"];

		mysql_query("
      INSERT INTO {$g_table_prefix}menu_items (menu_id, display_text, page_identifier, custom_options, url, is_submenu,
        list_order, is_new_sort_group)
      VALUES ($menu_id, '$display_text', '$page_identifier', '$custom_options', '$url', '$is_submenu',
        $order, '$is_new_sort_group')
        ");
		$order++;
	}

	$success = true;
	$message = $LANG["notify_client_menu_updated"];
	extract(Hooks::processHookCalls("end", compact("info"), array("success", "message")), EXTR_OVERWRITE);

	return array($success, $message);
}


/**
 * Deletes a client menu. Since it's possible for one or more clients to already be associated with the
 * menu, those clients will be orphaned by this action. In this situation, it refuses to delete the
 * menu, and lists all clients that will be affected (each a link to their account). It also provides
 * an option to bulk assign them to another menu.
 *
 * In all likelihood, however, the administrator will already be aware of this, having seen their names
 * listed in the table where they chose to delete the menu.
 *
 * @param integer $menu_id
 * @return array [0] T/F, [1] message
 */
function ft_delete_client_menu($menu_id)
{
	global $g_table_prefix, $g_root_url, $LANG;

	extract(Hooks::processHookCalls("start", compact("menu_id"), array()), EXTR_OVERWRITE);

	// confirm that there are no client accounts that currently use this menu
	$query = mysql_query("
    SELECT account_id, first_name, last_name
    FROM   {$g_table_prefix}accounts
    WHERE  menu_id = $menu_id
  ");

	$client_info = array();
	while ($row = mysql_fetch_assoc($query))
		$client_info[] = $row;

	if (!empty($client_info))
	{
		$message = $LANG["notify_deleted_menu_already_assigned"];
		$placeholder_str = $LANG["phrase_assign_all_listed_client_accounts_to_menu"];

		$menus = ft_get_menu_list();
		$dd = "<select id=\"mass_update_client_menu\">";
		foreach ($menus as $menu_info)
		{
			if ($menu_info["menu_type"] == "admin")
				continue;

			$dd .= "<option value=\"{$menu_info["menu_id"]}\">{$menu_info["menu"]}</option>";
		}

		$dd .= "</select>";

		// a bit bad (hardcoded HTML!), but organize the account list in 3 columns
		$client_links_table = "<table cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">\n<tr>";
		$num_affected_clients = count($client_info);
		for ($i=0; $i<$num_affected_clients; $i++)
		{
			$account_info = $client_info[$i];
			$client_id  = $account_info["account_id"];
			$first_name = $account_info["first_name"];
			$last_name  = $account_info["last_name"];
			$client_ids[] = $client_id;

			if ($i != 0 && $i % 3 == 0)
				$client_links_table .= "</tr>\n<tr>";

			$client_links_table .= "<td width=\"33%\">&bull;&nbsp;<a href=\"$g_root_url/admin/clients/edit.php?page=settings&client_id=$client_id\" target=\"_blank\">$first_name $last_name</a></td>\n";
		}
		$client_id_str = join(",", $client_ids);

		// close the table
		if ($num_affected_clients % 3 == 1)
			$client_links_table .= "<td colspan=\"2\" width=\"66%\"> </td>";
		else if ($num_affected_clients % 3 == 2)
			$client_links_table .= "<td width=\"33%\"> </td>";

		$client_links_table .= "</tr></table>";

		$submit_button = "<input type=\"button\" value=\"{$LANG["phrase_update_accounts"]}\" onclick=\"window.location='index.php?page=menus&mass_assign=1&accounts=$client_id_str&menu_id=' + $('#mass_update_client_menu').val()\" />";

		$placeholders = array(
			"menu_dropdown" => $dd,
			"submit_button" => $submit_button
		);

		$mass_assign_html = "<div class=\"margin_top_large margin_bottom_large\">" . General::evalSmartyString($placeholder_str, $placeholders) . "</div>";
		$html = $message . $mass_assign_html . $client_links_table;

		return array(false, $html);
	}

	// ------------------------------------------------------------


	$client_account_query = mysql_query("
    SELECT account_id, first_name, last_name
    FROM   {$g_table_prefix}accounts
    WHERE  menu_id = $menu_id
  ");

	// delete the menu
	mysql_query("DELETE FROM {$g_table_prefix}menus WHERE menu_id = $menu_id");
	mysql_query("DELETE FROM {$g_table_prefix}menu_items WHERE menu_id = $menu_id");

	// construct the message to return to the administrator
	$client_accounts = array();
	while ($row = mysql_fetch_assoc($client_account_query))
		$client_accounts[] = $row;

	if (empty($client_accounts))
	{
		$success = true;
		$message = $LANG["notify_client_menu_deleted"];
	}
	else
	{
		$success = false;
		$message = $LANG["notify_client_menu_deleted_orphaned_accounts"];

		$accounts_str = "<br />";
		foreach ($client_accounts as $account_info)
		{
			$client_id  = $account_info["account_id"];
			$first_name = $account_info["first_name"];
			$last_name  = $account_info["last_name"];

			$accounts_str .= "&bull;&nbsp;<a href=\"$g_root_url/admin/clients/edit.php?client_id=$client_id\" target=\"_blank\">$first_name $last_name</a><br />\n";
		}

		$message .= $accounts_str;
	}

	return array($success, $message);
}


/**
 * This function updates the default menu for multiple accounts simultaneously. It's called when
 * an administrator tries to delete a menu that's current used by some client accounts. They're presented
 * with the option of setting the menu ID for all the clients.
 *
 * There's very little error checking done here...
 *
 * @param string $account_id_str a comma delimited list of account IDs
 * @param integer $theme_id the theme ID
 */
function ft_update_client_menus($account_ids, $menu_id)
{
	global $LANG, $g_table_prefix;

	if (empty($account_ids) || empty($menu_id))
		return;

	$client_ids = explode(",", $account_ids);

	$menu_info = ft_get_menu($menu_id);
	$menu_name = $menu_info["menu"];

	foreach ($client_ids as $client_id)
		mysql_query("UPDATE {$g_table_prefix}accounts SET menu_id=$menu_id WHERE account_id = $client_id");

	$placeholders = array("menu_name" => $menu_name);
	$message = General::evalSmartyString($LANG["notify_client_account_menus_updated"], $placeholders);
	return array(true, $message);
}
