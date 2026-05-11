@echo off
setlocal

set "FLUTTER_HOME=%USERPROFILE%\development\flutter"
set "ANDROID_SDK_ROOT=%USERPROFILE%\AppData\Local\Android\Sdk"
set "JAVA_HOME=%USERPROFILE%\development\jdk17"
set "PATH=%JAVA_HOME%\bin;%FLUTTER_HOME%\bin;%ANDROID_SDK_ROOT%\platform-tools;%PATH%"
set "API_HOST=192.168.1.242"
set "API_BASE_URL=http://%API_HOST%:8000/api/mobile/v1"
set "LOG_FILE=%~dp0build_wifi_apk.log"

echo ============================================
echo SAICO Vehiculos Mobile - Build APK por Wi-Fi
echo ============================================
echo.
echo IP actual configurada:
echo %API_HOST%
echo.
echo Guardando log en:
echo %LOG_FILE%
echo.

call flutter build apk --debug --no-pub --dart-define=API_BASE_URL=%API_BASE_URL% --dart-define=USE_MOCK_DATA=false >> "%LOG_FILE%" 2>&1

echo Resultado final: %ERRORLEVEL%
echo.
echo APK generada en:
echo %~dp0build\app\outputs\flutter-apk\app-debug.apk
echo.
pause
