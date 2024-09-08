# Live check

- Email
- Username
- Password

## Check email

Check if email is available to use, using route

```http
POST /api/auth/check/email
```

| Parameter  | Type     | Description             |
|:-----------|:---------|:------------------------|
| `email`    | `string` | **Required** User email |


## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                 |
|:------------|:--------------------------------------------|
| 0000        | **OK**                                      |
| 1010        | **Error**: Global error.                    |
| 1011        | **Error**: Please, enter your email         |
| 1012        | **Error**: Email too long                   |
| 1013        | **Error**: Email invalid                    |
| 1014        | **Error**: This email has already been used |

## Example of success response

```json
{
    "code": "0000",
    "message": "Email is available to use"
}
```

## Check username

Check if email is available to use, using route

```http
POST /api/auth/check/username
```

| Parameter  | Type     | Description           |
|:-----------|:---------|:----------------------|
| `username` | `string` | **Required** Username |

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                    |
|:------------|:-----------------------------------------------|
| 0000        | **OK**                                         |
| 1014        | **Error**: Global error.                       |
| 1015        | **Error**: Please, enter your username         |
| 1016        | **Error**: Username too long                   |
| 1017        | **Error**: This username has already been used |

## Example of success response

```json
{
    "code": "0000",
    "message": "Username is available to use"
}
```

## Check password

Check if email is available to use, using route

```http
POST /api/auth/check/password
```

| Parameter  | Type      | Description                |
|:-----------|:----------|:---------------------------|
| `password` | `string`  | **Required** User password |

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                              |
|:------------|:-----------------------------------------|
| 0000        | **OK**                                   |
| 1018        | **Error**: Global error.                 |
| 1019        | **Error**: Please, enter your password   |
| 1020        | **Error**: Global error (password check) |
| 1021        | **Error**: Password not valid error      |

## Example of success response

```json
{
    "code": "0000",
    "message": "Good password"
}
```
