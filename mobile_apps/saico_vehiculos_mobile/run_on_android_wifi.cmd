@echo off
setlocal

set "FLUTTER_HOME=%USERPROFILE%\development\flutter"
set "ANDROID_SDK_ROOT=%USERPROFILE%\AppData\Local\Android\Sdk"
set "JAVA_HOME=%USERPROFILE%\development\jdk17"
set "PATH=%JAVA_HOME%\bin;%FLUTTER_HOME%\bin;%ANDROID_SDK_ROOT%\platform-tools;%ANDROID_SDK_ROOT%\cmdline-tools\latest\bin;%PATH%"
set "LOG_FILE=%~dp0run_on_android_wifi.log"
set "PROJECT_ROOT=%~dp0..\.."
set "DEVICE_ID=%~1"
set "API_HOST=192.168.1.242"
set "API_BASE_URL=http://%API_HOST%:8000/api/mobile/v1"

if "%DEVICE_ID%"=="" (
  for /f "skip=1 tokens=1" %%D in ('adb devices') do (
    if not "%%D"=="" if not "%%D"=="List" if "%DEVICE_ID%"=="" set "DEVICE_ID=%%D"
  )
)

if "%DEVICE_ID%"=="" (
  echo No se detecto ningun dispositivo Android para ejecutar la app.
  echo Conecta el celular o define el DEVICE_ID al llamar este script.
  pause
  exit /b 1
)

echo ============================================
echo SAICO Vehiculos Mobile - Android real por Wi-Fi
echo ============================================
echo.
echo IP actual configurada:
echo %API_HOST%
echo.
echo Dispositivo configurado:
echo %DEVICE_ID%
echo.
echo Guardando log en:
echo %LOG_FILE%
echo.
echo 0. Levantando API Laravel real en red local...
start "SAICO Mobile API WIFI" /min cmd /c "cd /d %PROJECT_ROOT% && php artisan serve --host=0.0.0.0 --port=8000 >> mobile_apps\saico_vehiculos_mobile\mobile_api_wifi.log 2>&1"
timeout /t 4 >nul
echo Resultado: %ERRORLEVEL%
echo.
echo 1. Verificando Flutter...
call flutter --version >> "%LOG_FILE%" 2>&1
echo Resultado: %ERRORLEVEL%
echo.
echo 2. Dispositivos detectados...
call flutter devices >> "%LOG_FILE%" 2>&1
echo Resultado: %ERRORLEVEL%
echo.
echo 3. Ejecutando la app real en Android por Wi-Fi sin pub get...
call flutter run --no-pub -d %DEVICE_ID% --dart-define=API_BASE_URL=%API_BASE_URL% --dart-define=USE_MOCK_DATA=false >> "%LOG_FILE%" 2>&1
echo Resultado final: %ERRORLEVEL%
echo.
echo Si algo fallo, abre este archivo:
echo %LOG_FILE%
echo.
pause
