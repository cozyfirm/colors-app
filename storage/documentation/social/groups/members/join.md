# Join to public group

This endpoint is used to send request to join private groups, using route

```http
POST /api/groups/membership/join
```

| Parameter   | Type     | Description                   |
|:------------|:---------|:------------------------------|
| `api_token` | `string` | **Required**. User auth token |
| `id`        | `int`    | **Required**. Group id        |


## Status Codes

API returns the following status codes in its API:

| Status Code | Description                             |
|:------------|:----------------------------------------|
| 0000        | **OK**                                  |
| 3051        | **Error**: Global error.                |
| 3065        | **Error**: Error: Unknown group         |
| 3066        | **Error**: You cant join private groups |
| 3067        | **Error**: Already a member             |

## Example of success response

Note: Group image and path should be defined as

```http
GET /files/social/groups/$_FILE_PARAM
```

For input data given as:

| Key           | value       |
|:--------------|:------------|
| `api_token`   | SHA256-HASH |
| `id`          | 13          |

```json
{
    "code": "0000",
    "message": "Successfully joined",
    "data": {
        "group": {
            "id": 15,
            "file_id": 46,
            "name": "Željo for Life",
            "public": 1,
            "description": "Grupa navijača FK Sarajevo Bosna i Hercegovina!",
            "reactions": 0,
            "members": 1,
            "file_rel": {
                "id": 46,
                "file": "images.jpeg",
                "name": "a6ed3a3ad92cf8d386e83d2717e6a313.jpeg",
                "ext": "jpeg",
                "path": "files/social/groups"
            }
        }
    }
}
```
