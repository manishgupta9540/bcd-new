---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.

<!-- END_INFO -->

#Instant Verification APIs


APIs for managing Instant Verification checks
<!-- START_ff3c66415b9f8606ffa875882fae5cff -->
## Aadhar API

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for show the Aadhar details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/aadhar" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"aadhar_number":986018457823}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/aadhar"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "aadhar_number": 986018457823
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "status": false,
    "message": "Permission Denied!!"
}
```
> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (406):

```json
{
    "status": false,
    "data": null,
    "message": "It seems like ID number is not valid!"
}
```
> Example response (200):

```json
{
    "status": true,
    "data": {
        "aadhar_number": "986018457823",
        "aadhar_validity": "valid",
        "verification_check": "completed",
        "result": {
            "aadhar_number_exist": "986018457823",
            "age_bond": "20-30",
            "gender": "Male",
            "state": "Delhi",
            "mobile_last_digits": 28
        }
    },
    "initiated_date": "19-05-2021",
    "completed_date": "19-05-2021",
    "message": "Verification Done Successfully !!"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/aadhar`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `aadhar_number` | integer |  required  | Aadhar Number to run check on (digits:12).
    
<!-- END_ff3c66415b9f8606ffa875882fae5cff -->

<!-- START_20028f268615ff739f3fd3f215988e61 -->
## PAN API

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for show the PAN details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/pan" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"pan_number":"\"GPWPS3116F\""}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/pan"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "pan_number": "\"GPWPS3116F\""
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "status": false,
    "message": "Permission Denied!!"
}
```
> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (406):

```json
{
    "status": false,
    "data": null,
    "message": "It seems like ID number is not valid!"
}
```
> Example response (200):

```json
{
    "status": true,
    "data": {
        "pan_number": "GPWPS3116F",
        "pan_validity": "valid",
        "verification_check": "completed",
        "result": {
            "pan_number_exist": "GPWPS3116F",
            "full_name": "AMIT SAH"
        }
    },
    "initiated_date": "19-05-2021",
    "completed_date": "19-05-2021",
    "message": "Verification Done Successfully !!"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/pan`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `pan_number` | string |  required  | PAN Number to run check on (PAN Format & digits:10).
    
<!-- END_20028f268615ff739f3fd3f215988e61 -->

<!-- START_79a1601a9b4b19aa0f4c4e842f75ce0e -->
## Voter ID API

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for show the Voter ID details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/voterid" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"voter_id_number":"\"BCQ5016258\""}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/voterid"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "voter_id_number": "\"BCQ5016258\""
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "status": false,
    "message": "Permission Denied!!"
}
```
> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (406):

```json
{
    "status": false,
    "data": null,
    "message": "It seems like ID number is not valid!"
}
```
> Example response (200):

```json
{
    "status": true,
    "data": {
        "voter_id_number": "BCQ5016258",
        "voter_id_validity": "valid",
        "verification_check": "completed",
        "result": {
            "voter_id_number_exist": "BCQ5016258",
            "full_name": "Souvik Mukherjee",
            "gender": "Male",
            "age": "36",
            "dob": null,
            "house_no": "",
            "area": "Bahir Sarbamangala Harijan F.P School",
            "state": "West Bengal"
        }
    },
    "initiated_date": "19-05-2021",
    "completed_date": "19-05-2021",
    "message": "Verification Done Successfully !!"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/voterid`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `voter_id_number` | string |  required  | Voter ID Number to run check on (Voter ID Format & digits:10).
    
<!-- END_79a1601a9b4b19aa0f4c4e842f75ce0e -->

<!-- START_b74a97db41ca54280a4e2c35f3193fe9 -->
## RC API

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for show the RC details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/rc" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"rc_number":"\"UP82AE1242\""}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/rc"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "rc_number": "\"UP82AE1242\""
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "status": false,
    "message": "Permission Denied!!"
}
```
> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (406):

```json
{
    "status": false,
    "data": null,
    "message": "It seems like ID number is not valid!"
}
```
> Example response (200):

```json
{
    "status": true,
    "data": {
        "rc_number": "UP82AE1242",
        "rc_validity": "valid",
        "verification_check": "completed",
        "result": {
            "rc_number_exist": "UP82AE1242",
            "registration_date": "2019-05-14",
            "owner_name": "CHETAN PRAKASH VERMA",
            "vehicle_chasis_number": "MA3JMT31SKD1XXXXX",
            "vehicle_engine_number": "K10BN82XXXXX",
            "fuel_type": "PETROL",
            "norms_type": "BHARAT STAGE IV",
            "insurance_company": "",
            "insurance_policy_number": "",
            "insurance_upto": "2021-04-14",
            "registered_at": "ETAH, UTTAR PRADESH"
        }
    },
    "initiated_date": "20-05-2021",
    "completed_date": "20-05-2021",
    "message": "Verification Done Successfully !!"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/rc`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `rc_number` | string |  required  | RC Number to run check on (RC Format & min:8).
    
<!-- END_b74a97db41ca54280a4e2c35f3193fe9 -->

<!-- START_45f42b46642e4da78b6ca12a48b23893 -->
## Passport API

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for show the Passport details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/passport" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"file_number":"\"BP8063370822817\"","dob":"\"1991-07-05\""}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/passport"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "file_number": "\"BP8063370822817\"",
    "dob": "\"1991-07-05\""
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "status": false,
    "message": "Permission Denied!!"
}
```
> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (406):

```json
{
    "status": false,
    "data": null,
    "message": "It seems like ID number is not valid!"
}
```
> Example response (200):

```json
{
    "status": true,
    "data": {
        "passport_number": "Y5094251",
        "passport_validity": "valid",
        "verification_check": "completed",
        "result": {
            "passport_number_exist": "Y5094252",
            "name": "MONIKA GAUTAM",
            "dob": "1991-07-05",
            "file_number": "BP8063370822817",
            "date_of_application": "2019-02-15"
        }
    },
    "initiated_date": "28-05-2021",
    "completed_date": "28-05-2021",
    "message": "Verification Done Successfully !!"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/passport`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `file_number` | string |  required  | File Number to run check on (alpha-numeric & min:8).
        `dob` | date |  required  | Date of Birth to run check on.
    
<!-- END_45f42b46642e4da78b6ca12a48b23893 -->

<!-- START_9bca5a2d091947bbce5f407c51e0afde -->
## DL API

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for show the Driving License details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/driving" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"dl_number":"\"DL0520160307903\""}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/driving"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "dl_number": "\"DL0520160307903\""
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "status": false,
    "message": "Permission Denied!!"
}
```
> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (406):

```json
{
    "status": false,
    "data": null,
    "message": "It seems like ID number is not valid!"
}
```
> Example response (200):

```json
{
    "status": true,
    "data": {
        "dl_number": "DL0520160307903",
        "dl_validity": "valid",
        "verification_check": "completed",
        "result": {
            "dl_number_exist": "DL0520160307903",
            "name": "AAKASH KUMAR VERMA",
            "gender": "Male",
            "dob": "1996-02-18",
            "father_or_husband_name": "GIRISH KUMAR SHARMA",
            "permanent_address": "V-307 GALI NO- 22 V- BLOCKVIJAY PARK,DELHI",
            "state": "DL",
            "citizenship": "IND",
            "dto": "DY.DIR.ZONAL OFFICE,DELHI NORTH EAST, LONI ROAD",
            "date_of_expiry": "2036-09-15"
        }
    },
    "initiated_date": "28-05-2021",
    "completed_date": "28-05-2021",
    "message": "Verification Done Successfully !!"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/driving`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `dl_number` | string |  required  | DL Number to run check on (alpha-numeric & min:8).
    
<!-- END_9bca5a2d091947bbce5f407c51e0afde -->

<!-- START_cb3a5230c4172b061d5b1ec83c393c7a -->
## GSTIN API

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for show the GST details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/gstin" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"gst_number":"\"37AAACI4403L1ZN\"","filling_status":true}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/gstin"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "gst_number": "\"37AAACI4403L1ZN\"",
    "filling_status": true
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "status": false,
    "message": "Permission Denied!!"
}
```
> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (406):

```json
{
    "status": false,
    "data": null,
    "message": "It seems like ID number is not valid!"
}
```
> Example response (200):

```json
{
    "status": true,
    "data": {
        "gst_number": "37AAACI4403L1ZN",
        "gst_validity": "valid",
        "verification_check": "completed",
        "result": {
            "gst_number_exist": "37AAACI4403L1ZN",
            "business_name": "IBM  INDIA PVT LTD",
            "address": "IBM",
            "center_jurisdiction": "IBM",
            "date_of_registration": "2017-07-01",
            "constitution_of_business": "Private Limited Company",
            "taxpayer_type": "Regular",
            "gstin_status": "Active"
        }
    },
    "initiated_date": "28-05-2021",
    "completed_date": "28-05-2021",
    "message": "Verification Done Successfully !!"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/gstin`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `gst_number` | string |  required  | GST Number to run check on (alpha-numeric & min:15).
        `filling_status` | boolean |  required  | Filling Status to run check on.
    
<!-- END_cb3a5230c4172b061d5b1ec83c393c7a -->

<!-- START_b1f314553d4e666054cd5e5184d932d4 -->
## Bank Verification API

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for show the Bank Account details based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/bankaccount" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"account_number":"\"164001502522\"","ifsc_code":"\"ICIC0002644\""}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/bankaccount"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "account_number": "\"164001502522\"",
    "ifsc_code": "\"ICIC0002644\""
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "status": false,
    "message": "Permission Denied!!"
}
```
> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (406):

```json
{
    "status": false,
    "data": null,
    "message": "It seems like ID number is not valid!"
}
```
> Example response (200):

```json
{
    "status": true,
    "data": {
        "account_number": "164001502522",
        "account_validity": "valid",
        "verification_check": "completed",
        "result": {
            "name": "AMIT SAH",
            "account_number": "164001502522",
            "ifsc_code": "ICIC0002644"
        }
    },
    "initiated_date": "28-05-2021",
    "completed_date": "28-05-2021",
    "message": "Verification Done Successfully !!"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/bankaccount`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `account_number` | string |  required  | Account Number to run check on (alpha-numeric & min:9,max:18).
        `ifsc_code` | string |  required  | IFSC Code to run check on.
    
<!-- END_b1f314553d4e666054cd5e5184d932d4 -->

<!-- START_16eddd96d9f66f8fe090e16ff19cf0c6 -->
## ID Check Telecom API

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for show the Telecom details or Sends an OTP based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/telecom" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"mobile_number":9876543216}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/telecom"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "mobile_number": 9876543216
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (200):

```json
{
    "status": true,
    "db": true,
    "data": {
        "mobile_number": "XXXXXXXX16",
        "mobile_validity": "valid",
        "verification_check": "completed",
        "result": {
            "name": "Ravi Bishnoi",
            "dob": "03 March, 1996",
            "address": "dfdfsgsdf",
            "mobile": "XXXXXXXX16",
            "alternative": "XXXXXXXX47",
            "operator": "vi",
            "billing_type": "prepaid",
            "email": "N\/A",
            "city": "South delhi",
            "state": "Delhi",
            "pin_code": "110007"
        }
    },
    "initiated_date": "29-05-2021",
    "completed_date": "29-05-2021",
    "message": "Verification Done Successfully !!"
}
```
> Example response (200):

```json
{
    "status": true,
    "db": false,
    "data": {
        "mobile_number": "XXXXXXXX16",
        "mobile_validity": "valid",
        "verification_check": "pending",
        "result": {
            "client_id": "telecom_CaoEgfyNCELFgdiulUco",
            "otp_sent": true,
            "operator": "vi",
            "if_number": true
        }
    },
    "message": "SMS Sent to your mobile Number !"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/telecom`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `mobile_number` | integer |  required  | Mobile Number to run check on (digits:10).
    
<!-- END_16eddd96d9f66f8fe090e16ff19cf0c6 -->

<!-- START_20250acf6a9a7b4f241b14f48ec82279 -->
## Verify Check Telecom API

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for show the Telecom details based on who logs in (i.e; if the user belongs to an Admin/COC) and Verification details send by ID Check Telecom API.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/verify_telecom" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"mobile_number":9876543216,"client_id":"\"telecom_CaoEgfyNCELFgdiulUco\"","sms_otp":8754}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/verify_telecom"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "mobile_number": 9876543216,
    "client_id": "\"telecom_CaoEgfyNCELFgdiulUco\"",
    "sms_otp": 8754
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (406):

```json
{
    "status": false,
    "data": null,
    "mobile_number": "Please enter a valid mobile number !"
}
```
> Example response (200):

```json
{
    "status": true,
    "db": true,
    "data": {
        "mobile_number": "XXXXXXXX16",
        "mobile_validity": "valid",
        "verification_check": "completed",
        "result": {
            "name": "Ravi Bishnoi",
            "dob": "03 March, 1996",
            "address": "dfdfsgsdf",
            "mobile": "XXXXXXXX16",
            "alternative": "XXXXXXXX47",
            "operator": "vi",
            "billing_type": "prepaid",
            "email": "N\/A",
            "city": "South delhi",
            "state": "Delhi",
            "pin_code": "110007"
        }
    },
    "initiated_date": "29-05-2021",
    "completed_date": "29-05-2021",
    "message": "Verification Done Successfully !!"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/verify_telecom`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `mobile_number` | integer |  required  | Mobile Number to run check on (digits:10).
        `client_id` | string |  required  | Client ID to run check on (alpha-numeric).
        `sms_otp` | integer |  required  | SMS OTP to run check on (min:4,max:6).
    
<!-- END_20250acf6a9a7b4f241b14f48ec82279 -->

<!-- START_0f428f2ce887e935c8aafb0bda08d9ac -->
## ID Check Covid 19 Generate OTP API

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for Send an OTP based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/covid19_generateotp" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"mobile_number":9876543216}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/covid19_generateotp"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "mobile_number": 9876543216
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (400):

```json
{
    "status": false,
    "data": null,
    "message": "Invalid Mobile Number ! Try Again !!"
}
```
> Example response (401):

```json
{
    "status": false,
    "data": null,
    "message": "Permission Denied !!"
}
```
> Example response (401):

```json
{
    "status": false,
    "data": null,
    "message": "Enter a Valid Mobile Number ! Try Again !!"
}
```
> Example response (412):

```json
{
    "status": false,
    "data": null,
    "message": "Something Went Wrong !!"
}
```
> Example response (200):

```json
{
    "status": true,
    "db": false,
    "data": {
        "mobile_number": "9876543216",
        "mobile_validity": "valid",
        "verification_check": "pending",
        "result": {
            "otp_id": "OA==",
            "txnId": "a46bf20f-5e2a-4da0-ba2e-e433aa3360ea"
        }
    },
    "message": "SMS Sent to your mobile Number !"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/covid19_generateotp`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `mobile_number` | integer |  required  | Mobile Number to run check on (digits:10).
    
<!-- END_0f428f2ce887e935c8aafb0bda08d9ac -->

<!-- START_66d5e3da94d599cbf065c698e19adc81 -->
## ID Check Covid 19 Verify OTP API

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for Verify the Mobile Number based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/covid19_verifyotp" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"txnId":"\"975567a0-558e-453f-80bd-3dacffd16d58\"","otp_id":"\"OQ==\"","otp":875485}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/covid19_verifyotp"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "txnId": "\"975567a0-558e-453f-80bd-3dacffd16d58\"",
    "otp_id": "\"OQ==\"",
    "otp": 875485
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (400):

```json
{
    "status": false,
    "data": null,
    "message": "Invalid OTP ! Try Again !!"
}
```
> Example response (401):

```json
{
    "status": false,
    "data": null,
    "message": "Permission Denied !!"
}
```
> Example response (401):

```json
{
    "status": false,
    "data": null,
    "message": "Enter a Valid OTP ! Try Again !!"
}
```
> Example response (412):

```json
{
    "status": false,
    "data": null,
    "message": "Something Went Wrong !!"
}
```
> Example response (200):

```json
{
    "status": true,
    "db": false,
    "data": {
        "mobile_number": "9876543216",
        "mobile_validity": "valid",
        "verification_check": "pending",
        "result": {
            "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiI0NDAyMTUzOS1iYWFiLTQ3YzQtOGZlYi1jZmNmMzQ2MDE5YjciLCJ1c2VyX3R5cGUiOiJCRU5FRklDSUFSWSIsInVzZXJfaWQiOiI0NDAyMTUzOS1iYWFiLTQ3YzQtOGZlYi1jZmNmMzQ2MDE5YjciLCJtb2JpbGVfbnVtYmVyIjo4NzAwMDM1NDI2LCJiZW5lZmljaWFyeV9yZWZlcmVuY2VfaWQiOjE0MzcxODk2NDEzOTMsInR4bklkIjoiOTc1NTY3YTAtNTU4ZS00NTNmLTgwYmQtM2RhY2ZmZDE2ZDU4IiwiaWF0IjoxNjI5MTA3NjkwLCJleHAiOjE2MjkxMDg1OTB9.n5VPnnaTGa77W28DSmsdeQkrkblzLhBtj58nX2hnyPQ"
        }
    },
    "message": "Mobile Number Verified !"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/covid19_verifyotp`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `txnId` | string |  required  | txn ID to run check on (min:1).
        `otp_id` | string |  required  | OTP ID to run check on (min:1).
        `otp` | integer |  required  | SMS OTP to run check on (min:4,max:6).
    
<!-- END_66d5e3da94d599cbf065c698e19adc81 -->

<!-- START_a15359ad75357b4e7c1f787af15b8854 -->
## ID Check Covid 19 Get Certificate

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
This API is used for Get the Certificate for whom is vaccinated based on who logs in (i.e; if the user belongs to an Admin/COC) and details you puts in.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/covid19_refcheck" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs" \
    -d '{"login_id":94,"token":"\"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiI0NDAyMTUzOS1iYWFiLTQ3YzQtOGZlYi1jZmNmMzQ2MDE5YjciLCJ1c2VyX3R5cGUiOiJCRU5FRklDSUFSWSIsInVzZXJfaWQiOiI0NDAyMTUzOS1iYWFiLTQ3YzQtOGZlYi1jZmNmMzQ2MDE5YjciLCJtb2JpbGVfbnVtYmVyIjo4NzAwMDM1NDI2LCJiZW5lZmljaWFyeV9yZWZlcmVuY2VfaWQiOjE0MzcxODk2NDEzOTMsInR4bklkIjoiOGQ3Y2Q1M2UtZWEwOC00ZGJiLWI0YTktODU5Mzg5Yjk4ZTAxIiwiaWF0IjoxNjI4NTc2NTQ0LCJleHAiOjE2Mjg1Nzc0NDR9.Qtc0O1pWVADR5Q5ezLynddiPKcK9SH3mPmPZymZtlEY\"","reference_id":53965833337440}'

```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/instant-verification/v1/idcheck/covid19_refcheck"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

let body = {
    "login_id": 94,
    "token": "\"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiI0NDAyMTUzOS1iYWFiLTQ3YzQtOGZlYi1jZmNmMzQ2MDE5YjciLCJ1c2VyX3R5cGUiOiJCRU5FRklDSUFSWSIsInVzZXJfaWQiOiI0NDAyMTUzOS1iYWFiLTQ3YzQtOGZlYi1jZmNmMzQ2MDE5YjciLCJtb2JpbGVfbnVtYmVyIjo4NzAwMDM1NDI2LCJiZW5lZmljaWFyeV9yZWZlcmVuY2VfaWQiOjE0MzcxODk2NDEzOTMsInR4bklkIjoiOGQ3Y2Q1M2UtZWEwOC00ZGJiLWI0YTktODU5Mzg5Yjk4ZTAxIiwiaWF0IjoxNjI4NTc2NTQ0LCJleHAiOjE2Mjg1Nzc0NDR9.Qtc0O1pWVADR5Q5ezLynddiPKcK9SH3mPmPZymZtlEY\"",
    "reference_id": 53965833337440
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "User Not Found!!"
}
```
> Example response (404):

```json
{
    "status": false,
    "data": null,
    "message": "The Given Data is Invalid!!"
}
```
> Example response (400):

```json
{
    "status": false,
    "data": null,
    "message": "Data Not Found !!"
}
```
> Example response (401):

```json
{
    "status": false,
    "data": null,
    "message": "Permission Denied !!"
}
```
> Example response (401):

```json
{
    "status": false,
    "data": null,
    "message": "Timeout ! Try Again Later!!"
}
```
> Example response (412):

```json
{
    "status": false,
    "data": null,
    "message": "Something Went Wrong !!"
}
```
> Example response (200):

```json
{
    "status": true,
    "db": true,
    "data": {
        "mobile_number": "9876543216",
        "reference_id": "5396583336879",
        "mobile_validity": "valid",
        "verification_check": "completed",
        "result": {
            "url": "http:\/\/bcdb2b.local\/cowin\/certificate\/202108160656223-cowin-certificate.pdf"
        }
    },
    "message": "Verification Done !"
}
```

### HTTP Request
`POST api/instant-verification/v1/idcheck/covid19_refcheck`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `login_id` | integer |  required  | Login ID for finding the user.
        `token` | string |  required  | token to run check on (min:1).
        `reference_id` | integer |  required  | Reference ID to run check on, which is linked to mobile number you have entered at the time of Generate OTP API, To Get this Id you have to visit the cowin site (https://selfregistration.cowin.gov.in/) & Get Logged In (min:1).
    
<!-- END_a15359ad75357b4e7c1f787af15b8854 -->

#general


<!-- START_cd4a874127cd23508641c63b640ee838 -->
## doc.json
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/doc.json" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/doc.json"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "variables": [],
    "info": {
        "name": "BCD API",
        "_postman_id": "22eb2768-459b-4150-b04d-69650f7e4deb",
        "description": "",
        "schema": "https:\/\/schema.getpostman.com\/json\/collection\/v2.0.0\/collection.json"
    },
    "item": [
        {
            "name": "Instant Verification APIs",
            "description": "\nAPIs for managing Instant Verification checks",
            "item": [
                {
                    "name": "Aadhar API",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/aadhar",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"aadhar_number\": 986018457823\n}"
                        },
                        "description": "This API is used for show the Aadhar details based on who logs in (i.e; if the user belongs to an Admin\/COC) and details you puts in.",
                        "response": []
                    }
                },
                {
                    "name": "PAN API",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/pan",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"pan_number\": \"\\\"GPWPS3116F\\\"\"\n}"
                        },
                        "description": "This API is used for show the PAN details based on who logs in (i.e; if the user belongs to an Admin\/COC) and details you puts in.",
                        "response": []
                    }
                },
                {
                    "name": "Voter ID API",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/voterid",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"voter_id_number\": \"\\\"BCQ5016258\\\"\"\n}"
                        },
                        "description": "This API is used for show the Voter ID details based on who logs in (i.e; if the user belongs to an Admin\/COC) and details you puts in.",
                        "response": []
                    }
                },
                {
                    "name": "RC API",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/rc",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"rc_number\": \"\\\"UP82AE1242\\\"\"\n}"
                        },
                        "description": "This API is used for show the RC details based on who logs in (i.e; if the user belongs to an Admin\/COC) and details you puts in.",
                        "response": []
                    }
                },
                {
                    "name": "Passport API",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/passport",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"file_number\": \"\\\"BP8063370822817\\\"\",\n    \"dob\": \"\\\"1991-07-05\\\"\"\n}"
                        },
                        "description": "This API is used for show the Passport details based on who logs in (i.e; if the user belongs to an Admin\/COC) and details you puts in.",
                        "response": []
                    }
                },
                {
                    "name": "DL API",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/driving",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"dl_number\": \"\\\"DL0520160307903\\\"\"\n}"
                        },
                        "description": "This API is used for show the Driving License details based on who logs in (i.e; if the user belongs to an Admin\/COC) and details you puts in.",
                        "response": []
                    }
                },
                {
                    "name": "GSTIN API",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/gstin",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"gst_number\": \"\\\"37AAACI4403L1ZN\\\"\",\n    \"filling_status\": true\n}"
                        },
                        "description": "This API is used for show the GST details based on who logs in (i.e; if the user belongs to an Admin\/COC) and details you puts in.",
                        "response": []
                    }
                },
                {
                    "name": "Bank Verification API",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/bankaccount",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"account_number\": \"\\\"164001502522\\\"\",\n    \"ifsc_code\": \"\\\"ICIC0002644\\\"\"\n}"
                        },
                        "description": "This API is used for show the Bank Account details based on who logs in (i.e; if the user belongs to an Admin\/COC) and details you puts in.",
                        "response": []
                    }
                },
                {
                    "name": "ID Check Telecom API",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/telecom",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"mobile_number\": 9876543216\n}"
                        },
                        "description": "This API is used for show the Telecom details or Sends an OTP based on who logs in (i.e; if the user belongs to an Admin\/COC) and details you puts in.",
                        "response": []
                    }
                },
                {
                    "name": "Verify Check Telecom API",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/verify_telecom",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"mobile_number\": 9876543216,\n    \"client_id\": \"\\\"telecom_CaoEgfyNCELFgdiulUco\\\"\",\n    \"sms_otp\": 8754\n}"
                        },
                        "description": "This API is used for show the Telecom details based on who logs in (i.e; if the user belongs to an Admin\/COC) and Verification details send by ID Check Telecom API.",
                        "response": []
                    }
                },
                {
                    "name": "ID Check Covid 19 Generate OTP API",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/covid19_generateotp",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"mobile_number\": 9876543216\n}"
                        },
                        "description": "This API is used for Send an OTP based on who logs in (i.e; if the user belongs to an Admin\/COC) and details you puts in.",
                        "response": []
                    }
                },
                {
                    "name": "ID Check Covid 19 Verify OTP API",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/covid19_verifyotp",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"txnId\": \"\\\"975567a0-558e-453f-80bd-3dacffd16d58\\\"\",\n    \"otp_id\": \"\\\"OQ==\\\"\",\n    \"otp\": 875485\n}"
                        },
                        "description": "This API is used for Verify the Mobile Number based on who logs in (i.e; if the user belongs to an Admin\/COC) and details you puts in.",
                        "response": []
                    }
                },
                {
                    "name": "ID Check Covid 19 Get Certificate",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/instant-verification\/v1\/idcheck\/covid19_refcheck",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"login_id\": 94,\n    \"token\": \"\\\"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiI0NDAyMTUzOS1iYWFiLTQ3YzQtOGZlYi1jZmNmMzQ2MDE5YjciLCJ1c2VyX3R5cGUiOiJCRU5FRklDSUFSWSIsInVzZXJfaWQiOiI0NDAyMTUzOS1iYWFiLTQ3YzQtOGZlYi1jZmNmMzQ2MDE5YjciLCJtb2JpbGVfbnVtYmVyIjo4NzAwMDM1NDI2LCJiZW5lZmljaWFyeV9yZWZlcmVuY2VfaWQiOjE0MzcxODk2NDEzOTMsInR4bklkIjoiOGQ3Y2Q1M2UtZWEwOC00ZGJiLWI0YTktODU5Mzg5Yjk4ZTAxIiwiaWF0IjoxNjI4NTc2NTQ0LCJleHAiOjE2Mjg1Nzc0NDR9.Qtc0O1pWVADR5Q5ezLynddiPKcK9SH3mPmPZymZtlEY\\\"\",\n    \"reference_id\": 53965833337440\n}"
                        },
                        "description": "This API is used for Get the Certificate for whom is vaccinated based on who logs in (i.e; if the user belongs to an Admin\/COC) and details you puts in.",
                        "response": []
                    }
                }
            ]
        },
        {
            "name": "general",
            "description": "",
            "item": [
                {
                    "name": "doc.json",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "doc.json",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/user",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/user",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/account\/sendSMSOTP",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/account\/sendSMSOTP",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/account\/verifySMSOTP",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/account\/verifySMSOTP",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/profile",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/profile",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/verification\/jaf\/form",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/verification\/jaf\/form",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/verification\/jaf\/form\/save",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/verification\/jaf\/form\/save",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/verification\/address\/form",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/verification\/address\/form",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/verification\/address",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/verification\/address",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/verification\/address\/data",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/verification\/address\/data",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/verification\/aadhar",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/verification\/aadhar",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/verifications\/{status}",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/verifications\/:status",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/store\/address",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/store\/address",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/states",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/states",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/addresstypelist",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/addresstypelist",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/addressfileupload",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/addressfileupload",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v1\/candidates\/addressfiledelete",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v1\/candidates\/addressfiledelete",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "api\/v2\/user",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "api\/v2\/user",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Show the main home page.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "\/",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Show the signup form",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "signup",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Show the thank you page after signup",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "thank-you",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "contact",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "contact",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "demopdfreport",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "demopdfreport",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "docsreport",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "docsreport",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "demoinvoice",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "demoinvoice",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "contactstore",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "contactstore",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "terms",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "terms",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "privacy-policy",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "privacy-policy",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Verify the email .",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "email-verify",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "forgot-password",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "forgot-password",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "pay",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "pay",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "payment",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "payment",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "plan\/create",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "plan\/create",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "plan\/subscription\/create",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "plan\/subscription\/create",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "User Authentication",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "userAuthenticate",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "forget\/password\/email",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "forget\/password\/email",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Show the form for creating a new resource.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "forget\/password\/:id\/:token_no",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Update password",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "forget\/password\/update",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "startverification",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "startverification",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "guest\/store",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "guest\/store",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "email_verification\/{id}",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "email_verification\/:id",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "email_verify",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "email_verify",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "thank-you-email_verify",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "thank-you-email_verify",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Show the application's login form.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "login",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Handle a login request to the application.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "login",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Log the user out of the application.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "logout",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Show the application registration form.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "register",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Handle a registration request for the application.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "register",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display the form to request a password reset link.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "password\/reset",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Send a reset link to the given user.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "password\/email",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display the password reset view for the given token.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "password\/reset\/:token",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "If no token is present, display the link request form.",
                        "response": []
                    }
                },
                {
                    "name": "Reset the given user's password.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "password\/reset",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Display the password confirmation view.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "password\/confirm",
                            "query": []
                        },
                        "method": "GET",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                },
                {
                    "name": "Confirm the given user's password.",
                    "request": {
                        "url": {
                            "protocol": "http",
                            "host": "bws.my-bcd.local",
                            "path": "password\/confirm",
                            "query": []
                        },
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application\/json"
                            },
                            {
                                "key": "Accept",
                                "value": "application\/json"
                            },
                            {
                                "key": "Authorization",
                                "value": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "[]"
                        },
                        "description": "",
                        "response": []
                    }
                }
            ]
        }
    ]
}
```

### HTTP Request
`GET doc.json`


<!-- END_cd4a874127cd23508641c63b640ee838 -->

<!-- START_96b8840d06e94c53a87e83e9edfb44eb -->
## api/v1/user
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/v1/user" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/user"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/user`


<!-- END_96b8840d06e94c53a87e83e9edfb44eb -->

<!-- START_ef6d6c68d29293e05741812382657d04 -->
## api/v1/candidates/account/sendSMSOTP
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/v1/candidates/account/sendSMSOTP" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/account/sendSMSOTP"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/candidates/account/sendSMSOTP`


<!-- END_ef6d6c68d29293e05741812382657d04 -->

<!-- START_94cb410ef095c5fe069d6d33609faf14 -->
## api/v1/candidates/account/verifySMSOTP
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/v1/candidates/account/verifySMSOTP" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/account/verifySMSOTP"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/candidates/account/verifySMSOTP`


<!-- END_94cb410ef095c5fe069d6d33609faf14 -->

<!-- START_5274338055bec9f5c4d4cbf21f94566c -->
## api/v1/candidates/profile
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/api/v1/candidates/profile" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/profile"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "status": "error",
    "message": "Required parameter is missing."
}
```

### HTTP Request
`GET api/v1/candidates/profile`


<!-- END_5274338055bec9f5c4d4cbf21f94566c -->

<!-- START_88855eb3abbd166499a4aa604476d02f -->
## api/v1/candidates/verification/jaf/form
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/v1/candidates/verification/jaf/form" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/verification/jaf/form"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/candidates/verification/jaf/form`


<!-- END_88855eb3abbd166499a4aa604476d02f -->

<!-- START_ffcb2ce34fed3cc2c8f19ef899bf4542 -->
## api/v1/candidates/verification/jaf/form/save
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/v1/candidates/verification/jaf/form/save" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/verification/jaf/form/save"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/candidates/verification/jaf/form/save`


<!-- END_ffcb2ce34fed3cc2c8f19ef899bf4542 -->

<!-- START_27ebd14895c0cd20af5ec95640ce9c03 -->
## api/v1/candidates/verification/address/form
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/api/v1/candidates/verification/address/form" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/verification/address/form"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "status": "error",
    "message": "The given data was invalid.",
    "errors": "Parameter is missing!"
}
```

### HTTP Request
`GET api/v1/candidates/verification/address/form`


<!-- END_27ebd14895c0cd20af5ec95640ce9c03 -->

<!-- START_0aabd682b64ee7d3b46b60e691bf51ff -->
## api/v1/candidates/verification/address
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/v1/candidates/verification/address" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/verification/address"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/candidates/verification/address`


<!-- END_0aabd682b64ee7d3b46b60e691bf51ff -->

<!-- START_22cb6a0e9c35e7b8647ac4d5f8ae754d -->
## api/v1/candidates/verification/address/data
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/api/v1/candidates/verification/address/data" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/verification/address/data"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "status": "error",
    "message": "The given data was invalid.",
    "errors": "Parameter is missing!"
}
```

### HTTP Request
`GET api/v1/candidates/verification/address/data`


<!-- END_22cb6a0e9c35e7b8647ac4d5f8ae754d -->

<!-- START_284a39198c93e63090d0db35333eb554 -->
## api/v1/candidates/verification/aadhar
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/api/v1/candidates/verification/aadhar" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/verification/aadhar"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "status": "error",
    "message": "The given data was invalid.",
    "errors": "Parameter is missing!"
}
```

### HTTP Request
`GET api/v1/candidates/verification/aadhar`


<!-- END_284a39198c93e63090d0db35333eb554 -->

<!-- START_b738d4077c0aef36252d6c0e0a3aec45 -->
## api/v1/candidates/verifications/{status}
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/api/v1/candidates/verifications/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/verifications/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "data": [
        "mihtilesh",
        "priyanka"
    ]
}
```

### HTTP Request
`GET api/v1/candidates/verifications/{status}`


<!-- END_b738d4077c0aef36252d6c0e0a3aec45 -->

<!-- START_25888f7a18dc91a404a999a2f3c44e56 -->
## api/v1/candidates/store/address
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/v1/candidates/store/address" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/store/address"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/candidates/store/address`


<!-- END_25888f7a18dc91a404a999a2f3c44e56 -->

<!-- START_eda1692320fc4f90874ff614113eeaf7 -->
## api/v1/states
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/api/v1/states" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/states"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "status": true,
    "data": [
        {
            "id": 1,
            "name": "Andaman and Nicobar Islands"
        },
        {
            "id": 2,
            "name": "Andhra Pradesh"
        },
        {
            "id": 3,
            "name": "Arunachal Pradesh"
        },
        {
            "id": 4,
            "name": "Assam"
        },
        {
            "id": 5,
            "name": "Bihar"
        },
        {
            "id": 6,
            "name": "Chandigarh"
        },
        {
            "id": 7,
            "name": "Chhattisgarh"
        },
        {
            "id": 8,
            "name": "Dadra and Nagar Haveli"
        },
        {
            "id": 9,
            "name": "Daman and Diu"
        },
        {
            "id": 10,
            "name": "Delhi"
        },
        {
            "id": 11,
            "name": "Goa"
        },
        {
            "id": 12,
            "name": "Gujarat"
        },
        {
            "id": 13,
            "name": "Haryana"
        },
        {
            "id": 14,
            "name": "Himachal Pradesh"
        },
        {
            "id": 15,
            "name": "Jammu and Kashmir"
        },
        {
            "id": 16,
            "name": "Jharkhand"
        },
        {
            "id": 17,
            "name": "Karnataka"
        },
        {
            "id": 18,
            "name": "Kenmore"
        },
        {
            "id": 19,
            "name": "Kerala"
        },
        {
            "id": 20,
            "name": "Lakshadweep"
        },
        {
            "id": 21,
            "name": "Madhya Pradesh"
        },
        {
            "id": 22,
            "name": "Maharashtra"
        },
        {
            "id": 23,
            "name": "Manipur"
        },
        {
            "id": 24,
            "name": "Meghalaya"
        },
        {
            "id": 25,
            "name": "Mizoram"
        },
        {
            "id": 26,
            "name": "Nagaland"
        },
        {
            "id": 27,
            "name": "Narora"
        },
        {
            "id": 28,
            "name": "Natwar"
        },
        {
            "id": 29,
            "name": "Odisha"
        },
        {
            "id": 30,
            "name": "Paschim Medinipur"
        },
        {
            "id": 31,
            "name": "Pondicherry"
        },
        {
            "id": 32,
            "name": "Punjab"
        },
        {
            "id": 33,
            "name": "Rajasthan"
        },
        {
            "id": 34,
            "name": "Sikkim"
        },
        {
            "id": 35,
            "name": "Tamil Nadu"
        },
        {
            "id": 36,
            "name": "Telangana"
        },
        {
            "id": 37,
            "name": "Tripura"
        },
        {
            "id": 38,
            "name": "Uttar Pradesh"
        },
        {
            "id": 39,
            "name": "Uttarakhand"
        },
        {
            "id": 40,
            "name": "Vaishali"
        },
        {
            "id": 41,
            "name": "West Bengal"
        }
    ]
}
```

### HTTP Request
`GET api/v1/states`


<!-- END_eda1692320fc4f90874ff614113eeaf7 -->

<!-- START_25be373163ee7335536d1472b40abb2d -->
## api/v1/addresstypelist
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/api/v1/addresstypelist" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/addresstypelist"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "status": "error",
    "message": "The given data was invalid.",
    "errors": {
        "candidate_id": [
            "The candidate id field is required."
        ]
    }
}
```

### HTTP Request
`GET api/v1/addresstypelist`


<!-- END_25be373163ee7335536d1472b40abb2d -->

<!-- START_40822023cbaafeb54ffc60149c326ffa -->
## api/v1/candidates/addressfileupload
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/v1/candidates/addressfileupload" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/addressfileupload"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/candidates/addressfileupload`


<!-- END_40822023cbaafeb54ffc60149c326ffa -->

<!-- START_513fc782ca9a627c318ec736f016432e -->
## api/v1/candidates/addressfiledelete
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/v1/candidates/addressfiledelete" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v1/candidates/addressfiledelete"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v1/candidates/addressfiledelete`


<!-- END_513fc782ca9a627c318ec736f016432e -->

<!-- START_a71c151af132886057e0008e19768862 -->
## api/v2/user
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/api/v2/user" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/api/v2/user"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/v2/user`


<!-- END_a71c151af132886057e0008e19768862 -->

<!-- START_53be1e9e10a08458929a2e0ea70ddb86 -->
## Show the main home page.

> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET /`


<!-- END_53be1e9e10a08458929a2e0ea70ddb86 -->

<!-- START_6ea5fa55ccef15129c404506202f5dd2 -->
## Show the signup form

> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/signup" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/signup"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET signup`


<!-- END_6ea5fa55ccef15129c404506202f5dd2 -->

<!-- START_4e50c4331d625bc0d547949645ac6c50 -->
## Show the thank you page after signup

> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/thank-you" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/thank-you"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET thank-you`


<!-- END_4e50c4331d625bc0d547949645ac6c50 -->

<!-- START_679ea4e19d49028fd5a7bd6ee9f0f308 -->
## contact
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/contact" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/contact"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET contact`


<!-- END_679ea4e19d49028fd5a7bd6ee9f0f308 -->

<!-- START_d5fec7f6b91d225a9aeab2bd9d041c03 -->
## demopdfreport
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/demopdfreport" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/demopdfreport"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET demopdfreport`


<!-- END_d5fec7f6b91d225a9aeab2bd9d041c03 -->

<!-- START_cd95c605f68d14693ae2711181b0a165 -->
## docsreport
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/docsreport" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/docsreport"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET docsreport`


<!-- END_cd95c605f68d14693ae2711181b0a165 -->

<!-- START_d4d32f269d62b6a77b199c52ea5e3cee -->
## demoinvoice
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/demoinvoice" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/demoinvoice"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET demoinvoice`


<!-- END_d4d32f269d62b6a77b199c52ea5e3cee -->

<!-- START_c0c35a3dc5ad64aaede4e99162ca3c27 -->
## contactstore
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/contactstore" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/contactstore"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST contactstore`


<!-- END_c0c35a3dc5ad64aaede4e99162ca3c27 -->

<!-- START_5d6ce2ec72b9360a929e0425b42a2a8a -->
## terms
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/terms" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/terms"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET terms`


<!-- END_5d6ce2ec72b9360a929e0425b42a2a8a -->

<!-- START_3a8259abf1d6f5c6d47f1d16e36f8d55 -->
## privacy-policy
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/privacy-policy" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/privacy-policy"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET privacy-policy`


<!-- END_3a8259abf1d6f5c6d47f1d16e36f8d55 -->

<!-- START_5cd1349ff8ca46c8a6c2f68abe957ed5 -->
## Verify the email .

> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/email-verify" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/email-verify"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET email-verify`


<!-- END_5cd1349ff8ca46c8a6c2f68abe957ed5 -->

<!-- START_60efc9f5157c5ed604f4a3f83ee14d6b -->
## forgot-password
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/forgot-password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/forgot-password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET forgot-password`


<!-- END_60efc9f5157c5ed604f4a3f83ee14d6b -->

<!-- START_552eb83f4fed6da80e2547a2c4dfb678 -->
## pay
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/pay" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/pay"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET pay`


<!-- END_552eb83f4fed6da80e2547a2c4dfb678 -->

<!-- START_48eb8351f36c52d178ebccb279cd8d71 -->
## payment
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/payment" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/payment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST payment`


<!-- END_48eb8351f36c52d178ebccb279cd8d71 -->

<!-- START_688d04ba18fb662be3d4d2ef0552134c -->
## plan/create
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/plan/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/plan/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET plan/create`


<!-- END_688d04ba18fb662be3d4d2ef0552134c -->

<!-- START_37e05c9a4a0088227a851420ca135015 -->
## plan/subscription/create
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/plan/subscription/create" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/plan/subscription/create"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`GET plan/subscription/create`


<!-- END_37e05c9a4a0088227a851420ca135015 -->

<!-- START_fe61a3d5efb2a81504cb86843a3c62e2 -->
## User Authentication

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/userAuthenticate" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/userAuthenticate"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST userAuthenticate`


<!-- END_fe61a3d5efb2a81504cb86843a3c62e2 -->

<!-- START_7baa73f0f3784f1299ad806b2250aed2 -->
## forget/password/email
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/forget/password/email" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/forget/password/email"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST forget/password/email`


<!-- END_7baa73f0f3784f1299ad806b2250aed2 -->

<!-- START_18b29677137d9f5d33aa4b4d9d6b9a9a -->
## Show the form for creating a new resource.

> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/forget/password/1/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/forget/password/1/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET forget/password/{id}/{token_no}`


<!-- END_18b29677137d9f5d33aa4b4d9d6b9a9a -->

<!-- START_860c9073f9081d9c340195617714910f -->
## Update password

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/forget/password/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/forget/password/update"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST forget/password/update`


<!-- END_860c9073f9081d9c340195617714910f -->

<!-- START_01226d8391809f0f37e2e57f0562c8f7 -->
## startverification
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/startverification" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/startverification"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (302):

```json
null
```

### HTTP Request
`GET startverification`


<!-- END_01226d8391809f0f37e2e57f0562c8f7 -->

<!-- START_9797ddd6da40df3baa708e9faf4eec7c -->
## guest/store
> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/guest/store" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/guest/store"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST guest/store`


<!-- END_9797ddd6da40df3baa708e9faf4eec7c -->

<!-- START_174271dd13e82c57aba0bcedab9a063a -->
## email_verification/{id}
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/email_verification/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/email_verification/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET email_verification/{id}`


<!-- END_174271dd13e82c57aba0bcedab9a063a -->

<!-- START_73fc098db4bf5a489963b5956b86571e -->
## email_verify
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/email_verify" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/email_verify"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (302):

```json
null
```

### HTTP Request
`GET email_verify`


<!-- END_73fc098db4bf5a489963b5956b86571e -->

<!-- START_edeec83d98c2592914fda9f80c0829dd -->
## thank-you-email_verify
> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/thank-you-email_verify" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/thank-you-email_verify"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET thank-you-email_verify`


<!-- END_edeec83d98c2592914fda9f80c0829dd -->

<!-- START_66e08d3cc8222573018fed49e121e96d -->
## Show the application&#039;s login form.

> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (302):

```json
null
```

### HTTP Request
`GET login`


<!-- END_66e08d3cc8222573018fed49e121e96d -->

<!-- START_ba35aa39474cb98cfb31829e70eb8b74 -->
## Handle a login request to the application.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST login`


<!-- END_ba35aa39474cb98cfb31829e70eb8b74 -->

<!-- START_e65925f23b9bc6b93d9356895f29f80c -->
## Log the user out of the application.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/logout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/logout"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST logout`


<!-- END_e65925f23b9bc6b93d9356895f29f80c -->

<!-- START_ff38dfb1bd1bb7e1aa24b4e1792a9768 -->
## Show the application registration form.

> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (302):

```json
null
```

### HTTP Request
`GET register`


<!-- END_ff38dfb1bd1bb7e1aa24b4e1792a9768 -->

<!-- START_d7aad7b5ac127700500280d511a3db01 -->
## Handle a registration request for the application.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST register`


<!-- END_d7aad7b5ac127700500280d511a3db01 -->

<!-- START_d72797bae6d0b1f3a341ebb1f8900441 -->
## Display the form to request a password reset link.

> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/password/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET password/reset`


<!-- END_d72797bae6d0b1f3a341ebb1f8900441 -->

<!-- START_feb40f06a93c80d742181b6ffb6b734e -->
## Send a reset link to the given user.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/password/email" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/password/email"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST password/email`


<!-- END_feb40f06a93c80d742181b6ffb6b734e -->

<!-- START_e1605a6e5ceee9d1aeb7729216635fd7 -->
## Display the password reset view for the given token.

If no token is present, display the link request form.

> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/password/reset/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/password/reset/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET password/reset/{token}`


<!-- END_e1605a6e5ceee9d1aeb7729216635fd7 -->

<!-- START_cafb407b7a846b31491f97719bb15aef -->
## Reset the given user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/password/reset" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/password/reset"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST password/reset`


<!-- END_cafb407b7a846b31491f97719bb15aef -->

<!-- START_b77aedc454e9471a35dcb175278ec997 -->
## Display the password confirmation view.

> Example request:

```bash
curl -X GET \
    -G "http://bws.my-bcd.local/password/confirm" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/password/confirm"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
null
```

### HTTP Request
`GET password/confirm`


<!-- END_b77aedc454e9471a35dcb175278ec997 -->

<!-- START_54462d3613f2262e741142161c0e6fea -->
## Confirm the given user&#039;s password.

> Example request:

```bash
curl -X POST \
    "http://bws.my-bcd.local/password/confirm" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -H "Authorization: Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs"
```

```javascript
const url = new URL(
    "http://bws.my-bcd.local/password/confirm"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer ZNaLkJ2S20ean3GfJSzTgFQ3MNlMz3JFBxdejSXs",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST password/confirm`


<!-- END_54462d3613f2262e741142161c0e6fea -->


