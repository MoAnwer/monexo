# Monexo API doc

---

# Global rules:

## How to send resquest ?

after success login or new register actions api with generate access_token like :

```json
{
  "access_token": "10|UCKQBTCKoAgmpLytlATxHc2KJpKUNd0LLSEhsH7u7c5fa4f5",
  "type": "bearer"
}
```

try to save it in `localStorage` or `cookie` to use if with your requests, **every request** must be send with headers:

- `Accept` : **only** must be `application/json`
- `Authorization` : contains `Bearer <token>`

> Warning: this headers are important friend **every request** must contains them

```js
const axios = require("axios");

let config = {
  method: "get",
  maxBodyLength: Infinity,
  url: "http://127.0.0.1:8000/api/transactions",
  headers: {
    Accept: "application/json",
    Authorization: "Bearer 10|UCKQBTCKoAgmpLytlATxHc2KJpKUNd0LLSEhsH7u7c5fa4f5",
  },
};

axios
  .request(config)
  .then((res) => {
    console.log(JSON.stringify(res.data));
  })
  .catch((error) => {
    console.log(error);
  });
```

---

## Success response formate:

```json
{
  "status": 200,
  "message": "success",
  "data": {
    //
  }
}
```

## failed response for all endpoints:

```json
{
  "status": 404,
  "message": "failed",
  "error": "error details"
}
```

> NOTE : Every failed resonse will be like this.

## if user is not logged with return response:

```json
{
  "status": 403,
  "message": "Unauthenticate action !"
}
```

---

# API endpoints

---

## Login endpoint: `POST: /api/login` :

##### Request data expected:

    - email (string, email)
    - password (string)

#### Success response `/api/logint`:

```json
{
  "access_token": "10|UCKQBTCKoAgmpLytlATxHc2KJpKUNd0LLSEhsH7u7c5fa4f5",
  "type": "bearer"
}
```

#### Success response `/api/logout`:

```json
{
  "message": "Logged out successfully"
}
```

---

## Register endpoint

` POST: /api/register` :

#### Request data expected:

    - name      (string, [you can make `firstName + lastName`])
    - email     (string, email)
    - password  (string)

#### Success response `/api/register`:

```json
{
  "access_token": "10|UCKQBTCKoAgmpLytlATxHc2KJpKUNd0LLSEhsH7u7c5fa4f5",
  "type": "bearer"
}
```

---

## Statistics endpoints:

1- `GET: /api/stats/day/{day?}` // e.g `/api/stats/day/2024-12-26`

### Success response for `/api/stats/day/{day?}`:

```json
{
  "status": 200,
  "message": "success",
  "date": {
    "income": [
      {
        "total_transactions": 6,
        "total_amount": "522020.00"
      }
    ],
    "expense": [
      {
        "total_transactions": 2,
        "total_amount": "2000.00"
      }
    ],
    "remain_amount": 520020
  }
}
```

2- `GET: /api/stats/year/{year?}` // e.g `/api/stats/year/2024`

### Success response for `/api/stats/year/{year?}`:

```json
{
  "status": 200,
  "message": "success",
  "date": {
    "income": [
      {
        "total_transactions": 6,
        "total_amount": "522020.00"
      }
    ],
    "expense": [
      {
        "total_transactions": 2,
        "total_amount": "2000.00"
      }
    ],
    "remain_amount": 520020
  }
}
```

3- `GET: /api/stats/month/{month?}`

### Success response for `/api/stats/month/{month?}`:

```json
{
  "status": 200,
  "message": "success",
  "date": {
    "income": [
      {
        "total_transactions": 6,
        "total_amount": "522020.00"
      }
    ],
    "expense": [
      {
        "total_transactions": 2,
        "total_amount": "2000.00"
      }
    ],
    "remain_amount": 520020
  }
}
```

---

## Transactions endpoints:

1- `POST: /api/transactions/create`

#### Request data expected:

    - title         (string, required)
    - amount        (integer),
    - category      available:['salary','shopping','home','car','family & personal','git','heathcare','business','rent','food','other'],
    - type          (only `income` or `expense`),
    - description    (string, optional)

##### e.g Request data :

```json
{
  "title": "another transaction",
  "type": "income",
  "amount": 10000,
  "category": "shopping",
  "date": "2024-08-08"
}
```

### Success response for `/api/transactions/create`:

```json
{
    "status": 200,
    "message": "success",
    "data": {
        "title": "Buy clothes",
        "amount": "500000",
        "category": "shopping",
        "type": "expense",
        "description": "t-shirts",
        "user_id": 4,
        "updated_at": "2024-12-27T11:58:32.000000Z",
        "created_at": "2024-12-27T11:58:32.000000Z",
        "id": 43
    }
}
```

2- `GET: /api/transactions`

### Success response for `/api/transactions`:

```json
{
    "status": 200,
    "message": "success",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 33,
                "title": "project salary",
                "description": "new balance",
                "type": "income",
                "amount": "500000.00",
                "category": "salary",
                "user_id": 4,
                "date": null,
                "created_at": "2024-12-26T11:06:22.000000Z",
                "updated_at": "2024-12-26T11:06:22.000000Z"
            },
           ...
        ],
        "first_page_url": "http://127.0.0.1:8000/api/transactions?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://127.0.0.1:8000/api/transactions?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/transactions?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http://127.0.0.1:8000/api/transactions",
        "per_page": 15,
        "prev_page_url": null,
        "to": 8,
        "total": 8
    }
}
```

2- `GET: /api/transactions/{id}`

### Success response for `/api/transactions/33`:

```json
	{
    	"status": 200,
    	"message": "success",
    	"data": {
        	"id": 33,
        	"title": "project salary",
        	"description": "new balance",
        	"type": "income",
        	"amount": "500000.00",
        	"category": "salay",
        	"user_id": 4,
        	"date": null,
        	"created_at": "2024-12-26T11:06:22.000000Z",
        	"updated_at": "2024-12-26T11:06:22.000000Z"
     }
```

3- `PUT: /api/transactions/update/{id}`

#### Request data expected:

    - title (string)
    - amount (integer),
    - category_id (integer, required),
    - type (only `income` or `expense`),
    - description (string, optional)

##### e.g Request data :

```json
{
  "title": "another transaction",
  "type": "income",
  "amount": 10000,
  "category": "salary",
  "date": "2024-08-08"
}
```

### Success response for `/api/transactions/update/1`:

```json
{
  "status": 200,
  "message": "success",
  "date": {
    "title": "another transaction",
    "type": "income",
    "amount": 10000,
    "category": "other",
    "user_id": 4,
    "date": "2024-08-08"
  }
}
```

4- `DELETE: /api/transactions/delete/{id}`

### Success response for `/api/transactions/delete/1`:

```json
{
  "status": 200,
  "message": "success"
}
```

5- `GET: /api/transactions/type/{type?}`

- get transactions by type (must be **only** `income` or `expense`)

### Success response for /api/transactions/type/{type?}:

```json
{
    "status": 200,
    "message": "success",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 33,
                "title": "project salary",
                "description": "new balance",
                "type": "{type}",
                "amount": "500000.00",
                "category": "salary",
                "user_id": 4,
                "date": null,
                "created_at": "2024-12-26T11:06:22.000000Z",
                "updated_at": "2024-12-26T11:06:22.000000Z"
            },
           ...
        ],
        "first_page_url": "http://127.0.0.1:8000/api/transactions?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://127.0.0.1:8000/api/transactions?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/transactions?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http://127.0.0.1:8000/api/transactions",
        "per_page": 15,
        "prev_page_url": null,
        "to": 8,
        "total": 8
    }
}
```

6- `GET: /api/transactions/date/{date}`

- `date` param available values: ['today', 'week', 'month', 'year']

### Success response for `/api/transactions/date/today`:

```json
{    "status": 200,
    "message": "success",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 33,
                "title": "project salary",
                "description": "new balance",
                "type": "income",
                "amount": "500000.00",
                "category": "salary",
                "user_id": 4,
                "date": null,
                "created_at": "2024-12-26T11:06:22.000000Z",
                "updated_at": "2024-12-26T11:06:22.000000Z"
            },
            ...
        "first_page_url": "http://127.0.0.1:8000/api/transactions/date/today?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://127.0.0.1:8000/api/transactions/date/today?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/transactions/date/today?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http://127.0.0.1:8000/api/transactions/date/today",
        "per_page": 15,
        "prev_page_url": null,
        "to": 8,
        "total": 8
   }
```

7- `GET: /api/transactions/date/get/{date}` // e.g. /api/transactions/date/get/2024-12-10

- get transactions for sepicific date .

### Success response for `GET: /api/transactions/date/get/{date}`:

> like point number 6

---

### Goals :

1- `GET: /api/goals`

### Success response for `GET: /api/goals`:

```json
{
  "status": 200,
  "message": "success",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "first goal - buy car (update)",
        "target_amount": "400000.00",
        "current_amount": "3000.00",
        "user_id": 4,
        "due_date": "2025-05-05",
        "created_at": "2024-12-26T06:06:37.000000Z",
        "updated_at": "2024-12-26T06:21:12.000000Z"
      },
      {
        "id": 3,
        "name": "Buy IPhone",
        "target_amount": "400000.00",
        "current_amount": "1000.00",
        "user_id": 4,
        "due_date": "2025-06-05",
        "created_at": "2024-12-26T06:24:34.000000Z",
        "updated_at": "2024-12-26T06:24:34.000000Z"
      }
    ],
    "first_page_url": "http://127.0.0.1:8000/api/goals?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://127.0.0.1:8000/api/goals?page=1",
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://127.0.0.1:8000/api/goals?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": null,
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "next_page_url": null,
    "path": "http://127.0.0.1:8000/api/goals",
    "per_page": 15,
    "prev_page_url": null,
    "to": 2,
    "total": 2
  }
}
```

2- `GET: /api/goals/{id}`

### Success response for `GET: /api/goals/1`:

```json
{
  "status": 200,
  "message": "success",
  "data": {
    "id": 1,
    "name": "first goal - buy car (update)",
    "target_amount": "400000.00",
    "current_amount": "3000.00",
    "user_id": 4,
    "due_date": "2025-05-05",
    "created_at": "2024-12-26T06:06:37.000000Z",
    "updated_at": "2024-12-26T06:21:12.000000Z",
    "stats": {
      "remain_amount": 397000,
      "progress": 0.75
    }
  }
}
```

3- `POST: /api/goals/create`

#### Request data expected

    - name (string, required)
    - target_amount (integer)
    - current_amount (integer)
    - due_date (date)

### Success response for `POST: /api/goals/create`:

```json
  {
    "status": 200,
    "message": "success",
    "data":{
        "id": 4,
        "name": "Buy Labtop",
        "target_amount": "10000.00",
        "current_amount": "1000.00",
        "user_id": 4,
        "due_date": "2025-04-01",
        "created_at": "2024-12-26T06:24:34.000000Z",
        "updated_at": "2024-12-26T06:24:34.000000Z"
    }
```

3- `PUT: /api/goals/update/{id}`

#### Request data expected:

    - id 				(integer)
    - name 				(string, required)
    - target_amount		(integer)
    - current_amount 	(integer)
    - due_date			(date)

### Success response for `PUT: /api/goals/update/4`:

```json
{
    "status": 200,
    "message": "success",
    "data": {
        "id": 4,
        "name": "Buy a new HP Labtop",
        "target_amount": "10000.00",
        "current_amount": "2000.00",
        "user_id": 4,
        "due_date": "2025-04-01",
        "created_at": "2024-12-26T06:24:34.000000Z",
        "updated_at": "2024-12-26T06:24:34.000000Z"
}
```

4- `DELETE: /api/goals/delete/{id}`

### Success response for `/api/goals/delete/1`:

```json
{
  "status": 200,
  "message": "success"
}
```
---

### Profile enpoints:

1- `GET: /api/profile`

### Success response for `/api/profile/`

```json
{
  "status": 200,
  "message": "success",
  "data": {
    "id": 4,
    "name": "mezzzo",
    "email": "mazin@exmple.com",
    "email_verified_at": null,
    "created_at": "2024-12-25T16:36:02.000000Z",
    "updated_at": "2024-12-26T09:10:20.000000Z"
  }
}
```

2- `PUT: /api/profile/reset-password`


##### Request data expected:
    - old_password                (string, required)
    - new_password                (string, required)
    - new_password_confirmation   (string)

### Success response for `/api/profile/reset-password`

```json
  {
    "status": 200,
    "message": "success",
    "data": "password reset successful"
  }
```

3- `DELETE: /api/profile/deleteAccount`

### Success response for `/api/profile/deleteAccount`
```json
  {
    "status": 200,
    "message": "success",
    "data": "delete"
  }
```