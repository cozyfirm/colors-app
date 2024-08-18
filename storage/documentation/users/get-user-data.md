# Get user data

This endpoint is used to fetch user data, using route

```http
POST /api/users/get-data
```

| Parameter   | Type     | Description                                            |
|:------------|:---------|:-------------------------------------------------------|
| `api_token` | `string` | **Required**. User auth token                          |


## Status Codes

API returns the following status codes in its API:

| Status Code | Description                        |
|:------------|:-----------------------------------|
| 0000        | **OK**                             |
| 2001        | **Error**: Global error.           |

## Example of success response

For input data given as:

| Key         | value       |
|:------------|:------------|
| `api_token` | SHA256-HASH |

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
