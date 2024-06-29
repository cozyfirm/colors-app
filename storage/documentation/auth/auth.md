# Authentication

This endpoint is used for authentication of users, using route

```http
POST /api/auth
```

| Parameter  | Type | Description                    |
|:-----------| :--- |:-------------------------------|
| `email`    | `string` | **Required**. User email   |
| `password` | `string` | **Required**. User password|


## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                    |
|:------------|:-----------------------------------------------|
| 0000        | **OK**                                         |
| 1101        | **Error**: Global error.                       |
| 1102        | **Error**: Please, enter your email            |
| 1103        | **Error**: Please, enter your password         |
| 1104        | **Error**: Unknown email                       |
| 1105        | **Error**: You have entered wrong password     |


## Example of success response

```json
{
    "code": "0000",
    "message": "Success",
    "data": {
        "username": "aladeenkapic",
        "name": "The Aladin",
        "email": "kaapiic@gmail.com",
        "api_token": "d13b2043e8b1726d0de5848f29017c7c9522dfa1764ad0e8e9b5085adbfb4165"
    }
}
```
