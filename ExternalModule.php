<?php

namespace Vanderbilt\MyProjectsLinkRedirect\ExternalModule;

use ExternalModules\AbstractExternalModule;

class ExternalModule extends AbstractExternalModule
{
	private const REDIRECT_KEY_NAME = "redirs";

	public function redcap_every_page_top($project_id): void {
		// ensure user is on My Projects page
		if (!str_ends_with(PAGE, "index.php")) {
			return;
		}
		if ($_GET['action'] != "myprojects") {
			return;
		}

		$this->initializeJavascriptModuleObject();
		$redirect_projects = [];
		$cur_user = $this->getUser()->getUsername();

		$sys = $this->getSystemSettings(self::REDIRECT_KEY_NAME);
		foreach ($sys["target_project"]["system_value"] as $redirect_idx => $pr_id) {
			$active_for_users = array_filter($sys["user_list"]["system_value"][$redirect_idx]);
			if (!in_array($cur_user, $active_for_users) && !empty($active_for_users)) {
				continue;
			}

			$redirect_link = $sys["redirect_link"]["system_value"][$redirect_idx];
			$custom_link_css = $sys["custom_link_css"]["system_value"][$redirect_idx];

			$redirect_projects[] = [
				"target_pid" => $pr_id,
				"replacement_link" => $redirect_link,
				"custom_link_css" => $custom_link_css
			];
		}

		$this->tt_addToJavascriptModuleObject("redirect_projects", $redirect_projects);
		$this->addJs("js/main.js");
	}


	public function redcap_module_configuration_settings($project_id, $settings): array {
		// update user list with all users on system
		$user_list_key = "user_list";

		// reformat for expected dropdown
		$users = $this->getAllUsers();
		array_walk(
			$users,
			function (&$u, $key) {
				$u = [
						"value" => $u,
						"name" => $u
					];
			}
		);

		$parent_idx = array_search(
			self::REDIRECT_KEY_NAME,
			array_column($settings, "key")
		);

		$users_idx = array_search(
			$user_list_key,
			array_column($settings[$parent_idx]["sub_settings"], "key")
		);

		$settings[$parent_idx]["sub_settings"][$users_idx]["choices"] = $users;
		return $settings;
	}


	public function getAllUsers(): array {
		// NOTE: user-list configuration type does not work if not in project context
		$choices = [];
		$sql = <<<_SQL
			SELECT username from redcap_user_information
			ORDER BY ui_id;
		_SQL;
		$mysqli_result = $this->query($sql, []);

		$accumulator = [];
		while ($row = $mysqli_result->fetch_assoc()) {
			$accumulator[] = $row['username'];
		}

		return $accumulator;
	}


	public function addJS(string $path): void {
		echo "<script src='" . $this->getUrl($path) . "'></script>";
	}
}
