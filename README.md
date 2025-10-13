# My Projects Link Redirect
A REDCap External Module to change links on My Projects to point to a custom landing page for specified users.

## Prerequisites
 - REDCap >= 14.6.4

## Easy installation
- Install the _My Projects Link Redirect_ module from the Consortium [REDCap Repo](https://redcap.vumc.org/consortium/modules/index.php) from the Control Center.

## Manual Installation
- Clone this repo into to `<redcap-root>/modules/my_projects_link_redirect_v0.0.0`.

## Introduction

This module changes the landing page users are sent when they click the corresponding project title on the My Projects page. It allows limiting the redirect to specific users. This module was developed to allow users to bypass a project's typical home page in favor of being sent directly to an External Module's project page.

## Limitations

<!-- Mention any limitations to this module that users may expect, e.g. won't work with repating instances or events, won't work with signature fields -->
This module _only_ alters links for the "Project Title" column on the "My Projects" page. It does _not_ redirect all traffic from one project to another (i.e. if a user bookmarked a project page, this module will not alter where they end up).

## System Configuration

- **Redirects**: Repeatable setting to define redirects
  - **Project to Redirect**: The project which you wish to apply redirects to.
  - **Redirect Link**: The link to which the project should redirect.
    - This may be any `http` URL or a path relative to your REDCap route (e.g. `/index.php?action=myprojects`).
    - Note that the project ID (and any other GET parameters) will not be automatically retained, they **must** be part of this link!
  - **Users to Redirect**: The users whose links should be redirected. **Leaving this option blank will result in _all_ users seeing this redict!**
  - **Custom link CSS**: Custom CSS (as a JSON object) to be applied to the Project Title link.
