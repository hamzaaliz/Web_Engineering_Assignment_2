Overview

This web spider is a program designed to systematically browse the internet, following hyperlinks, and collecting information from web pages. The fundamental implementation follows the principles of search engines. The spider is implemented in PHP, leveraging the DOMDocument class for HTML parsing.

Technologies and Concepts Used
* PHP: The server-side scripting language used for implementing the web spider.
* DOMDocument class: Used for parsing HTML content and extracting relevant information from crawled pages.
* HTTP Requests: Implemented using the file_get_contents function to fetch HTML content from URLs.
* robots.txt Compliance: The spider checks and respects the rules specified in the robots.txt file of the website being crawled.
* Error Handling: Implemented to manage situations where a page cannot be fetched, parsed, or other issues occur during crawling.

Setup and Execution
* Run the Spider:
    * Open index.html in a web browser.
    * Enter the seed URL and choose the task (View Crawled Content or Find Text).
    * If selecting "Find Text," enter the text to search.
* View Results:
    * If the task is "View Crawled Content," the spider will display the title and meta information of crawled pages.
    * If the task is "Find Text," the spider will echo URLs where the specified text is found.
    
Notes and Considerations
* Ensure that the PHP environment is set up on your machine.
* Respect ethical guidelines and avoid overloading servers with excessive requests.
* Modify the code as needed for more advanced scenarios or additional features.
