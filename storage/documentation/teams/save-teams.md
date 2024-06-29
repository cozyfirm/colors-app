# Save teams

This endpoint is used to save selected teams, using route

```http
POST /api/teams/save-teams
```

| Parameter       | Type     | Description                          |
|:----------------|:---------|:-------------------------------------|
| `api_token`     | `string` | **Required**. User auth token        |
| `team`          | `int`    | **Required**. Selected team          |
| `national_team` | `int`    | **Required**. Selected national team |


## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                |
|:------------|:-------------------------------------------|
| 0000        | **OK**                                     |
| 1321        | **Error**: Global error.                   |
| 1322        | **Error**: Error: Team not found!          |
| 1323        | **Error**: Error: National team not found! |

## Example of success response

Note: Flag for team is given also as `flag`, but path should be defined as

```http
GET /files/core/clubs/$_FLAG_PARAM
```

For input data given as:

| Key             | value        |
|:----------------|:-------------|
| `api_token`     | SHA256-HASH  |
| `team`          | 36           |
| `national_team` | 412          |

```json
{
    "code": "0000",
    "message": "Teams saved!",
    "data": {
        "team": {
            "id": 36,
            "name": "FK MLADOST",
            "flag": "GRBOVI\\BiH\\Prva Liga FBiH\\png\\FK Mladost Doboj Kakanj.png",
            "gender": "M"
        },
        "national_team": {
            "id": 412,
            "name": "Bosna i Hercegovina",
            "flag": "bih.png",
            "gender": "M"
        }
    }
}
```
