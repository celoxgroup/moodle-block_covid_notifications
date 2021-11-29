[![Build Status](https://travis-ci.org/learningworks/moodle-block_advnotifications.svg?branch=master)](https://travis-ci.org/learningworks/moodle-block_advnotifications)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](README.md)

## Easily create, manage and display notifications/alerts to users

This block allows users to display COVID-19 alerts, which are Bootstrap-based, allowing for various configurations.
This could be useful in cases such as alerting users of scheduled outages, welcoming them to your site, teachers can use it to notify users of changes/due dates, etc.


### Features:

* Customisable title & message
* Basic HTML tags allowed for advanced users
* Multiple types of notifications (Bootstrap-based styles)
* Type-based icons (optional setting)
* Dismissible/Non-Dismissible for a limited time
* Display a notification to the user dashboard
* Enable/Disable a/all notifications (Site-wide and instance-based)
* Student can submit covid certificate.
* Easy to use, but fully documented with all the nitty-gritty information
* Short tag support [filter plugin](https://github.com/branchup/moodle-filter_shortcodes)
* Diffrent notification on based user status 
* Cohorts selction available when user successfully upload and apporoved user add in cohorts for badge
* Select the role that can receive the data submitted by email
* Admin and manager report page where user information display in table


For full documentation, please check [here](docs/CovidNotifications.pdf) - or check the plugin's `/docs` directory.


#### Notification Anatomy

[![Alert Types](docs/AlertTypes.png)](docs/AlertTypes.png)


#### Installation Notice

All the plugin's settings are disabled by default. Enable it upon installation if you wish to start using it immediately or enable it later by navigating to Site Administration > Plugins > Blocks > Covid-19 notifications. 


### Shortcodes filter

This [filter plugin](https://github.com/branchup/moodle-filter_shortcodes) makes it easier and more reliable to add the items to your course content. We very highly recommend you to use it. This is a requirement to use the trading feature.



