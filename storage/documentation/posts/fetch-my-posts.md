# Save posts

This endpoint is used to fetch my posts (not the friend user, only my), using route

```http
POST /api/posts/fetch-my-posts
```

| Parameter   | Type     | Description                                  |
|:------------|:---------|:---------------------------------------------|
| `api_token` | `string` | **Required**. User auth token                |

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                |
|:------------|:-------------------------------------------|
| 0000        | **OK**                                     |
| 2021        | **Error**: Global error.                   |

## Example of success response

Note: Absolute path of image can be found at:

```http
GET /$_POST_FILE_REL_PATH/$_POST_FILE_REL_NAME
```

For input data given as:

| Key         | value                        |
|:------------|:-----------------------------|
| `api_token` | SHA256-HASH                  |

```json
{
    "code": "0000",
    "message": "Success",
    "data": {
        "posts": [
            {
                "id": 10,
                "user_id": 5,
                "content": "My first post",
                "file_id": 13,
                "public": 1,
                "group_id": null,
                "likes": 0,
                "created_at": "2024-06-30T11:03:59.000000Z",
                "updated_at": "2024-06-30T11:03:59.000000Z",
                "file_rel": {
                    "id": 13,
                    "file": "colors.png",
                    "name": "ec85ced671a48852e709b0a20ceab719.png",
                    "ext": "png",
                    "type": "img",
                    "path": "files/posts",
                    "created_at": "2024-06-30T11:03:59.000000Z",
                    "updated_at": "2024-06-30T11:03:59.000000Z"
                }
            }
        ]
    }
}
```
