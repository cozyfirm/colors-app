# Save teams

This endpoint is used to save groups, using route

```http
POST /api/groups/save
```

| Parameter     | Type     | Description                         |
|:--------------|:---------|:------------------------------------|
| `api_token`   | `string` | **Required**. User auth token       |
| `photo`       | `file`   | **Required**. Chosen file           |
| `name`        | `string` | **Required**. Name of the group     |
| `public`      | `bool`   | **Required**. 1. Public, 0. Private |
| `description` | `text`   | **Required**. Group description     |


## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                                        |
|:------------|:-------------------------------------------------------------------|
| 0000        | **OK**                                                             |
| 3001        | **Error**: Global error.                                           |
| 3002        | **Error**: Error: Please choose a photo                            |
| 3003        | **Error**: Group name too large! Maximum size is 100 chars!        |
| 3004        | **Error**: Group description too large! Maximum size is 300 chars! |

## Example of success response

Note: Group image and path should be defined as

```http
GET /files/social/groups/$_FILE_PARAM
```

For input data given as:

| Key           | value                                             |
|:--------------|:--------------------------------------------------|
| `api_token`   | SHA256-HASH                                       |
| `photo`       | `File**`                                          |
| `name`        | `Željo for life`                                  |
| `public`      | 1                                                 |
| `description` | `Grupa navijača FK Sarajevo Bosna i Hercegovina!` |

```json
{
    "code": "0000",
    "message": "Success",
    "data": {
        "group": {
            "hash": "$2y$12$yzhkKQ2N9MvRncMihjBhjuiX2tMsIKBOgiXtIWZQ0sTf2MmYDU1wC",
            "file_id": 46,
            "name": "Željo for Life",
            "public": "1",
            "description": "Grupa navijača FK Sarajevo Bosna i Hercegovina!",
            "updated_at": "2024-11-16T17:39:08.000000Z",
            "created_at": "2024-11-16T17:39:08.000000Z",
            "id": 15,
            "file_rel": {
                "file": "images.jpeg",
                "name": "a6ed3a3ad92cf8d386e83d2717e6a313.jpeg",
                "ext": "jpeg",
                "type": "img",
                "path": "files/social/groups",
                "updated_at": "2024-11-16T17:39:08.000000Z",
                "created_at": "2024-11-16T17:39:08.000000Z",
                "id": 46
            },
            "owner_rel": {
                "group_id": 15,
                "user_id": 1,
                "role": "admin",
                "note": "owner",
                "status": "accepted",
                "updated_at": "2024-11-16T17:39:08.000000Z",
                "created_at": "2024-11-16T17:39:08.000000Z",
                "id": 10
            }
        }
    }
}
```
