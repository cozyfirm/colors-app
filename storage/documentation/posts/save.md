# Save posts

This endpoint is used to save post by user, using route

```http
POST /api/posts/save
```

| Parameter   | Type     | Description                                  |
|:------------|:---------|:---------------------------------------------|
| `api_token` | `string` | **Required**. User auth token                |
| `content`   | `string` | **Required**. Content of post                |
| `file`      | `file`   | **Optional**. Image (png, jpg, jpeg for now) |
| `public`    | `int`    | **Required**. 1 - Public, 0 - Private        |

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                |
|:------------|:-------------------------------------------|
| 0000        | **OK**                                     |
| 2001        | **Error**: Global error.                   |

## Example of success response

Note: Absolute path of image can be found at:

```http
GET /$_POST_FILE_REL_PATH/$_POST_FILE_REL_NAME
```

For input data given as:

| Key         | value                        |
|:------------|:-----------------------------|
| `api_token` | SHA256-HASH                  |
| `content`   | "My first post"              |
| `file`      | "Image object with key file" |
| `public`    | 1                            |

```json
{
    "code": "0000",
    "message": "Teams saved!",
    "data": {
        "post": {
            "user_id": 5,
            "content": "My first post",
            "file_id": 12,
            "public": "1",
            "updated_at": "2024-06-30T10:57:29.000000Z",
            "created_at": "2024-06-30T10:57:29.000000Z",
            "id": 9,
            "file_rel": {
                "file": "colors.png",
                "name": "a5fd2be6e341bc4741d9b6280b3a7fc2.png",
                "ext": "png",
                "type": "img",
                "path": "files/posts",
                "updated_at": "2024-06-30T10:57:29.000000Z",
                "created_at": "2024-06-30T10:57:29.000000Z",
                "id": 12
            }
        }
    }
}
```
