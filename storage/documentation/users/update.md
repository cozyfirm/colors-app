# Update user data

This endpoint is used to update user data, using route

```http
POST /api/users/update
```

| Parameter    | Type     | Description                                  |
|:-------------|:---------|:---------------------------------------------|
| `api_token`  | `string` | **Required**. User auth token                |
| `username`   | `string` | **Required**. Username (100 chars length)    |
| `birth_date` | `string` | **Required**. Birth date (format dd.mm.YYYY) |
| `city`       | `string` | **Required**. City (100 chars length)        |


## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                    |
|:------------|:-----------------------------------------------|
| 0000        | **OK**                                         |
| 2001        | **Error**: Global error.                       |
| 2012        | **Error**: Please, enter your username         |
| 2013        | **Error**: This username has already been used |

## Example of success response

For input data given as:

| Key           | value       |
|:--------------|:------------|
| `api_token`   | SHA256-HASH |
| `username`    | aladeenkapic |
| `city`        | Sarajevo |
| `birth_date`  | 03.05.1994 |

```json
{
    "code": "0000",
    "message": "User data",
    "data": {
        "username": "aladeenkapic",
        "email": "kaapiic@gmail.com",
        "birth_date": "1994-05-03",
        "birth_date_f": "03.05.1994",
        "city": "Sarajevo",
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
