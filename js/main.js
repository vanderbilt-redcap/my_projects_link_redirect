// JS documentation
// https://github.com/vanderbilt-redcap/external-module-framework-docs/blob/main/javascript.md

$(document).ready(() => {
  const module = ExternalModules.Vanderbilt.MyProjectsLinkRedirect.ExternalModule;
  const redirect_projects = module.tt("redirect_projects");

	redirect_projects.forEach((item) => {
		changeLink(item);
	});

function changeLink(redirect_setting) {
	const {target_pid, replacement_link} = redirect_setting;
	let custom_link_css = redirect_setting.custom_link_css ?? {};
	custom_link_css = JSON.parse(custom_link_css);
	target_link = $(`a[href$='index.php?pid=${target_pid}']`);
	const original_link = target_link.attr('href');

	target_link.attr('href', replacement_link);
	target_link.css(custom_link_css);
}

});
