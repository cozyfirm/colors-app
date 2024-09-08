## Terms and conditions

This API is used to fetch terms and conditions from main server
```http
POST /api/open-api/config/info/terms-and-conditions
```

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                  |
|:------------|:---------------------------------------------|
| 0000        | **OK**                                       |
| 8110        | **Error**: Global error.                     |

## Example of success response

```json
{
    "code": "0000",
    "message": "Success",
    "data": {
        "content": "Lorem Ipsum is simply dummy text of the printing and typesetting industry"
    }
}
```
