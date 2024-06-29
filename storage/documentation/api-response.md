# Colors API Endpoints

## General

This document is used for general display of api endpoint system. It is unique, for every error thrown in system. Please, follow documentation.

## Input

In case of data requests, request headers should contain

```http
Content-Type: application/json
```

## Responses

API endpoints return the JSON representation of the resource created. If code '0000' is returned, it represents "success", otherwise, you should check for other messages or errors.

```json
{
"code" : string,
"message" : bool,
"data"    : object
}
```

The `code` attribute (status code) contains a code commonly used to indicate errors or success.

The `message` attribute gives optional message.

The `data` attribute contains any other metadata associated with the response. This will be an escaped string containing JSON data.
