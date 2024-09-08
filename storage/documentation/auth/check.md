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
| 1020        | **Error**: Global error.                    |
| 1021        | **Error**: Please, enter your email         |
| 1022        | **Error**: Email too long                   |
| 1023        | **Error**: Email invalid                    |
| 1024        | **Error**: This email has already been used |

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
| 1024        | **Error**: Global error.                       |
| 1025        | **Error**: Please, enter your username         |
| 1026        | **Error**: Username too long                   |
| 1027        | **Error**: This username has already been used |

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
| 1028        | **Error**: Global error.                 |
| 1029        | **Error**: Please, enter your password   |
| 1030        | **Error**: Global error (password check) |
| 1031        | **Error**: Password not valid error      |

## Example of success response

```json
{
    "code": "0000",
    "message": "Good password"
}
```
