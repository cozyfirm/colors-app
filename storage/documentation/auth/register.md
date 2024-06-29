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


## Example of success response

```json
{
    "code": "0000",
    "message": "Your account has been created",
    "data": {
        "id": 1,
        "username": "john-doe",
        "email": "john@doe.com",
        "api_token": "d13b2043e8b1726d0de5848f29017c7c9522dfa1764ad0e8e9b5085adbfb4165"
    }
}
```
