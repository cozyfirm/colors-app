# Save teams

This endpoint is used to fetch all groups (in order), using route

```http
POST /api/groups/fetch/all
```

| Parameter     | Type     | Description                                           |
|:--------------|:---------|:------------------------------------------------------|
| `api_token`   | `string` | **Required**. User auth token                         |
| `number`      | `int`    | **Required**. Number of results per page (default 10) |
| `page`        | `int`    | **Required**. Page number (default 1)                 |


## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                                        |
|:------------|:-------------------------------------------------------------------|
| 0000        | **OK**                                                             |
| 3001        | **Error**: Global error.                                           |

## Example of success response

Note: Group image and path should be defined as

```http
GET /files/social/groups/$_FILE_PARAM
```

For input data given as:

| Key         | value                                             |
|:------------|:--------------------------------------------------|
| `api_token` | SHA256-HASH                                       |
| `number`    | 1                                                 |
| `page`      | 1                                                 |

```json
{
    "code": "0000",
    "message": "Success",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 11,
                "hash": "$2y$12$T5.F99pFbmPbrHFAMYnBzOiVB.MlyLQj4meZ8ultqmoRQoemqDtEK",
                "file_id": 35,
                "name": "FK Sarajevo BiH",
                "public": 1,
                "description": "Grupa navijača FK Sarajevo Bosna i Hercegovina!",
                "reactions": 0,
                "members": 1,
                "created_at": "2024-08-18T16:35:48.000000Z",
                "updated_at": "2024-08-18T16:58:54.000000Z",
                "deleted_at": null,
                "file_rel": {
                    "id": 35,
                    "file": "icon.png",
                    "name": "2d0596a35e682cc0371be3a28f4a2abe.png",
                    "ext": "png",
                    "path": "files/social/groups"
                }
            }
        ],
        "first_page_url": "http://127.0.0.1:8000/api/groups/fetch/all?page=1",
        "from": 1,
        "last_page": 5,
        "last_page_url": "http://127.0.0.1:8000/api/groups/fetch/all?page=5",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/groups/fetch/all?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": "http://127.0.0.1:8000/api/groups/fetch/all?page=2",
                "label": "2",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/groups/fetch/all?page=3",
                "label": "3",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/groups/fetch/all?page=4",
                "label": "4",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/groups/fetch/all?page=5",
                "label": "5",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/groups/fetch/all?page=2",
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": "http://127.0.0.1:8000/api/groups/fetch/all?page=2",
        "path": "http://127.0.0.1:8000/api/groups/fetch/all",
        "per_page": 1,
        "prev_page_url": null,
        "to": 1,
        "total": 5
    }
}
```
