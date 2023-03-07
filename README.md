
# Shrinkker

Shrinkker is a simple URL shortener which is completely available to you as a RESTful API.

To use Shrinkker, a user management with a token-based authentication is available.



## Register Shrinkker Users
To register users, you can proceed as follows:
```
POST https://your-host/api/auth/register HTTP/1.1
content-type: application/json

{
    "name": "Michel Green",
    "email": "michael@green.com",
    "password": "GreNJak23!&%97a"
}

```
When the new user is successfully registered, the following response appears with the new authentification token for Shrinkker:
```
{
  "status": true,
  "message": "Shrinkker User Created Successfully",
  "token": "4|JsO8KXI1uZnzYxGeu4AcI2eLRhhx61GExG9ucHr1"
}
```
## Login Shrinkker Users (Get Token for existing User)
To get a token for an already registered user the following call can be used:
```
POST https://your-host/api/auth/login HTTP/1.1
content-type: application/json

{
    "email": "michael@green.com",
    "password": "GreNJak23!&%97a"
}

```
When the existing user is successfully logged in, the following response appears with the authentification token for Shrinkker:
```
{
  "status": true,
  "message": "Shrinkker User Logged In Successfully",
  "token": "6|AyUKnqe0QlLBNkZHIniUc8DNXakDas7bUaO3e7sT"
}

```
## Shrinkk URL's
To shorten or shrinkk an existing long URL you need a shrinkker user with an authentication token. If you have that you can shorten a URL with the following call:
```
POST https://your-host/api/url/shrinkk/create HTTP/1.1
Content-Type: application/json
Authorization: Bearer 6|AyUKnqe0QlLBNkZHIniUc8DNXakDas7bUaO3e7sT

{
    "url": "https://test-shrinkker-site.com/blog/articles/year/2023?page=4"
}
```
If the long URL was successfully shortened, the following message appears with the new, shortened (shrinkked) URL:


```
{
  "status": true,
  "message": "Given Url shrinkked successfully",
  "data": {
    "original_url": "https://test-shrinkker-site.com/blog/articles/year/2023?page=4",
    "code": "2qp1ZtYb",
    "shrinkked_url": "https://your-host/2qp1ZtYb",
    "creation_time": "2023-03-04T12:12:57.000000Z",
    "hits": null
  }
}
```
Now, with the help of the new, shrinkked URL, the desired destination address can be accessed with any web browser:
```
https://your-host/2qp1ZtYb
```
## List all my shrinkked URL's
With the following call all already created shrinkked URL's can be displayed for the logged in user:
```
#Request
POST https://your-host/api/url/shrinkk/list HTTP/1.1
Content-Type: application/json
Authorization: Bearer 6|AyUKnqe0QlLBNkZHIniUc8DNXakDas7bUaO3e7sT


#Response
{
  "status": true,
  "message": "Shrinkked URLs List",
  "data": {
    "url-list": {
      "2qp1ZtYb": {
        "original_url": "https://test-shrinkker-site.com/blog/articles/year/2023?page=4",
        "code": "2qp1ZtYb",
        "shrinkked_url": "https://your-host/2qp1ZtYb",
        "creation_time": "2023-03-01T09:12:57.000000Z",
        "hits": 75
      },
      "orAYZ3or": {
        "original_url": "https://test-shrinkker-site.com/blog/articles/year/2023?page=5",
        "code": "orAYZ3or",
        "shrinkked_url": "https://your-host/orAYZ3or",
        "creation_time": "2023-03-04T00:14:47.000000Z",
        "hits": 7
      },
      "a9cy0QUd": {
        "original_url": "https://test-shrinkker-site.com/blog/articles/year/2023?page=6",
        "code": "a9cy0QUd",
        "shrinkked_url": "https://your-host/a9cy0QUd",
        "creation_time": "2023-03-05T23:14:52.000000Z",
        "hits": 0
      }
    }
  }
}



```
## Delete my shrinkked URL
To delete a shrinkked URL the following call can be used:
```
#Request
POST http://localhost/api/url/shrinkk/delete/WjSMfL77 HTTP/1.1
Content-Type: application/json
Authorization: Bearer 6|AyUKnqe0QlLBNkZHIniUc8DNXakDas7bUaO3e7sT

#Response
{
  "status": true,
  "message": "Shrinkked URL deleted"
}
```
## Run Locally

The local environment includes Laravel Sail with a MariaDB Database container.

Clone this project

Go to the project directory

Install the following dependencies on your machine

```bash
  docker
  docker compose
```

Start Shrinkker with Sail

```bash
  ./vendor/bin/sail up
```
