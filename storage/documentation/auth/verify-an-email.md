## Verify an email

### Post method

This API is used to verify user email, just after registration, using API:

```http
POST /api/auth/verify-an-email
```

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                           |
|:------------|:--------------------------------------|
| 0000        | **OK**                                |
| 1015        | **Error**: Global error.              |
| 1016        | **Error**: Email verification failed. |

## Example of success response

```json
{
    "code": "0000",
    "message": "Email verification successful"
}
```

### GET method

This API is used to verify user email, just after registration, using API:

```http
GET /api/auth/verify-an-email/{username}/{api_token}
```

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                           |
|:------------|:--------------------------------------|
| 0000        | **OK**                                |
| 1017        | **Error**: Global error.              |
| 1018        | **Error**: Email verification failed. |

## Example of success response

```json
{
    "code": "0000",
    "message": "Email verification successful"
}
```
