CSE204a Final Project Proposal
Calendar (Something like Google Calendar)
For this project I will build a simple calendar that allows users to add and remove events dynamically.
I will use JavaScript to process user interactions at the web browser, without ever refreshing the browser after the initial web page load. 
This application should utilize AJAX to run server-side scripts that query my database to save and retrieve information, including user accounts and events.
Examples
https://calendar.google.com/ 
http://www.zoho.com/calendar/
Server-Side Language
I will use MySQL as my database.
Features
	Support a month-by-month view of the calendar.
Show one month at a time, with buttons to move forward or backward.
There should be no limit to how far forward or backward the user can go.
	Users can register and log in to the website.
	Unregistered users should see no events on the calendar.
	Registered users can add events.
All events should have a date and time, but do not need to have a duration.
	Registered users see only events that they have added.
	Registered users can delete their events, but not the events of others.
	All user and event data should be kept in a database.
	At no time should the main page need to be reloaded.
User registration, user authentication, event addition, and event deletion should all be handled by JavaScript and AJAX requests to my server.
API
AWS will be used as well as MySQL.

