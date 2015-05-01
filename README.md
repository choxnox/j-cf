# j-cf

System has 3 components: API endpoint, message processor and dashboard

#### API endpoint
Endpoint is made based on REST principle. Each API call is verifed for authorization (using client ID and access token which gets generated by our dashboard system. When message is validated, it is being written to the database. Then we proxy the message to the message processor to generate some (for this task "dummy") report that will show number of messages per country per day.

NOTE: The only API call not protected is the one which will be used to POST messages to the system. I wasn't sure how exactly you're going to test the system but this was done to make the reviewal process easier for the reviewer.

Technologies used: 

 1. PHP (Zend Framework)
 2. MySQL (to store messages)
 3. Socket.io (to communicate with the message processor system)
 4. Redis (for authentication)


