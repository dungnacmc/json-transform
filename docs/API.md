**Title**
----
Transform JSON to unified JSON object

* **URL**

  api/v1/json/<json_string>

* **Method:**
  `GET` 


## Tables

|Attribute | Type  | Required  | Description  |
|---|---|---|---|
| json_string  |string | Yes  |  Standard JSON string |


* **Success Response:**

    * **Code:** 200 <br />
      **Content:**
      ```json
       [{"id": 10,
          "title": "House",
          "level": 0,
          "children":
          [{"id": 12,
          "title": "Red Roof",
          "level": 1,
          "children":
          [{"id": 17,
          "title": "Blue Window",
          "level": 2,
          "children": [],
          "parent_id": 12},
          {"id": 15,
          "title": "Red Window",
          "level": 2,
          "children": [],
          "parent_id": 12}],
          "parent_id": 10},
          {"id": 18,
          "title": "Blue Roof",
          "level": 1,
          "children": [],
          "parent_id": 10},
          {"id": 13,
          "title": "Wall",
          "level": 1,
          "children":
          [{"id": 16,
          "title": "Door",
          "level": 2,
          "children": [],
          "parent_id": 13}],
          "parent_id": 10}],
      "parent_id": null}]
      ```
* **Error Response:**

    * **Code:** 404 NOT FOUND <br />
      **Content:** 
        ```json
           {"success": false, "message": "Not Found"}
        ```
    * **Code:** 405 METHOD NOT ALLOWED <br />
    **Content:**
      ```json
         {"success": false, "message": "Method Not Allowed"}
      ```  
    * **Code:** 500 INTERNAL SERVER ERROR <br />
      **Content:**
        ```json
          {"success": false, "message": "Internal server error"}
        ```
    * **Code:** 422 UNPROCESSABLE ENTITY <br />
    **Content:**
      ```json
        {"success": false, "message": "Wrong JSON string"}
      ```
