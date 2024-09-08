## Privacy policy

This API is used to fetch privacy policy from main server
```http
POST /api/open-api/config/info/privacy-policy
```

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                  |
|:------------|:---------------------------------------------|
| 0000        | **OK**                                       |
| 8120        | **Error**: Global error.                     |

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
