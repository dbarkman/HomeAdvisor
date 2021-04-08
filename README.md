# HomeAdvisor Project Documentation

Included in this repo are the following:
* HomeAdvisorAPI: code responsible for handling incoming API calls and returning the response produced by the framework.
* HomeAdvisorFramework: code responsible for creating, managing and deleting HomeAdvisor objects and data.
* HomeAdvisor.sql: SQL for creating the companion database and user for storing the HomeAdvisor data.
* HomeAdvisor.postman_collection.json: the test suite for testing the HomeAdvisor project with PostMan.
	* Note: the test suite currently points to the hosted version of this project.

## Local Install

This project has been tested on a LAMP stack with the following versions:
* CentOS 8.1
* Apache 2.4
* MariaDB 10.3
* PHP 7.2

In order to run this project locally, perform the following:
1. Clone the project to the system and directory preferred.
2. Update the permissions of the cloned project to be readable by the web server user.
3. Point the web server's document room to the HomeAdvisorAPI folder.
4. Run the SQL script to create the database, tables and user used by the code.
* If the password is changed from what's in the SQL script, please also change the password in HomeAdvisorFramework/config/credentials.php.

Test the Install
1. Point a browser or Postman to http://localhost/testPass and the following block will return if the API is set up correctly:
```javascript
{
  "httpStatus": 200,
  "noun": "testPass",
  "verb": "GET",
  "errorCode": "none",
  "errors": [],
  "friendlyError": "",
  "result": "success",
  "count": 0,
  "data": []
}
```

Troubleshooting
* Adjust the URL to match where the HomeAdvisorAPI folder was installed in relation to the web server's document root.
* Check HomeAdvisorFramework/log/HomeAdvisor.log for any errors
* Check the local web server logs for errors
* Check the local php log for errors

## Operation

### Test the API: GET /testPass
Parameters: 
* none

### Create a Business: POST /business

Note: businesses can be sent as a single business as an object or in an array or as multiple businesses in an array.

Parameters
* none

Sample call: /business
* body:
```javascript
[
    {
        "businessName": "Cherry Creek Contracting Inc",
        "businessHours": [{
            "dayOfWeek": "Monday",
            "open": "8",
            "close": "5"
        },{
            "dayOfWeek": "Tuesday",
            "open": "8",
            "close": "5"
        },{
            "dayOfWeek": "Wednesday",
            "open": "8",
            "close": "4"
        },{
            "dayOfWeek": "Thursday",
            "open": "8",
            "close": "6"
        },{
            "dayOfWeek": "Friday",
            "open": "8",
            "close": "4"
        }],
        "businessAddress": {
            "addressLine1": "2066 Auer Island",
            "addressLine2": "Suite 911",
            "city": "Denver",
            "stateAbbr": "CO",
            "postal": "80205"
        },
        "operatingCities": ["Denver", "Cherry Creek", "Phoenix", "Mesa"],
        "workTypes": ["Packing", "Maid Service", "House Cleaning", "Moving Services"],
        "reviews": [{
            "ratingScore": "5",
            "customerComment": "Use them weekly to clean our home. Do a great job every time."
        },{
            "ratingScore": "4",
            "customerComment": "Helped us move homes, very timely."
        },{
            "ratingScore": "5",
            "customerComment": "On time, did a good job."
        }]
    }
]
```

Sample result:
```javascript
{
    "httpStatus": 201,
    "noun": "business",
    "verb": "POST",
    "errorCode": "none",
    "errors": [],
    "friendlyError": "",
    "result": "success",
    "count": 1,
    "data": [
        {
            "id": "43",
            "businessName": "Cherry Creek Contracting Inc",
            "businessHours": [
                {
                    "dayOfWeek": "Monday",
                    "open": "8",
                    "close": "5"
                },
                {
                    "dayOfWeek": "Tuesday",
                    "open": "8",
                    "close": "5"
                },
                {
                    "dayOfWeek": "Wednesday",
                    "open": "8",
                    "close": "4"
                },
                {
                    "dayOfWeek": "Thursday",
                    "open": "8",
                    "close": "6"
                },
                {
                    "dayOfWeek": "Friday",
                    "open": "8",
                    "close": "4"
                }
            ],
            "businessAddress": {
                "addressLine1": "2066 Auer Island",
                "addressLine2": "Suite 911",
                "city": "Denver",
                "stateAbbr": "CO",
                "postal": "80205"
            },
            "operatingCities": [
                "Denver",
                "Phoenix",
                "Mesa",
                "Cherry Creek"
            ],
            "workTypes": [
                "Packing",
                "Maid Service",
                "House Cleaning",
                "Moving Services"
            ],
            "reviews": [
                {
                    "ratingScore": "5",
                    "customerComment": "Use them weekly to clean our home. Do a great job every time."
                },
                {
                    "ratingScore": "4",
                    "customerComment": "Helped us move homes, very timely."
                },
                {
                    "ratingScore": "5",
                    "customerComment": "On time, did a good job."
                }
            ]
        }
    ]
}
```

### Retrieve Businesses: GET /business

Parameters for filtering results:
* businessId: integer
	* the ID of a business
* OR
* bussinessName: string
	* the name or partial name of a business
	* if both businessId and businessName are included, businessId will override businessName
* AND
* workType: string
	* the type of work performed by the business
* operatingCity: string
	* the city where a business operates
* openOn: string
	* a specific day of the week when a business is open
	* accepted values: [Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday]
* openAt: integer
	* a time, in 24 hour value, a business is open
	* accepted values: 0-23
* averageRatingScore: integer
	* a minimum score that a business's average of all scores must meet or exceed
	* accepted values: 1-5

Parameters for sorting results:
* order: string
	* parameter to order results by
	* accepted values: [businessName, averageRatingScore]
* direction: string
	* direction to list the ordered results by
	* accepted values: [asc, desc]

Sample call:
* GET /business?businessName=Contracting&operatingCity=Denver&workType=Packing&averageRatingScore=4&openOn=Monday&openAt=12&order=businessName&direction=desc

Sample result:
```javascript
{
    "httpStatus": 200,
    "noun": "business",
    "verb": "GET",
    "errorCode": "none",
    "errors": [],
    "friendlyError": "",
    "result": "success",
    "count": 1,
    "data": [
        {
            "businessName": "Cherry Creek Contracting Inc",
            "businessHours": [
                {
                    "dayOfWeek": "Monday",
                    "open": "8",
                    "close": "5"
                },
                {
                    "dayOfWeek": "Tuesday",
                    "open": "8",
                    "close": "5"
                },
                {
                    "dayOfWeek": "Wednesday",
                    "open": "8",
                    "close": "4"
                },
                {
                    "dayOfWeek": "Thursday",
                    "open": "8",
                    "close": "6"
                },
                {
                    "dayOfWeek": "Friday",
                    "open": "8",
                    "close": "4"
                },
            ],
            "businessAddress": {
                "addressLine1": "2066 Auer Island",
                "addressLine2": "Suite 911",
                "city": "Denver",
                "stateAbbr": "CO",
                "postal": "80205"
            },
            "operatingCities": [
                "Denver",
		 "Cherry Creek",
                "Phoenix",
                "Mesa"
            ],
            "workTypes": [
                "Packing",
                "Maid Service", 
		 "House Cleaning", 
		 "Moving Services"
            ],
            "reviews": [
                {
                    "ratingScore": "5",
                    "customerComment": "Use them weekly to clean our home. Do a great job every time."
                },
                {
                    "ratingScore": "4",
                    "customerComment": "Helped us move homes, very timely."
                },
                {
                    "ratingScore": "5",
                    "customerComment": "On time, did a good job."
                }
            ]
        }
    ]
}
```

### Delete a Business: DELETE /business

Parameters:
* businessId: integer

Sample call:
* DELETE /business?businessId=10

Sample result:
```javascript
{
    "httpStatus": 200,
    "noun": "business",
    "verb": "DELETE",
    "errorCode": "none",
    "errors": [],
    "friendlyError": "",
    "result": "success",
    "count": 1,
    "data": []
}
```
