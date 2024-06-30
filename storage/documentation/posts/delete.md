# Save posts

This endpoint is used to remove post by user, using route

```http
POST /api/posts/delete
```

| Parameter   | Type     | Description                   |
|:------------|:---------|:------------------------------|
| `api_token` | `string` | **Required**. User auth token |
| `post_id`   | `int`    | **Required**. Id of post      |

## Status Codes

API returns the following status codes in its API:

| Status Code | Description                                           |
|:------------|:------------------------------------------------------|
| 0000        | **OK**                                                |
| 2011        | **Error**: Global error.                              |
| 2012        | **Error**: You have no privilege to delete this post! |

## Example of success response

```json
{
    "code": "0000",
    "message": "Successfully deleted"
}
```
