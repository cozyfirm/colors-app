# Registration

This endpoint is used for registration of new users, using route

```http
POST /api/auth/register
```

| Parameter  | Type | Description                               |
|:-----------| :--- |:------------------------------------------|
| `username` | `string` | **Required**. User username (unique)      |
| `email`    | `string` | **Required**. User email (unique)         |
| `password` | `string` | **Required**. User password (min 8 chars) |

## Responses

API endpoints return the JSON representation of the resource created. If code '0000' is returned, it represents "success", otherwise, you should check for other messages or errors.

```javascript
{
  "code" : string,
  "message" : bool,
  "data"    : object
}
```

The `code` attribute contains a code commonly used to indicate errors or success.

The `message` attribute gives optional message.

The `data` attribute contains any other metadata associated with the response. This will be an escaped string containing JSON data.

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                    |
|:------------|:-----------------------------------------------|
| 0000        | **OK**                                         |
| 1001        | **Error**: Global error.                       |
| 1002        | **Error**: Please, enter your email            |
| 1003        | **Error**: Please, enter your password         |
| 1004        | **Error**: Please, enter your username         |
| 1005        | **Error**: This email has already been used    |
| 1006        | **Error**: This username has already been used | 
| 1007        | **Error**: Password not valid error            |
| 1008        | **Error**: Password check: Global error.       |
