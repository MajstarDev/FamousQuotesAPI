# Quotes API

Simple REST API to CRUD famous quotes. API responds in JSON format.
Other than /key request, you *must* send "X-APIKEY" header with a valid API key to do anything.
All requests return status = OK|Error upon completion. In case of error, error message will be returned (in 'message').

The following operations are supported:

## GET /key

Generates and returns new API key. This request is available to public (anybody can receive a new API and manage their collection of quotes).

No input params.

Example output:

```
{"status":"OK", "key":"269098bd10bb6d2123955e1d95d6073cd93a2b85"}
```

## GET /quote

Returns list of all quotes for current user (identified by X-APIKEY header).

Example output:
```
{
    "status": "OK",
    "data": [
        {
            "id": 10,
            "name": "Andy Fletcher",
            "text": "We want to avoid cross discussion between candidates during bootcamp where possible"
        },
        {
            "id": 11,
            "name": "Andy Fletcher",
            "text": "I don't want anyone to feel obliged to hang out here thinking I'll hold it against you if you don't"
        },
        {
            "id": 12,
            "name": "Andy Fletcher",
            "text": "None of it is about knowing some weird intricacy of Doctrine's hydration system or shit like that"
        }
    ]
}
```

## POST /quote

Creates new quote.

Input params:
* author	(string, required)
* text 		(string, required)

Example error output #1:
```
{"status":"Error","message":"Username could not be found."}
```

Example error output #2:
```
{"status": "Error", "message": "Either author or quote text is blank"}
```

Example OK reponse:

```
{"status":"OK","message":"Quote Added Successfully"}
```

## POST /quote/{id}

Update quote identified by {id} where `id` is as seen in GET /quote output. If `id` doesn't below to currently authenticated user, you'll get not found error.

Input params:
* author	(string, required)
* text 		(string, required)

Example error output:

```
{"status":"Error","message":"Quote not found"}
```

Example OK reponse:

```
{"status":"OK","message":"Quote updated"}
```

## DELETE /quote/{id}

Delete quote identified by {id} where `id` can be found in GET /quote output.

Example error output:

```
{"status":"Error","message":"Quote not found"}
```

Example OK reponse:

```
{"status": "OK", "message": "Quote removed"}
```

## Live API URL

API has been deployed to http://pavel.bootcamp.architechlabs.com:8000 so you can use [Postman]https://www.getpostman.com/downloads/ to play with it.

## Built With

* Symfony 4.2.7
* FOSRestBundle
* Doctrine
* MySQL 5.7

## Comments

* I was tempted to build this on API Platform, but they call it 'framework' so I decided it's a no-go since framework must be Symfony per requirements, not another framework on top of Symfony
* There are two tradeoffs in the code: (a) user input validation should have been done with Symfony's form validator (b) error messages should be moved to a language file/class. Everything else seems be done nicely (this is my 2nd Symfony app ever, the first was your "basket" assignment).

## Authors

* **Pavel Kolas** - pk@majstar.com
