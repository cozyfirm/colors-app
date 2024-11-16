# Send request for membership 

This endpoint is used to send request to join private groups, using route

```http
POST /api/groups/membership/send-request
```

| Parameter   | Type     | Description                   |
|:------------|:---------|:------------------------------|
| `api_token` | `string` | **Required**. User auth token |
| `id`        | `int`    | **Required**. Group id        |


## Status Codes

API returns the following status codes in its API:

| Status Code | Description                     |
|:------------|:--------------------------------|
| 0000        | **OK**                          |
| 3051        | **Error**: Global error.        |
| 3055        | **Error**: Error: Unknown group |
| 3056        | **Error**: Request already sent |
| 3057        | **Error**: Already a member     |
| 3058        | **Error**: Request denied       |

## Example of success response

For input data given as:

| Key           | value       |
|:--------------|:------------|
| `api_token`   | SHA256-HASH |
| `id`          | 13          |

```json
{
    "code": "0000",
    "message": "Request successfully sent"
}
```
