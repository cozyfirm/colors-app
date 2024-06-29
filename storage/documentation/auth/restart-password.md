# Restart password

## Generate PIN

This endpoint is used to generate 4 digits pin, used for email verification, using route

```http
POST /api/auth/restart-password/generate-pin
```

| Parameter  | Type | Description                    |
|:-----------| :--- |:-------------------------------|
| `email`    | `string` | **Required**. User email   |


## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                    |
|:------------|:-----------------------------------------------|
| 0000        | **OK**                                         |
| 1201        | **Error**: Global error.                       |
| 1202        | **Error**: Please, enter your email            |
| 1203        | **Error**: Unknown email                       |

## Example of success response

```json
{
    "code": "0000",
    "message": "Email sent successfully. Follow instructions"
}
```

## Verify the PIN code

This endpoint is used to verify 4 digits pin and email, using route

```http
POST /api/auth/restart-password/verify-pin
```

| Parameter | Type | Description                     |
|:----------| :--- |:--------------------------------|
| `email`   | `string` | **Required**. User email    |
| `pin`     | `string` | **Required**. Generated PIN |

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                         |
|:------------|:------------------------------------|
| 0000        | **OK**                              |
| 1201        | **Error**: Global error.            |
| 1204        | **Error**: Please, enter your email |
| 1205        | **Error**: Please, enter PIN code   |
| 1206        | **Error**: PIN Code incorrect       |

## Example of success response

```json
{
    "code": "0000",
    "message": "Pin code is correct. Proceed to continue"
}
```

## Insert new password

This endpoint is used to generate new password, using route

```http
POST /api/auth/restart-password/new-password
```

| Parameter  | Type | Description                 |
|:-----------| :--- |:----------------------------|
| `email`    | `string` | **Required**. User email    |
| `pin`      | `string` | **Required**. Generated PIN |
| `password` | `string` | **Required**. New password  |

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                             |
|:------------|:----------------------------------------|
| 0000        | **OK**                                  |
| 1201        | **Error**: Global error.                |
| 1207        | **Error**: Please, enter your email     |
| 1208        | **Error**: Please, enter PIN code       |
| 1209        | **Error**: Please, enter your password  |
| 1210        | **Error**: Password not valid error     |
| 1211        | **Error**: PIN Code incorrect           |

## Example of success response

```json
{
    "code": "0000",
    "message": "Password changed successfully",
    "data": {
        "username": "aladeenkapic",
        "name": "The Aladin",
        "email": "kaapiic@gmail.com",
        "api_token": "ccd8e0240fb69b612a882d90b3d23898e3ffe9998661374c04e3f09f355781e8"
    }
}
```
