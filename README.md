# Shorty

- - -

## Repository content

### Main App
This repository contains the main 'Shorty' owncloud app and one or more plugins to that app. 
Shorty itself is a comfortable url shortening suite with a twist. 

### Plugin Apps
The plugins provide additional features but are standalone apps from a technical point of view: 

Existing plugins: 
* shorty_tracking: tracking of requests and details to existing entries in the main app

Possible future plugins: 
* shorty_integration: integration of Shortys features into ownClouds share feature
* shorty_protocols: allows to shorten urls with protocols besides web: email, (s)ftp(s)
* shorty_yourls: support for yourls which serves as more than just a backend with its additonal features
* . . .

### Folder layout: 
* l10n:            the localization resources (owncloud uses the transifex service)
* shorty:          the main "Shorty" app
* shorty_tracking: the "Shorty Tracking" plugin to the main app

## Translation (l10n)
ownCloud usually uses an automatic extraction of translatable tokens from the files. UNfortunately this approach is only usable for plain and more simply scripts, it miserably fails when processing the more complex structures found inside this repository. All attempty to solve this issues lead to bloating the code and other unwanted problems. Because of this a decision was made to deactivate the automatic extraction and to maintain the l10n templates manually (one for each app). This means that you have to manually update those template files in the 'l10n/templates' folder if you change any strings that require translation!

## Maintainance

#### Current maintainer: 
* Christian Reiner (arkascha)

!!! These apps are looking for *contributors* and a *new maintainer* !!!

#### Current issues: 
See issue tracker here on github. 

#### Documentation: 
See contained 'doc' folder.
