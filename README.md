# j-cf

System has 3 components: API endpoint, message processor and dashboard

NOTE: In this test task HTTP protocol had to be used because certain web browsers such as Firefox do not allow HTTPS protocol to be used with Socket.IO when self-signed certificates are involved which is why I had to switch to HTTP. Of course, in real world systems all communication (between user - API endpoint - message processor -  dashboard) would go through HTTPS protocol.

API endpoint (to POST messages): http://146.185.169.94/currencyfair/endpoint/messages
Dashboard: http://146.185.169.94/currencyfair/dashboard/

#### System operation (an overview)

 1. API endpoint: receives a message which gets validated and stored in the database, and then proxied to the message processor
 2. Message processor: receives a message from the API endpoint, pushes its ID to the notification system, generates the report and finally notifies the dashboard every second (only if there are new messages) that is should refresh the data.
 3. Dashboard: receives message IDs and requests message data from the API endpoint, requests data for the report, and then refreshes the dashboard.

#### API endpoint
Endpoint is made based on REST principle. Each API call is verifed for authorization (using client ID and access token which gets generated by our dashboard system. When message is validated, it is being written to the database. Then we proxy the message to the message processor to generate some (for this task "dummy") report that will show number of messages per country per day.

NOTE: The only API call not protected is the one which will be used to POST messages to the system. I wasn't sure how exactly you're going to test the system but this was done to make the reviewal process easier for the reviewer.

Technologies used: 

 1. PHP (Zend Framework)
 2. MySQL (to store messages)
 3. Socket.io (to communicate with the message processor system)
 4. Redis (for authentication)


#### Message processor
When processor receives a message, new Gearman worker is created and new background job is submitted which notifies the dashboard that there are new messages received (realtime reporting), and which generates some dummy report (dummy just to showcase how processor would operate in the whole system) that we can also see in the dashboard in realtime.

NOTE: In order to prevent clogging the communication channels due to possible high load, notifications are sent to the dashboard each second. This allows sending batched message IDs to the dashboard. As a result the dashboard doesn't need to make separate API calls for each message, this allows to fetch few messages in one API call.

Technologies used:

 1. NodeJS
 2. MySQL (to store generated reports)
 2. Socket.io (to communicate with the API endpoint and the dashboard)
 3. Gearman (to process messages as background jobs)


#### Dashboard
Dashboard allows realtime overview as messages are being added to the system. It requires admin privileges in order to access web application. When user logs into the system, an access token is generated for them which will be used to make API calls to the API endpoint.

NOTE: The is a test data generator which when enabled will generate dummy data and post it to the endpoint so we can see how realtime system actually works. If you have you're own test data generator then I guess you won't be needing this one.

Technologies used:

 1. PHP (Zend Framework)
 2. JQuery (to make simple web application and to communicate with the API endpoint)
 3. Highcharts (library for maps and charts)
 4. Socket.io (to communicate with the processor)
 5. Redis (to store access token for the API endpoint)
