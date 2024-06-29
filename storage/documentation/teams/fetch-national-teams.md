# Fetch all national teams

This endpoint is used fetching all national teams, using route

```http
POST /api/teams/fetch-national-teams
```

| Parameter   | Type | Description                        |
|:------------| :--- |:-----------------------------------|
| `api_token` | `string` | **Required**. User auth token  |
| `team`      | `string` | **Required**. Searched team    |


## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                    |
|:------------|:-----------------------------------------------|
| 0000        | **OK**                                         |
| 1301        | **Error**: Global error.                       |


## Example of success response

Note: Country_rel represents country, not national team. Flag is given as uri from api-sports.io
Note: Flag for national team is given also as `flag`, but path should be prepended as

```http
GET /files/core/clubs/$_FLAG_PARAM
```

For input data given as:

| Key         | value       |
|:------------|:------------|
| `api_token` | SHA256-HASH |
| `team`      | `b`         |

```json
{
    "code": "0000",
    "message": "Success",
    "data": [
        {
            "id": 412,
            "name": "Bosna i Hercegovina",
            "flag": "bih.png",
            "country_id": 21,
            "gender": "M",
            "country_rel": {
                "id": 21,
                "name": "Bosnia",
                "name_ba": "Bosna i Hercegovina",
                "flag": "https://media-1.api-sports.io/flags/ba.svg"
            }
        },
        {
            "id": 414,
            "name": "Srbija",
            "flag": "srbija.jpg",
            "country_id": 134,
            "gender": "M",
            "country_rel": {
                "id": 134,
                "name": "Serbia",
                "name_ba": "Srbija",
                "flag": "https://media-2.api-sports.io/flags/rs.svg"
            }
        }
    ]
}
```
