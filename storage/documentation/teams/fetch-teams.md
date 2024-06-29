# Fetch all teams

This endpoint is used fetching all teams, using route

```http
POST /api/teams/fetch-teams
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
| 1311        | **Error**: Global error.                       |


## Example of success response

Note: API returns arrays of leagues, with seasons of relationship of array of teams
Note: Flag for team is given also as `flag`, but path should be defined as

```http
GET /files/core/clubs/$_FLAG_PARAM
```

Note: Logo for leagues (if needed) is given also as `flag`, but path should be defined as

```http
GET /files/core/leagues/$_FLAG_PARAM
```

For input data given as:

| Key         | value       |
|:------------|:------------|
| `api_token` | SHA256-HASH |
| `team`      | `mlad`      |

```json
{
    "code": "0000",
    "message": "Success",
    "data": [
        {
            "id": 3,
            "name": "Prva liga FBiH",
            "logo": "not-available.png",
            "season_rel": {
                "id": 6,
                "start_y": 2024,
                "end_y": 2025,
                "season": "2024 / 2025",
                "league_id": 3,
                "locked": 0,
                "created_at": "2024-06-29T19:43:19.000000Z",
                "updated_at": "2024-06-29T19:43:19.000000Z",
                "deleted_at": null,
                "team_rel": [
                    {
                        "season_id": 6,
                        "team_id": 36,
                        "created_by": 1,
                        "created_at": "2024-06-29T19:43:36.000000Z",
                        "updated_at": "2024-06-29T19:43:36.000000Z",
                        "deleted_at": null,
                        "team_rel": {
                            "id": 36,
                            "name": "FK MLADOST",
                            "flag": "GRBOVI\\BiH\\Prva Liga FBiH\\png\\FK Mladost Doboj Kakanj.png",
                            "gender": "M"
                        }
                    }
                ]
            }
        },
        {
            "id": 5,
            "name": "Druga liga FBiH - Centar",
            "logo": "not-available.png",
            "season_rel": {
                "id": 7,
                "start_y": 2024,
                "end_y": 2025,
                "season": "2024 / 2025",
                "league_id": 5,
                "locked": 0,
                "created_at": "2024-06-29T19:44:19.000000Z",
                "updated_at": "2024-06-29T19:44:19.000000Z",
                "deleted_at": null,
                "team_rel": [
                    {
                        "season_id": 7,
                        "team_id": 320,
                        "created_by": 1,
                        "created_at": "2024-06-29T19:45:11.000000Z",
                        "updated_at": "2024-06-29T19:45:11.000000Z",
                        "deleted_at": null,
                        "team_rel": {
                            "id": 320,
                            "name": "FK MLADOST",
                            "flag": "GRBOVI\\BiH\\2 Kantonalna liga TK Jug\\FK Mladost Gornja Tuzla.png",
                            "gender": "M"
                        }
                    },
                    {
                        "season_id": 7,
                        "team_id": 410,
                        "created_by": 1,
                        "created_at": "2024-06-29T19:45:27.000000Z",
                        "updated_at": "2024-06-29T19:45:27.000000Z",
                        "deleted_at": null,
                        "team_rel": {
                            "id": 410,
                            "name": "FK MLADOST",
                            "flag": "GRBOVI\\BiH\\Podrucna Liga RS Modrica Samac Grupa B\\FK Mladost Srpska Tisina.png",
                            "gender": "M"
                        }
                    }
                ]
            }
        }
    ]
}
```
