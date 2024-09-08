## Splash screen

This API check for splash screens, available for users. If multiple screens present, data is given in random order

```http
POST /api/open-api/config/splash-screen
```

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                  |
|:------------|:---------------------------------------------|
| 0000        | **OK**                                       |
| 8100        | **Error**: Global error.                     |

## Example of success response

**Note**: Absolute path of image can be found at:

```http
GET /$_POST_FILE_REL_PATH/$_POST_FILE_REL_NAME
```

**Note**: There are two available success responses:

1. When default screen should be presented (There is no active splash screen, show default)

```json
{
    "code": "0000",
    "message": "Success",
    "data": {
        "default": true,
        "screen": null
    }
}
```

2. When splash screen should be presented to users (There are one or multiple available splash screens)

```json
{
    "code": "0000",
    "message": "Success",
    "data": {
        "default": false,
        "screen": {
            "id": 1,
            "title": "Coca Cola",
            "file_id": 40,
            "file_rel": {
                "id": 40,
                "name": "d58e5b8f3a65a86a84ddd9689ae3606e.png",
                "ext": "png",
                "type": "img",
                "path": "files/config/splash"
            }
        }
    }
}
```
