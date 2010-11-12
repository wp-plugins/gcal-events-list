=== GCal Events List ===
Contributors: carlo daniele
Tags: google, calendar, events, widget
Requires at least: 3.0.1
Tested up to: 3.0.1
Stable tag: 0.1

GCal Events List retrieve future events from a public Google Calendar and shows data in a widget.

== Description ==

GCal Events List retrieve events from Google Calendar and shows data in a widget.

To make GCal Events List work properly, you have to activate the plugin and paste a public calendar ID in the widget admin panel.

It requires some other options. Here is the full list of params:

1. Title (widget title)
1. Calendar ID (you can find it in the option panel of your Google Calendar)
1. Order by (possible values are starttime and lastmodified)
1. Sort order (ascending or descending)
1. Max results
1. Start min (a valid date in YYYY-MM-DD)
1. Start max (a valid date in YYYY-MM-DD)

For a detailed description of calendar settings, read the [Google Calendar Data API reference](http://code.google.com/intl/it-IT/apis/calendar/data/2.0/reference.html)

**REMEMBER**: your Google Calendar must be public, otherwise the widget won't show any event!

== Installation ==

1. Extract gcal_events_list.zip
1. Upload the plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure your *GCal Events List* widgets through the widgets admin page.  

== Screenshots ==

1. The widget admin panel
2. The widget rendered in the fornt-end

== Changelog ==

0.1: Initial plugin release