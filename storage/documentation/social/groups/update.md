# Save teams

This endpoint is used to update groups (only text fields; Image is updated from another route), using route

```http
POST /api/groups/update
```

| Parameter     | Type     | Description                         |
|:--------------|:---------|:------------------------------------|
| `api_token`   | `string` | **Required**. User auth token       |
| `hash`        | `file`   | **Required**. Group hash            |
| `name`        | `string` | **Required**. Name of the group     |
| `public`      | `bool`   | **Required**. 1. Public, 0. Private |
| `description` | `text`   | **Required**. Group description     |


## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                                        |
|:------------|:-------------------------------------------------------------------|
| 0000        | **OK**                                                             |
| 3001        | **Error**: Global error.                                           |
| 3005        | **Error**: Group name too large! Maximum size is 100 chars!        |
| 3006        | **Error**: Group description too large! Maximum size is 300 chars! |

## Example of success response

Note: Group image and path should be defined as

```http
GET /files/social/groups/$_FILE_PARAM
```

For input data given as:

| Key           | value                                             |
|:--------------|:--------------------------------------------------|
| `api_token`   | SHA256-HASH                                       |
| `name`        | `FK Sarajevo BiH`                                 |
| `public`      | 1                                                 |
| `description` | `Grupa navijača FK Sarajevo Bosna i Hercegovina!` |

```json
{
    "code": "0000",
    "message": "Success",
    "data": {
        "group": {
            "id": 11,
            "hash": "$2y$12$T5.F99pFbmPbrHFAMYnBzOiVB.MlyLQj4meZ8ultqmoRQoemqDtEK",
            "file_id": 35,
            "name": "FK Sarajevo BiH",
            "public": "1",
            "description": "Grupa navijača FK Sarajevo Bosna i Hercegovina!",
            "reactions": 0,
            "members": 1,
            "created_at": "2024-08-18T16:35:48.000000Z",
            "updated_at": "2024-08-18T16:58:54.000000Z",
            "deleted_at": null,
            "admins_rel": [
                {
                    "id": 5,
                    "group_id": 11,
                    "user_id": 1,
                    "role": "admin",
                    "note": "owner",
                    "status": "accepted",
                    "created_at": "2024-08-18T16:35:48.000000Z",
                    "updated_at": "2024-08-18T16:35:48.000000Z",
                    "deleted_at": null
                }
            ]
        }
    }
}
```
