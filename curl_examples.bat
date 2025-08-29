@echo off
REM Operator API Testing Script for Windows
REM Base URL - sesuaikan dengan environment Anda
set BASE_URL=http://localhost:8000/api

echo [INFO] Starting Operator API Testing...
echo.

REM Test 1: Login (Public endpoint)
echo [INFO] === Test 1: Authentication ===
set LOGIN_DATA={"username": "operator123", "password": "password123"}

curl -s -H "Content-Type: application/json" -H "Accept: application/json" -d %LOGIN_DATA% %BASE_URL%/auth/login > temp_response.txt

REM Extract token (simple extraction)
findstr "token" temp_response.txt > temp_token.txt
set /p TOKEN_LINE=<temp_token.txt
for /f "tokens=4 delims=:," %%i in ("%TOKEN_LINE%") do set AUTH_TOKEN=%%i
set AUTH_TOKEN=%AUTH_TOKEN:"=%

if defined AUTH_TOKEN (
    echo [SUCCESS] Login successful, token obtained
    echo [INFO] Token: %AUTH_TOKEN:~0,20%...
) else (
    echo [ERROR] Login failed, cannot continue testing
    type temp_response.txt
    goto cleanup
)

echo.

REM Test 2: Get User Info
echo [INFO] === Test 2: Get User Info ===
curl -s -H "Authorization: Bearer %AUTH_TOKEN%" -H "Accept: application/json" %BASE_URL%/auth/me

echo.
echo.

REM Test 3: Get Dashboard
echo [INFO] === Test 3: Get Dashboard ===
curl -s -H "Authorization: Bearer %AUTH_TOKEN%" -H "Accept: application/json" %BASE_URL%/operator/dashboard

echo.
echo.

REM Test 4: Scan Slot for Store
echo [INFO] === Test 4: Scan Slot for Store ===
set STORE_SCAN_DATA={"slot_name": "A-01-01", "erp_code": "ERP001", "quantity": 50, "lot_number": "LOT20250115"}
curl -s -X POST -H "Authorization: Bearer %AUTH_TOKEN%" -H "Content-Type: application/json" -H "Accept: application/json" -d %STORE_SCAN_DATA% %BASE_URL%/operator/store/scan-slot

echo.
echo.

REM Test 5: Store by ERP
echo [INFO] === Test 5: Store by ERP ===
set STORE_DATA={"slot_name": "A-01-01", "erp_code": "ERP001", "quantity": 50, "lot_number": "LOT20250115", "notes": "Stock baru dari supplier"}
curl -s -X POST -H "Authorization: Bearer %AUTH_TOKEN%" -H "Content-Type: application/json" -H "Accept: application/json" -d %STORE_DATA% %BASE_URL%/operator/store/by-erp

echo.
echo.

REM Test 6: Scan Slot for Pull
echo [INFO] === Test 6: Scan Slot for Pull ===
set PULL_SCAN_DATA={"slot_name": "A-01-01", "quantity": 20}
curl -s -X POST -H "Authorization: Bearer %AUTH_TOKEN%" -H "Content-Type: application/json" -H "Accept: application/json" -d %PULL_SCAN_DATA% %BASE_URL%/operator/pull/scan-slot

echo.
echo.

REM Test 7: Pull by Lot Number
echo [INFO] === Test 7: Pull by Lot Number ===
set PULL_DATA={"slot_name": "A-01-01", "quantity": 20, "lot_number": "LOT20250115", "notes": "Untuk production line A"}
curl -s -X POST -H "Authorization: Bearer %AUTH_TOKEN%" -H "Content-Type: application/json" -H "Accept: application/json" -d %PULL_DATA% %BASE_URL%/operator/pull/by-lot

echo.
echo.

REM Test 8: Get Slot Info
echo [INFO] === Test 8: Get Slot Info ===
curl -s -H "Authorization: Bearer %AUTH_TOKEN%" -H "Accept: application/json" %BASE_URL%/operator/slot/A-01-01

echo.
echo.

REM Test 9: Search Items
echo [INFO] === Test 9: Search Items ===
curl -s -H "Authorization: Bearer %AUTH_TOKEN%" -H "Accept: application/json" "%BASE_URL%/operator/search/items?query=bearing&limit=10"

echo.
echo.

REM Test 10: Get Activities
echo [INFO] === Test 10: Get Activities ===
curl -s -H "Authorization: Bearer %AUTH_TOKEN%" -H "Accept: application/json" "%BASE_URL%/operator/activities?action=store&limit=5"

echo.
echo.

REM Test 11: Refresh Token
echo [INFO] === Test 11: Refresh Token ===
curl -s -X POST -H "Authorization: Bearer %AUTH_TOKEN%" -H "Accept: application/json" %BASE_URL%/auth/refresh

echo.
echo.

REM Test 12: Logout
echo [INFO] === Test 12: Logout ===
curl -s -X POST -H "Authorization: Bearer %AUTH_TOKEN%" -H "Accept: application/json" %BASE_URL%/auth/logout

echo.
echo.
echo [INFO] === Testing Complete ===
echo [INFO] Check the responses above for any errors or issues.

:cleanup
REM Clean up temporary files
if exist temp_response.txt del temp_response.txt
if exist temp_token.txt del temp_token.txt

pause
