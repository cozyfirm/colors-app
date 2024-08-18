# Update profile settings

This endpoint is used to update profile settings, using route

```http
POST /api/users/profile-settings
```

| Parameter   | Type     | Description                                            |
|:------------|:---------|:-------------------------------------------------------|
| `api_token` | `string` | **Required**. User auth token                          |
| `key`       | `string` | **Required**. Selected key: s_not or s_loc or s_b_date |
| `value`     | `int`    | **Required**. 0 or 1                                   |


List of keys:

| Key      | Description        |
|:---------|:-------------------|
| s_not    | Show notifications |
| s_loc    | Show location      |
| s_b_date | Show birth date    |

List of values:

| Value | Description     |
|:------|:----------------|
| 0     | Disabled        |
| 1     | Enabled         |

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                        |
|:------------|:-----------------------------------|
| 0000        | **OK**                             |
| 2031        | **Error**: Global error.           |
| 2032        | **Error**: Error: Unknown key      |
| 2033        | **Error**: Error: Value not valid  |

## Example of success response

For input data given as:

| Key         | value       |
|:------------|:------------|
| `api_token` | SHA256-HASH |
| `key`       | s_b_date    |
| `value`     | 1           |

```json
{
    "code": "0000",
    "message": "User data",
    "data": {
        "username": "aladeenkapic",
        "email": "kaapiic@gmail.com",
        "teams": {
            "status": true,
            "team": 36,
            "national_team": 412
        },
        "s_not": 0,
        "s_loc": 0,
        "s_b_date": 0
    }
}
```
